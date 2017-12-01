<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\RegistrationForm;

/**
 * This is the controller class displaying a page with the User Registration form.
 */
class RegistrationController extends AbstractActionController 
{
    /**
     * This is the default "index" action of the controller. It displays the 
     * User Registration page.
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
                
                // Register the user and send confirmation email.
                
                // Send user to success page.
                return $this->redirect()->toRoute('registration', 
                            ['action'=>'success']);
                
            }
        }
        
        $viewModel = new ViewModel([
            'form' => $form
        ]);
        
        return $viewModel;
    }
    
    /**
     * The "success" action shows a page letting the user know that registration
     * was successful.
     */
    public function successAction()
    {
        return new ViewModel();
    }
}