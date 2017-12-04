<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Form\RegistrationForm;

/**
 * This controller is responsible for user registration.
 */
class RegistrationController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Registration manager.
     * @var User\Service\RegistrationManager 
     */
    private $registrationManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $registrationManager)
    {
        $this->entityManager = $entityManager;
        $this->registrationManager = $registrationManager;
    }

    
    /**
     * This is the default action of the controller. It displays the 
     * User Registration page and handles form submission.
     */
    public function indexAction() 
    {             
        $form = new RegistrationForm();
        
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
                   $user = $this->registrationManager->registerUser($data);
                   $this->registrationManager->generateRegistrationConfiramtionToken($user);
                    return $this->redirect()->toRoute('registration', 
                                ['action'=>'message', 'id'=>'sent']);
                }
                $status = $user->getStatus();
                if ($status == 1){
                    // This user is already registered.
                    return $this->redirect()->toRoute('registration', 
                                ['action'=>'message', 'id'=>'exists']);   
                }
                if ($status == 2){
                    // This user is retired.  Send another confirmation.
                    $this->registrationManager->generateRegistrationConfiramtionToken($user);
                    return $this->redirect()->toRoute('registration', 
                                ['action'=>'message', 'id'=>'sent']); 
                }   
            }
        }
        
        $viewModel = new ViewModel([
            'form' => $form
        ]);
        
        return $viewModel;
    }
    
    /**
     * The "registration status" action shows a page letting the user know the registration
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
     * This action confirmed the user's registration through their email. 
     */
    public function confirmRegistrationAction()
    {
        $token = $this->params()->fromQuery('token', null);
        
        // Validate token length
        if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
            throw new \Exception('Invalid token type or length');
        }
        
        if($token===null || 
           !$this->registrationManager->validateRegistrationConfirmationToken($token)) {
            return $this->redirect()->toRoute('registration', 
                    ['action'=>'message', 'id'=>'failed']);
        }
                       
        //Set the user to active
        $this->registrationManager->confirmRegistration($token);
        
        return $this->redirect()->toRoute('registration', 
                    ['action'=>'message', 'id'=>'confirmed']);
        
    }
}


