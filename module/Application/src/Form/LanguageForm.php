<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to change the user's language. 
 */
class LanguageForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {        
        // Define form name
        parent::__construct('language-form');
     
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

        // Add "language" field
            $this->add([
                'type'  => 'select',
                'name' => 'language',
                'attributes' => [                
                    'id' => 'language'
                ],
                'options' => [
                    'label' => 'Language',
                    'value_options' => [
                        'en_US' => 'English',
                        'es_ES' => 'Español',
                    ]
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
                    'name'     => 'language',
                    'required' => true,            
                    'validators' => [
                        [
                            'name'    => 'InArray',
                            'options'=> [
                                'haystack' => ['en_US', 'es_ES'],
                            ]    
                        ],
                    ],
                ]);

    }
}