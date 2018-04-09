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
use Application\Form\FullNameForm;
use Application\Form\EmailForm;



class SettingsController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Settings manager.
     * @var Application\Service\SettingsManager 
     */
    private $settingsManager;
    
    /**
     * Mail sender.
     * @var Application\Service\MailSender
     */
    private $mailSender;
    
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $settingsManager, $mailSender) 
    {
       $this->entityManager = $entityManager;
       $this->settingsManager = $settingsManager;
       $this->mailSender = $mailSender;
    }  

    public function indexAction()
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
    * This action displays the user Full Name update page.
    */
    public function fullNameAction() 
    {   
        // Create Full Name form
        $form = new FullNameForm();
        
        $user = $this->currentUser();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                $fullName = $data['full_name'];
                $user->setFullName($fullName);
                // Apply changes to database.
                $this->entityManager->flush();

                // Redirect to "Settings" page
                return $this->redirect()->toRoute('settings');
            }               
        } else {

            $data = [
                'full_name' => $user->getFullName()
            ];
            
            $form->setData($data);
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    
     /**
    * This action displays the user Email update page.
    */
    public function emailAction() 
    {   
        // Create Email form
        $form = new EmailForm();
        
        $user = $this->currentUser();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($data['email']);
                if($user == null) {
                   $this->settingsManager->generateEmailResetToken($user, $data['email']);
                    return $this->redirect()->toRoute('settings', 
                                ['action'=>'message', 'id'=>'sent']);
                }
                
                // This email is already registered.
                return $this->redirect()->toRoute('settings', 
                            ['action'=>'message', 'id'=>'exists']);   

                // Redirect to "Settings" page
                return $this->redirect()->toRoute('settings');
            }               
        } else {

            $data = [
                'email' => $user->getEmail()
            ];
            
            $form->setData($data);
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    /**
     * The "email reset status" action shows a page letting the user know the email reset
     * status.
     */
    public function messageAction()
    {
        // Get message ID from route.
        $id = (string)$this->params()->fromRoute('id');
        
        // Validate input argument.
        if($id!='sent' && $id!='confirmed' && $id!='failed' && $id!='exists') {
            throw new \Exception('Invalid message ID specified');
        }
        
        return new ViewModel([
            'id' => $id
        ]);
    }
    
    /**
     * This action confirms the user's email.. 
     */
    public function confirmEmailAction()
    {
        $token = $this->params()->fromQuery('token', null);
        
        $newEmail = $this->params()->fromQuery('newemail', null);
        
        // Validate token length
        if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
            throw new \Exception('Invalid token type or length');
        }
        
        if($token===null || 
           !$this->settingsManager->validateEmailResetToken($token)) {
            return $this->redirect()->toRoute('settings', 
                    ['action'=>'message', 'id'=>'failed']);
        }
                       
        //Set the user to active
        $this->settingsManager->confirmEmail($token);
        
        return $this->redirect()->toRoute('settings', 
                    ['action'=>'message', 'id'=>'confirmed']);
        
    }
   
}
