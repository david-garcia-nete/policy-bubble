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
use Application\Form\PasswordForm;
use Zend\Crypt\Password\Bcrypt;
use Application\Form\AccountStatusForm;
use Application\Form\LanguageForm;




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
     * Auth manager.
     * @var User\Service\AuthManager
     */
    private $authManager;
    
    /**
     * Post manager.
     * @var Application\Service\PostManager 
     */
    private $postManager;
    
    /**
     * Image manager.
     * @var Application\Service\ImageManager;
     */
    private $imageManager;
    
    /**
     * Video manager.
     * @var Application\Service\VideoManager;
     */
    private $videoManager;
    
    /**
     * Audio manager.
     * @var Application\Service\AudioManager;
     */
    private $audioManager;
    
    /**
     * Mail sender.
     * @var Application\Service\MailSender
     */
    private $mailSender;
    
    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;
    
    /**
     * Translator.
     * @var Zend\I18n\Translator\Translator
     */
    private $translator;
    
    /**
     * User manager.
     * @var User\Service\UserManager 
     */
    private $userManager;
    
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $settingsManager, $authManager, 
            $postManager, $imageManager, $videoManager, $audioManager, 
            $mailSender, $sessionContainer, $translator, $userManager) 
    {
       $this->entityManager = $entityManager;
       $this->settingsManager = $settingsManager;
       $this->authManager = $authManager;
       $this->postManager = $postManager;
       $this->imageManager = $imageManager;
       $this->videoManager = $videoManager;
       $this->audioManager = $audioManager;
       $this->mailSender = $mailSender;
       $this->sessionContainer = $sessionContainer;
       $this->translator = $translator;
       $this->userManager = $userManager;
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
            'user' => $user,
            'userManager' => $this->userManager
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
        
        $currentUser = $this->currentUser();
        
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
                   $this->settingsManager->generateEmailResetToken($currentUser, $data['email']);
                    return $this->redirect()->toRoute('settings', 
                                ['action'=>'message', 'id'=>'sent']);
                }
                
                // This email is already registered.
                return $this->redirect()->toRoute('settings', 
                            ['action'=>'message', 'id'=>'exists']);   

            }               
        } else {

            $data = [
                'email' => $currentUser->getEmail()
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
        if($id!='sent' && $id!='confirmed' && $id!='failed' && $id!='exists' && $id!='passwordUpdated') {
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
                
        // Validate token length
        if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
            throw new \Exception('Invalid token type or length');
        }
        
        if($token===null || 
           !$this->settingsManager->validateEmailResetToken($token)) {
            return $this->redirect()->toRoute('settings', 
                    ['action'=>'message', 'id'=>'failed']);
        }
                       
        //Set the user email to the new email.
        $this->settingsManager->confirmEmail($token);
        
        if ($this->identity()!= null) {
               $this->authManager->logout();
            }
        
        return $this->redirect()->toRoute('settings', 
                    ['action'=>'message', 'id'=>'confirmed']);
        
    }
    
    /**
    * This action displays the user Language update page.
    */
    public function languageAction() 
    {   
        // Create Language form
        $form = new LanguageForm();
        
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
                $language = $data['language'];
                $user->setLanguage($language);
                // Apply changes to database.
                $this->entityManager->flush();
                
                $this->translator->setLocale($data['language']);
                
                $this->sessionContainer->Language = $data['language'];

                // Redirect to "Settings" page
                return $this->redirect()->toRoute('settings');
            }               
        } else {

            $data = [
                'language' => $user->getLanguage()
            ];
            
            $form->setData($data);
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    /**
    * This action displays the user Password update page.
    */
    public function passwordAction() 
    {   
        // Create Full Name form
        $form = new PasswordForm();
        
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
                // Encrypt password and store the password in encrypted state.
                $bcrypt = new Bcrypt();
                $passwordHash = $bcrypt->create($data['password']);        
                $user->setPassword($passwordHash);
                // Apply changes to database.
                $this->entityManager->flush();

                return $this->redirect()->toRoute('settings', 
                    ['action'=>'message', 'id'=>'passwordUpdated']);
            }               
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    /**
    * This action displays the user Account Status update page.
    */
    public function accountStatusAction() 
    {   
        // Create Full Name form
        $form = new AccountStatusForm();
        
        $user = $this->currentUser();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                $posts = $user->getPosts();
                foreach($posts as $post){
                    $this->postManager->removePost($post);
                    $this->imageManager->removePost($post->getId());
                    $this->videoManager->removePost($post->getId());
                    $this->audioManager->removePost($post->getId());  
                }
                
                $this->entityManager->remove($user);
                $this->entityManager->flush();
                $this->authManager->logout();
                
                return $this->redirect()->toRoute('home');
            }               
        } 
        
        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }
   
}
