<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use Application\Entity\Post;
use Application\Form\ContactForm;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PayPalConnectionException;
use Zend\Crypt\Password\Bcrypt;
use Application\Entity\TransactionsPayPal;
use PayPal\Api\PaymentExecution;
use Zend\Config\Config;


class IndexController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Mail sender.
     * @var Application\Service\MailSender
     */
    private $mailSender;
    
    /**
     * Membership Manager.
     * @var Application\Service\MembershipManager
     */
    private $membershipManager;
    
    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;
    
    /**
     * Local Configuration
     */
    private $localConfig;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $mailSender, $membershipManager, 
            $sessionContainer) 
    {
       $this->entityManager = $entityManager;
       $this->mailSender = $mailSender;
       $this->membershipManager = $membershipManager;
       $this->sessionContainer = $sessionContainer;
       $this->localConfig = new Config(include './config/autoload/local.php');
    }
    
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function aboutAction()
    {
        return new ViewModel();
    }
    
    public function privacyPolicyAction()
    {
        return new ViewModel();
    }
    
    public function disclosurePolicyAction()
    {
        return new ViewModel();
    }
    
    public function analysisAction()
    {
       return new ViewModel([
            'entityManager' => $this->entityManager
        ]);
    }
    
    public function popularTagsAction()
    {
       return new ViewModel();
    }
    
     /**
     * The "settings" action displays the info about currently logged in user.
     */
    public function settingsAction()
    {
        $id = $this->params()->fromRoute('id');
        
        if ($id!=null) {
            $user = $this->entityManager->getRepository(User::class)
                    ->find($id);
        } else {
            $user = $this->currentUser();
        }
        
        if ($user==null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        if (!$this->access('profile.any.view') && 
            !$this->access('profile.own.view', ['user'=>$user])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        return new ViewModel([
            'user' => $user
        ]);
    }
    
    /**
     * This action displays the Contact Us page.
     */
    public function contactUsAction() 
    {   
        // Create Contact Us form
        $form = new ContactForm();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                $email = $data['email'];
                $subject = $data['subject'];
                $body = $data['body'];
                
                // Send E-mail
                if(!$this->mailSender->sendMail('david.garcia.nete@gmail.com', $email, 
                        $subject, $body)) {
                    // In case of error, redirect to "Error Sending Email" page
                    return $this->redirect()->toRoute('application', 
                            ['action'=>'sendError']);
                }
                
                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('application', 
                        ['action'=>'thankYou']);
            }               
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    /**
     * This action displays the Thank You page. The user is redirected to this
     * page on successful mail delivery.
     */
    public function thankYouAction() 
    {
        return new ViewModel();
    }
    
    /**
     * This action displays the Send Error page. The user is redirected to this
     * page on mail delivery error.
     */
    public function sendErrorAction() 
    {
        return new ViewModel();
    }
    
    public function membershipAction() 
    { 
        
        // If the transaction was approved on the PayPal side.
        if($this->params()->fromQuery('approved', 'false') == 'true'){
            $payerId = $this->params()->fromQuery('PayerID');
                      
            $transaction = $this->entityManager->getRepository(TransactionsPayPal::class)
                        ->findOneBy(array('hash' => $this->sessionContainer->payPal['hash']));
            $paymentId = $transaction->getPaymentId();
            
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->localConfig->payPal->clientId,     
                    $this->localConfig->payPal->clientSecret     
                )
            );
            
            $apiContext->setConfig(['mode' => $this->localConfig->payPal->mode]);
            
            $payment = Payment::get($paymentId, $apiContext);
            
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            
            $payment->execute($execution, $apiContext);
            
            // Update transaction. Set complete = 1 where payment id = payment id.
            // I may want to add more info such as date and member level
            $transaction->setComplete(1);
            $transaction->setMembership($this->sessionContainer->payPal['membership']);
            $currentDate = date('Y-m-d H:i:s');
            $transaction->setDateCompleted($currentDate);
            $this->entityManager->flush();
            //Unset session containter array
            $this->sessionContainer->payPal = null;
            
                    
        }
        
        if($this->getRequest()->isPost()) {
            
            if ($this->identity()== null) {
                return $this->redirect()->toRoute('login');
            }
            
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->localConfig->payPal->clientId,     
                    $this->localConfig->payPal->clientSecret      
                )
            );
            
            $apiContext->setConfig(['mode' => $this->localConfig->payPal->mode]);

            $payer = new Payer();
            $details = new Details();
            $amount = new Amount();
            $transaction = new Transaction();
            $payment = new Payment();
            $redirectUrls = new RedirectUrls();
            
            $payer->setPaymentMethod('paypal');
            
            $data = $this->params()->fromPost();
            $selection = explode('-', $data['os0']);
            $amount->setTotal($selection[1]);
            $amount->setCurrency('USD');
            
            $transaction->setAmount($amount);
            
            $redirectUrls->setReturnUrl("https://" . $this->localConfig->domainName . "/membership?approved=true")
                ->setCancelUrl("https://" . $this->localConfig->domainName . "/membership?approved=false");
            
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);
            
            try {
                $payment->create($apiContext);
                
                $bcrypt = new Bcrypt();
                $hash = $bcrypt->create($payment->getId());
                $this->sessionContainer->payPal = [];
                $this->sessionContainer->payPal['hash'] = $hash;
                $this->sessionContainer->payPal['membership'] = $selection[0];
                
                $user = $this->currentUser();
                $this->membershipManager->addNewTransaction($user, $payment, 
                        $hash, $selection[0], $selection[1]);
                
            }
            catch (PayPalConnectionException $ex) {
                // This will print the detailed information on the exception.
                //REALLY HELPFUL FOR DEBUGGING
                echo $ex->getData();
            }
            
            foreach($payment->getLinks() as $link){
                if ($link->getRel() == 'approval_url'){
                    $redirectUrl = $link->getHref();
                }
            }
 
            $this->redirect()->toUrl($redirectUrl);
  
        }    
        
        // Get the user's membership status
        $user = $this->currentUser();
        $membershipStatus = $this->membershipManager->getMembership($user);
        
        // Get the user's post count for this month
        $postCount = $this->entityManager->getRepository(Post::class)
                ->findMonthPostCountByUser($user);
      
        return new ViewModel([
            'membershipStatus' => $membershipStatus,
            'postCount' => $postCount
                
        ]);
    }
}
