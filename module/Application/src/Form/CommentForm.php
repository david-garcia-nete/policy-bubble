<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
/**
 * This form is used to collect comment data.
 */
class CommentForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('comment-form');
     
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
        // Add "author" field
        $this->add([        
            'type'  => 'text',
            'name' => 'author',
            'attributes' => [
                'id' => 'author'
            ],
            'options' => [
                'label' => 'Author',
            ],
        ]);
        
        // Add "comment" field
        $this->add([            
            'type'  => 'textarea',
            'name' => 'comment',
            'attributes' => [
                'id' => 'comment'
            ],
            'options' => [
                'label' => 'Comment',
            ],
        ]);
        
        // Add "file" field.
            $this->add([
                'type'  => 'file',
                'name' => 'file',
                'attributes' => [                
                    'id' => 'file'
                ],
                'options' => [
                    'label' => 'Image file',
                ],
            ]);      
                
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Save',
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
                'name'     => 'author',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
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
                'name'     => 'comment',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StripTags'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 4096
                        ],
                    ],
                ],
            ]); 
        
        // Add validation rules for the "file" field.	 
            $inputFilter->add([
                    'type'     => 'Zend\InputFilter\FileInput',
                    'name'     => 'file',
                    'required' => false,   
                    'validators' => [
                        ['name'    => 'FileUploadFile'],
                        [
                            'name'    => 'FileMimeType',                        
                            'options' => [                            
                                'mimeType'  => ['image/jpeg', 'image/png']
                            ]
                        ],
                        ['name'    => 'FileIsImage'],
                        [
                            'name'    => 'FileImageSize',
                            'options' => [
                                'minWidth'  => 128,
                                'minHeight' => 128,
                                'maxWidth'  => 4096,
                                'maxHeight' => 4096
                            ]
                        ],
                    ],
                ]); 
    }
}