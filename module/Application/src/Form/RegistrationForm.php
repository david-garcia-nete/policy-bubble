<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user registration data. 
 */
class RegistrationForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {        
        // Define form name
        parent::__construct('registration-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
                
        $this->addElements();
        $this->addInputFilter(); 
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    { 
        // Add "email" field
        $this->add([           
            'type'  => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'email'
            ],
            'options' => [
                'label' => 'Your E-mail',
            ],
        ]);

        // Add "full_name" field
        $this->add([           
            'type'  => 'text',
            'name' => 'full_name',
            'attributes' => [
                'id' => 'full_name'
            ],
            'options' => [
                'label' => 'Full Name',
            ],
        ]);

        // Add "password" field
        $this->add([           
            'type'  => 'password',
            'name' => 'password',
            'attributes' => [
                'id' => 'password'
            ],
            'options' => [
                'label' => 'Choose Password',
            ],
        ]);

        // Add "confirm_password" field
        $this->add([           
            'type'  => 'password',
            'name' => 'confirm_password',
            'attributes' => [
                'id' => 'confirm_password'
            ],
            'options' => [
                'label' => 'Type Password Again',
            ],
        ]);           
            
        // Add the CSRF field
        $this->add([
            'type'  => 'csrf',
            'name' => 'csrf',
            'attributes' => [],
            'options' => [                
                'csrf_options' => [
                     'timeout' => 600
                ]
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Submit',
                'id' => 'submitbutton',
            ],
        ]);        
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck'    => false,                            
                        ],
                    ],
                ],
            ]);

        $inputFilter->add([
            'name'     => 'full_name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        // Add input for "password" field
        $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);  

        // Add input for "confirm_password" field
        $inputFilter->add([
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [
                ],       
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'password',                            
                        ],
                    ],
                ],
            ]);
    }
}