<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Post;
/**
 * This form is used to collect post data.
 */
class PostForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('post-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Set binary content encoding.
        $this->setAttribute('enctype', 'multipart/form-data');
                
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
            'name' => 'title',
            'attributes' => [
                'id' => 'title'
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);
        
        // Add "content" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'content',
            'attributes' => [                
                'id' => 'content'
            ],
            'options' => [
                'label' => 'Content',
            ],
        ]);
        
        // Add "tags" field
        $this->add([
            'type'  => 'text',
            'name' => 'tags',
            'attributes' => [                
                'id' => 'tags'
            ],
            'options' => [
                'label' => 'Tags',
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
        
        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'attributes' => [                
                'id' => 'status'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Post::STATUS_PUBLISHED => 'Published',
                    Post::STATUS_DRAFT => 'Draft',
                ]
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
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
                'name'     => 'title',
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
        
        $inputFilter->add([
                'name'     => 'content',
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
        
        $inputFilter->add([
                'name'     => 'tags',
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
        
        // Add validation rules for the "file" field.	 
        $inputFilter->add([
                'type'     => 'Zend\InputFilter\FileInput',
                'name'     => 'file',
                'required' => true,   
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
                'filters'  => [                    
                    [
                        'name' => 'FileRenameUpload',
                        'options' => [  
                            'target'=>'./data/upload',
                            'useUploadName'=>true,
                            'useUploadExtension'=>true,
                            'overwrite'=>true,
                            'randomize'=>false
                        ]
                    ]
                ],   
            ]);                
    }
}