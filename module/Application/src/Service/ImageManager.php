<?php
/**
 * A service model class encapsulating the functionality for image management.
 */
namespace Application\Service;

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
    public function getImagePathByName($fileName, $id) 
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
                
        // Return concatenated directory name and file name.
        return $this->saveToDir . 'post/' . $id . '/temp/' . $fileName;                
    }


    /**
     * Returns the array of saved file names.
     * @return array List of uploaded file names.
     */
    public function getSavedFiles() 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        if(!is_dir($this->saveToDir)) {
            if(!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
        
        // Scan the directory and create the list of uploaded files.
        $files = array();        
        $handle  = opendir($this->saveToDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            
            $files[] = $entry;
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
                copy($tempDir . $file, $permDir . $file); 
            } 
        } 
        closedir($dir); 
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



