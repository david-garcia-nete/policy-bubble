<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect post data.
 */
class SearchForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('search-form');
     
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
       
                
        // Add "title" field
        $this->add([        
            'type'  => 'text',
            'name' => 'search',
            'attributes' => [
                'id' => 'search'
            ],
            'options' => [
                'label' => 'Search Tags',
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
                'value' => 'Search',
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
                'name'     => 'search',
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
                            'max' => 1024
                        ],
                    ],
                ],
        ]);

    }
}