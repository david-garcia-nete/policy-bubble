<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user demographic data like user nationality, 
 * location and gender.
 */
class DemographicForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('contact-form');
     
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
        
        $this->add([           
            'type'  => 'text',
            'name' => 'country',
            'attributes' => [
                'id' => 'country'
            ],
            'options' => [
                'label' => 'Country',
            ],
        ]);
        
        $this->add([           
            'type'  => 'text',
            'name' => 'state',
            'attributes' => [
                'id' => 'state'
            ],
            'options' => [
                'label' => 'State',
            ],
        ]);
        
        $this->add([           
            'type'  => 'text',
            'name' => 'city',
            'attributes' => [
                'id' => 'city'
            ],
            'options' => [
                'label' => 'City',
            ],
        ]);

        $this->add([
            'type'  => 'select',
            'name' => 'gender',
            'attributes' => [                
                'id' => 'gender'
            ],
            'options' => [
                'label' => 'Gender',
                'value_options' => [
                    '' => '',
                    Demographic::GENDER_FEMALE => 'Female',
                    Demographic::GENDER_MALE => 'Male',
                ]
            ],
        ]);
        
        $years = [];
        $years [''] = '';
        $currentYear = date('Y');
        $i = 1;
        while($i <= 125){
            $years[$currentYear] = $currentYear;
            $currentYear--;
            $i++;  
        }
        
        $this->add([
            'type'  => 'select',
            'name' => 'birthYear',
            'attributes' => [                
                'id' => 'birthYear'
            ],
            'options' => [
                'label' => 'Birth Year',
                'value_options' => $years
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
                'name'     => 'country',
                'required' => false,
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
        
        $inputFilter->add([
                'name'     => 'state',
                'required' => false,
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
        
        $inputFilter->add([
                'name'     => 'city',
                'required' => false,
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
        
    }
    
   
}
