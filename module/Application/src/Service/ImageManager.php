<?php
/**
 * A service model class encapsulating the functionality for image management.
 */
namespace Application\Service;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Zend\Config\Config;

/**
 * The image manager service. Responsible for getting the list of uploaded
 * files and resizing the images.
 */
class ImageManager 
{
    /**
     * The directory where we save image files.
     * @var string
     */
    private $saveToDir = './data/upload/';
    
    /**
     * The AWS S3 client
     */
    private $s3client = null;
    
    /**
     * The AWS S3 bucket
     */
    private $s3bucket = null;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $config = new Config(include './config/autoload/local.php');
        $this->s3client = S3Client::factory($config->s3->s3client->toArray());
        $this->s3bucket = $config->s3->s3bucket;
    }
    
        
    /**
     * Returns path to the directory where we save the image files.
     * @return string
     */
    public function getSaveToDir() 
    {
        return $this->saveToDir;
    }
    
    /**
     * Returns the path to the saved image file.
     * @param string $fileName Image file name (without path part).
     * @return string Path to image file.
     */
    public function getImagePathByName($fileName, $id, $loc)
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
                
        // Return concatenated directory name and file name.
        return $this->saveToDir . 'post/' . $id . "/$loc/" . $fileName;                
    }
    
    /**
     * Returns the path to the saved image file.
     * @param string $fileName Image file name (without path part).
     * @return string Path to image file.
     */
    public function getAddImagePathByName($fileName, $id)
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
                
        // Return concatenated directory name and file name.
        return $this->saveToDir . 'user/' . $id . "/" . $fileName;                
    }


    /**
     * Returns the array of saved file names.
     * @return array List of uploaded file names.
     */
    public function getSavedFiles($id) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $id . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }

        // Scan the directory and create the list of uploaded files.
        $files = array();        
        $handle  = opendir($permDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            
            $files[] = $entry;
        }
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Returns the array of saved file names.
     * @return array List of uploaded file names.
     */
    public function getFirstSavedFiles($id, $count = 2) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $id . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }

        // Scan the directory and create the list of uploaded files.
        $files = array();        
        $handle  = opendir($permDir);
        $i=0;
        while ((false !== ($entry = readdir($handle))) && $i<$count) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            
            $files[] = $entry;
            $i++;
        }
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Returns the array of temp file names.
     * @return array List of uploaded file names.
     */
    public function getTempFiles($id, $dirty) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $tempDir = $this->saveToDir . 'post/' . $id . '/temp/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $id . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        if (!$dirty){
            // Delete all files
            $paths = glob($tempDir . '*'); // get all file names
            foreach($paths as $file){ // iterate files
                if(is_file($file))
                unlink($file); // delete file
            }    
        
            // Copy all files
            $permDir = $this->saveToDir . 'post/' . $id . '/perm/';
            $dir = opendir($permDir);  
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) {      
                    copy($permDir . $file, $tempDir . $file); 
                } 
            } 
            closedir($dir);
         }
        
        // Scan the directory and create the list of uploaded files.
        $files = array();        
        $handle  = opendir($tempDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            
            $files[] = $entry;
        }
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function saveTempFiles($id) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $id . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        // Delete all files
        $paths = glob($permDir . '*'); // get all file names
        foreach($paths as $file){ // iterate files
            if(is_file($file))
            unlink($file); // delete file
        }
        
        // Copy all files
        $tempDir = $this->saveToDir . 'post/' . $id . '/temp/';
        $dir = opendir($tempDir);  
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) {      
                //copy($tempDir . $file, $permDir . $file);
                try{
                    $this->s3client->putObject([
                        'Bucket' => $this->s3bucket,
                        'Key' => 'data/upload/post/' . $id . '/perm/' . $file,
                        'Body' => $tempDir . $file,
                        'ACL' => 'public-read'
                    ]);                    
                } catch(S3Exception $e){
                    die ("There was an error uploading that file.");
                }
            } 
        }  
        closedir($dir);

        // Remove temp dir
        array_map('unlink', glob($tempDir . '*.*'));
        rmdir($tempDir);
    }
    
    /**
     * Returns the array of temp file names.
     * @return array List of uploaded file names.
     */
    public function getAddTempFiles($id, $dirty) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $tempDir = $this->saveToDir . 'user/' . $id . '/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
                
        if (!$dirty){
            // Delete all files
            $paths = glob($tempDir . '*'); // get all file names
            foreach($paths as $file){ // iterate files
                if(is_file($file))
                unlink($file); // delete file
            }    
         }
        
        // Scan the directory and create the list of uploaded files.
        $files = array();        
        $handle  = opendir($tempDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            
            $files[] = $entry;
        }
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function saveAddTempFiles($postId, $userId) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $postId . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        // Delete all files
        $paths = glob($permDir . '*'); // get all file names
        foreach($paths as $file){ // iterate files
            if(is_file($file))
            unlink($file); // delete file
        }
        
        // Copy all files
        $tempDir = $this->saveToDir . 'user/' . $userId . '/';
        $dir = opendir($tempDir);  
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) {      
                copy($tempDir . $file, $permDir . $file); 
            } 
        }  
        closedir($dir);

        // Remove temp dir
        array_map('unlink', glob($tempDir . '*.*'));
        rmdir($tempDir);
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function saveCommentFile($commentId, $postId, $file) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $commentDir = $this->saveToDir . 'post/' . $postId . '/comment/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
       $result = move_uploaded_file($file['tmp_name'], $commentDir);
        if(!$result) {
          throw new \Exception('Could not save file for comment: '. error_get_last());
       
        }
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function removePost($postId) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $permDir = $this->saveToDir . 'post/' . $postId . '/perm/';
        if(!is_dir($permDir)) {
            if(!mkdir($permDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        // Delete all files
        $paths = glob($permDir . '*'); // get all file names
        foreach($paths as $file){ // iterate files
            if(is_file($file))
            unlink($file); // delete file
        }
        rmdir($permDir);
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $tempDir = $this->saveToDir . 'post/' . $postId . '/temp/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
    
        // Remove temp dir
        array_map('unlink', glob($tempDir . '*.*'));
        rmdir($tempDir);
        
        $postDir = $this->saveToDir . 'post/' . $postId . '/';
        rmdir($postDir);
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function removeTemp($fileName, $postId) 
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
        //
        // The directory where we plan to save uploaded files.
        $temp = $this->saveToDir . 'post/' . $postId . '/temp/' . $fileName;

        unlink($temp); // delete file
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function removeAddTemp($fileName, $userId) 
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
        //
        // The directory where we plan to save uploaded files.
        $temp = $this->saveToDir . 'user/' . $userId . '/' . $fileName;

        unlink($temp); // delete file
    }
    
    /**
     * Retrieves the file information (size, MIME type) by image path.
     * @param string $filePath Path to the image file.
     * @return array File information.
     */
    public function getImageFileInfo($filePath) 
    {
        // Try to open file        
        if (!is_readable($filePath)) {            
            return false;
        }
                
        // Get file size in bytes.
        $fileSize = filesize($filePath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if($mimeType===false)
            $mimeType = 'application/octet-stream';
        
        return [
            'size' => $fileSize,
            'type' => $mimeType 
        ];
    }
    
    /**
     * Returns the image file content. On error, returns boolean false. 
     * @param string $filePath Path to image file.
     * @return string|false
     */
    public function getImageFileContent($filePath) 
    {
        return file_get_contents($filePath);
    }


    /**
     * Resizes the image, keeping its aspect ratio.
     * @param string $filePath
     * @param int $desiredWidth
     * @return string Resulting file name.
     */
    public  function resizeImage($filePath, $desiredWidth = 240) 
    {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Calculate aspect ratio
        $aspectRatio = $originalWidth/$originalHeight;
        // Calculate the resulting height
        $desiredHeight = $desiredWidth/$aspectRatio;

        // Get image info
        $fileInfo = $this->getImageFileInfo($filePath); 
        
        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0, 
                $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Save the resized image to temporary location
        $tmpFileName = tempnam("/tmp", "FOO");
        imagejpeg($resultingImage, $tmpFileName, 80);
        
        // Return the path to resulting image.
        return $tmpFileName;
    }
}



