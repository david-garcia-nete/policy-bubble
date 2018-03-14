<?php
namespace Application\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * This validator class is designed for checking a phone number for conformance to
 * the local or to the international format.
 */
class MaxAudioValidator extends AbstractValidator 
{
    /**#@+
     * @const string Error constants
     */
    const TOO_MANY = 'fileCountTooMany';
    const TOO_FEW  = 'fileCountTooFew';
    /**#@-*/

    /**
     * @var array Error message templates
     */
    protected $messageTemplates = [
        self::TOO_MANY => "Too many files, maximum '%max%' post audio file is allowed.",
        self::TOO_FEW  => "Too few files, minimum '%min%'  post audios are expected.  '%existCount%' exist and '%selectCount%' are selected.",
    ];

    /**
     * @var array Error message template variables
     */
    protected $messageVariables = [
        'min'   => ['options' => 'min'],
        'max'   => ['options' => 'max'],
        'existCount' => 'existCount',
        'selectCount' => 'selectCount'
    ];

    /**
     * Actual filecount
     *
     * @var int
     */
    protected $count;
    
    /**
     * Existing filecount
     *
     * @var int
     */
    protected $existCount;
    
    /**
     * Selected filecount
     *
     * @var int
     */
    protected $selectCount;

    /**
     * Internal file array
     * @var array
     */
    protected $files;
    
    /**
     * The directory where we save image files.
     * @var string
     */
    private $saveToDir = './public/audio/';

    /**
     * Options for this validator
     *
     * @var array
     */
    protected $options = [
        'min' => null,  // Minimum file count, if null there is no minimum file count
        'max' => null,  // Maximum file count, if null there is no maximum file count
        'id' => null
    ];

    /**
     * Sets validator options
     *
     * Min limits the file count, when used with max=null it is the maximum file count
     * It also accepts an array with the keys 'min' and 'max'
     *
     * If $options is an integer, it will be used as maximum file count
     * As Array is accepts the following keys:
     * 'min': Minimum filecount
     * 'max': Maximum filecount
     *
     * @param  int|array|\Traversable $options Options for the adapter
     */
    public function __construct($options = null)
    {
        if (1 < func_num_args()) {
            $args = func_get_args();
            $options = [
                'min' => array_shift($args),
                'max' => array_shift($args),
            ];
        }

        if (is_string($options) || is_numeric($options)) {
            $options = ['max' => $options];
        }

        parent::__construct($options);
    }

    /**
     * Returns the minimum file count
     *
     * @return int
     */
    public function getMin()
    {
        return $this->options['min'];
    }

    /**
     * Sets the minimum file count
     *
     * @param  int|array $min The minimum file count
     * @return Count Provides a fluent interface
     * @throws Exception\InvalidArgumentException When min is greater than max
     */
    public function setMin($min)
    {
        if (is_array($min) && isset($min['min'])) {
            $min = $min['min'];
        }

        if (! is_numeric($min)) {
            throw new Exception\InvalidArgumentException('Invalid options to validator provided');
        }

        $min = (int) $min;
        if (($this->getMax() !== null) && ($min > $this->getMax())) {
            throw new Exception\InvalidArgumentException(
                "The minimum must be less than or equal to the maximum file count, but {$min} > {$this->getMax()}"
            );
        }

        $this->options['min'] = $min;
        return $this;
    }

    /**
     * Returns the maximum file count
     *
     * @return int
     */
    public function getMax()
    {
        return $this->options['max'];
    }

    /**
     * Sets the maximum file count
     *
     * @param  int|array $max The maximum file count
     * @return Count Provides a fluent interface
     * @throws Exception\InvalidArgumentException When max is smaller than min
     */
    public function setMax($max)
    {
        if (is_array($max) && isset($max['max'])) {
            $max = $max['max'];
        }

        if (! is_numeric($max)) {
            throw new Exception\InvalidArgumentException('Invalid options to validator provided');
        }

        $max = (int) $max;
        if (($this->getMin() !== null) && ($max < $this->getMin())) {
            throw new Exception\InvalidArgumentException(
                "The maximum must be greater than or equal to the minimum file count, but {$max} < {$this->getMin()}"
            );
        }

        $this->options['max'] = $max;
        return $this;
    }
    
    /**
     * Sets post id.
     */
    public function setId($id) 
    {
        if (! is_numeric($id)) {
            throw new Exception\InvalidArgumentException('Invalid options to validator provided');
        }
        
        $this->options['id'] = $id;
    }

    /**
     * Adds a file for validation
     *
     * @param string|array $file
     * @return Count
     */
    public function addFile($file)
    {
        if (is_string($file)) {
            $file = [$file];
        }

        if (is_array($file)) {
            foreach ($file as $name) {
                if (! isset($this->files[$name]) && ! empty($name)) {
                    $this->files[$name] = $name;
                }
            }
        }

        return $this;
    }

    /**
     * Returns true if and only if the file count of all checked files is at least min and
     * not bigger than max (when max is not null). Attention: When checking with set min you
     * must give all files with the first call, otherwise you will get a false.
     *
     * @param  string|array $value Filenames to check for count
     * @param  array        $file  File data from \Zend\File\Transfer\Transfer
     * @return bool
     */
    public function isValid($value, $file = null)
    {
        
        $id = $this->options['id'];
        
        // First check phone number length
        $isValid = false;
        $tempDir = $this->saveToDir . 'post/' . $id . '/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        $tempCount = 0;
        $files = glob($tempDir . "*");
        if ($files){
         $tempCount = count($files);
        }
        
        
        $uploadCount = 1;
                
        
        $total = $tempCount + $uploadCount;
        $this->count = $total;
        $this->existCount = $tempCount;
        $this->selectCount = $uploadCount;
        if (($this->getMax() !== null) && ($this->count > $this->getMax())) {
            return $this->throwError($file, self::TOO_MANY);
        }

        if (($this->getMin() !== null) && ($this->count < $this->getMin())) {
            return $this->throwError($file, self::TOO_FEW);
        }

        return true;
    }

    /**
     * Throws an error of the given type
     *
     * @param  string $file
     * @param  string $errorType
     * @return false
     */
    protected function throwError($file, $errorType)
    {
        if ($file !== null) {
            if (is_array($file)) {
                if (array_key_exists('name', $file)) {
                    $this->value = $file['name'];
                }
            } elseif (is_string($file)) {
                $this->value = $file;
            }
        }

        $this->error($errorType);
        return false;
    }
}
