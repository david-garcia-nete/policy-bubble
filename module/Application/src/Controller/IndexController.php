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
use Zend\Crypt\Password\Bcrypt;

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
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $mailSender, $membershipManager, 
            $sessionContainer) 
    {
       $this->entityManager = $entityManager;
       $this->mailSender = $mailSender;
       $this->membershipManager = $membershipManager;
       $this->sessionContainer = $sessionContainer;
    }
    
    
    public function indexAction()
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
        
        if($this->getRequest()->isPost()) {
            
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    'AVI5ewWx9QqD2rERLTae6tTwGhjc2R2c476aXYawIAljDaUeh3svqBWIN4jE86KTlXQBy2hMwNjjvvKR',     // ClientID
                    'ECThE6oCr1UzrP2wz4O4eczkDkSlMhoDSNRB4tin1SbHXwlQetKCGgH-6kN0up8jI2TGFNjelAXZYZ3z'      // ClientSecret
                )
            );

            $payer = new Payer();
            $details = new Details();
            $amount = new Amount();
            $transaction = new Transaction();
            $payment = new Payment();
            $redirectUrls = new RedirectUrls();
            
            $payer->setPaymentMethod('paypal');
            
            $data = $this->params()->fromPost();
            $total = $data['os0'];
            $amount->setTotal($total);
            $amount->setCurrency('USD');
            
            $transaction->setAmount($amount);
            
            $redirectUrls->setReturnUrl("http://policybubble.com/membership?approved=true")
                ->setCancelUrl("http://policybubble.com/membership?approved=false");
            
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);
            
            try {
                $payment->create($apiContext);
                
                $bcrypt = new Bcrypt();
                $hash = $bcrypt->create($payment->getId());
                $this->sessionContainer->paypal_hash = $hash;
                
                $user = $this->currentUser();
                $this->membershipManager->addNewTransaction($user, $payment, $hash);
                
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
        $membershipStatus = $user->getMembershipAsString();
        
        // Get the user's post count for this month
        $postCount = $this->entityManager->getRepository(Post::class)
                ->findMonthPostCountByUser($user);
      
        return new ViewModel([
            'membershipStatus' => $membershipStatus,
            'postCount' => $postCount
                
        ]);
    }
}
