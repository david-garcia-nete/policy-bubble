<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Post;
use Application\Validator\MaxFileValidator;
use Application\Validator\MaxVideoValidator;
use Application\Validator\MaxAudioValidator;
/**
 * This form is used to collect post data.
 */
class PostForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct($step, $id)
    {
        
        // Check input.

        if (!is_int($step) || $step<1 || $step>4)

            throw new \Exception('Step is invalid');
        
        // Define form name
        parent::__construct('post-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Set binary content encoding.
        $this->setAttribute('enctype', 'multipart/form-data');
                
        $this->addElements($step);
        $this->addInputFilter($step, $id);  
        
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements($step) 
    {
        if ($step==1) {
                
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
                        Post::STATUS_PUBLISHED => 'Public',
                        Post::STATUS_DRAFT => 'Private',
                    ]
                ],
            ]);

          
        }
        
        else if ($step==2) {
            
            // Add "file" field.
            $this->add([
                'type'  => 'file',
                'name' => 'file',
                'attributes' => [                
                    'id' => 'file',
                    'multiple' => true
                ],
                'options' => [
                    'label' => 'Image file',
                ],
            ]);      
        }
        
        else if ($step==3) {
            
            // Add "file" field.
            $this->add([
                'type'  => 'file',
                'name' => 'video',
                'attributes' => [                
                    'id' => 'video'
                ],
                'options' => [
                    'label' => 'Video file',
                ],
            ]);      
        }
        
        else if ($step==4) {
            
            // Add "file" field.
            $this->add([
                'type'  => 'file',
                'name' => 'audio',
                'attributes' => [                
                    'id' => 'audio'
                ],
                'options' => [
                    'label' => 'Audio file',
                ],
            ]);      
        }
                
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Next Step',
                'id' => 'submitbutton',
            ],
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter($step, $id) 
    {
        
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        if ($step==1) {
        
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
        }
        
        if ($step==2) {
            
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
                        [
                            'name' => MaxFileValidator::class,
                            'options' => [
                              'min' => 0,
                              'max'  => 100,
                              'id'=> $id
                            ]                        
                        ],
                    ],
                    'filters'  => [                    
                        [
                            'name' => 'FileRenameUpload',
                            'options' => [  
                                'target'=>'./data/image/post/' . $id . '/',
                                'useUploadName'=>true,
                                'useUploadExtension'=>true,
                                'overwrite'=>true,
                                'randomize'=>false
                            ]
                        ]
                    ],   
                ]); 
        }
        
        if ($step==3) {
            
            // Add validation rules for the "file" field.	 
            $inputFilter->add([
                    'type'     => 'Zend\InputFilter\FileInput',
                    'name'     => 'video',
                    'required' => false,   
                    'validators' => [
                        ['name'    => 'FileUploadFile'],
                        [
                            'name'    => 'FileMimeType',                        
                            'options' => [                            
                                'mimeType'  => ['video/mp4', 'video/ogg', 'video/quicktime']
                            ]
                        ],
                        [
                            'name'    => 'FileSize',
                            'options' => [
                                'min' => '10kB',
			        'max' => '2GB',
                            ]
                        ],
                        [
                            'name' => MaxVideoValidator::class,
                            'options' => [
                              'min' => 0,
                              'max'  => 1,
                              'id'=> $id
                            ]                        
                        ],
                    ],
                    'filters'  => [                    
                        [
                            'name' => 'FileRenameUpload',
                            'options' => [  
                                'target'=>'./public/video/post/' . $id . '/',
                                'useUploadName'=>true,
                                'useUploadExtension'=>true,
                                'overwrite'=>true,
                                'randomize'=>false
                            ]
                        ]
                    ],   
                ]); 
        }
        
        if ($step==4) {
            
            // Add validation rules for the "file" field.	 
            $inputFilter->add([
                    'type'     => 'Zend\InputFilter\FileInput',
                    'name'     => 'audio',
                    'required' => false,   
                    'validators' => [
                        ['name'    => 'FileUploadFile'],
                        [
                            'name'    => 'FileMimeType',                        
                            'options' => [                            
                                'mimeType'  => ['audio/mpeg', 'audio/ogg', 'audio/x-m4a']
                            ]
                        ],
                        [
                            'name'    => 'FileSize',
                            'options' => [
                                'min' => '10kB',
			        'max' => '1GB',
                            ]
                        ],
                        [
                            'name' => MaxAudioValidator::class,
                            'options' => [
                              'min' => 0,
                              'max'  => 1,
                              'id'=> $id
                            ]                        
                        ],
                    ],
                    'filters'  => [                    
                        [
                            'name' => 'FileRenameUpload',
                            'options' => [  
                                'target'=>'./public/audio/post/' . $id . '/',
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
}