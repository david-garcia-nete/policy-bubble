<?php
namespace Application\Validator;

use Zend\Validator\AbstractValidator;

/**
 * This validator class is designed for checking a phone number for conformance to
 * the local or to the international format.
 */
class MaxFileValidator extends AbstractValidator 
{
    // Phone format constants
    const MAX100 = 100; // Local phone format "333-7777" 
    const MAX10 = 10; // Local phone format "333-7777" 
    const ID = null; // Local phone format "333-7777" 
    
    /**
     * The directory where we save image files.
     * @var string
     */
    private $saveToDir = './data/upload/';
    
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'max'        => self::MAX100,
        'id'        => self::ID
    ];
    
    // Validation failure message IDs.
    const NOT_SCALAR  = 'notScalar';
    const INVALID_MAX_100  = 'invalidMax100';
    const INVALID_MAX_10 = 'invalidMax10';
    
    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SCALAR  => "The id must be a scalar value",
        self::INVALID_MAX_100  => "The post photo count must be less than 100",
        self::INVALID_MAX_10 => "The post photo count must be less than 10",
    ];
    
    /**
     * Constructor.
     * @param string One of PHONE_FORMAT_-prefixed constants.
     */
    public function __construct($options = null) 
    {
        // Set filter options (if provided).
        if(is_array($options)) {
            
            if(isset($options['max']))
                $this->setMax($options['max']);
            if(isset($options['id']))
                $this->setId($options['id']);
        }
        
        // Call the parent class constructor
        parent::__construct($options);
    }
    
    /**
     * Sets phone format.
     * @param string One of PHONE_FORMAT_-prefixed constants.
     */
    public function setMax($max) 
    {
        // Check input argument.
        if($max!=self::MAX100 && $max!=self::MAX10) {            
            throw new \Exception('Invalid format argument passed.');
        }
        
        $this->options['max'] = $max;
    }
    
    /**
     * Sets phone format.
     * @param string One of PHONE_FORMAT_-prefixed constants.
     */
    public function setId($id) 
    {
        if(!is_scalar($id)) {
            $this->error(self::NOT_SCALAR);
            return $false; // Phone number must be a scalar.
        }
        
        $this->options['id'] = $id;
    }
    
    /**
     * Validates a phone number.
     * @param string $value User-entered phone number.
     * @return boolean true if the number is valid; otherwise false.
     */
    public function isValid($value) 
    {
                    
                
        $max = $this->options['max'];
        $id = $this->options['id'];
        
        
        if(!is_scalar($id)) {
            $this->error(self::NOT_SCALAR);
            return $false; // Phone number must be a scalar.
        }
                
        // Determine the correct length and pattern of the phone number,
        // depending on the format.        
        if($max == self::MAX100) {
            $correctMax = 100;
        } else { // self::PHONE_FORMAT_LOCAL
            $correctMax = 10;
        }
        
        // First check phone number length
        $isValid = false;
        $tempDir = $this->saveToDir . 'post/' . $id . '/temp/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        $$filecount = 0;
        $files = glob($tempDir . "*");
        if ($files){
         $filecount = count($files);
        }
        if($filecount<$correctMax)
        $isValid = true;
        
               
        // If there were an error, set error message.
        if(!$isValid) {            
            if($max==self::MAX100)
                $this->error(self::INVALID_MAX_100);
            else
                $this->error(self::INVALID_MAX_10);
        }
        
        // Return validation result.
        return $isValid;
    }
}
