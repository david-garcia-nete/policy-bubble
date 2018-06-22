<?php
/**
 * A service model class encapsulating the functionality for image management.
 */
namespace Application\Service;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Zend\Config\Config;
use Application\Entity\Post;

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
    private $saveToDir = './data/image/';
    
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
        $this->s3bucket = $config->s3->s3imageBucket;
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
    public function getImagePathByName($fileName, $id)
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes
                
        // Return concatenated directory name and file name.
        return $this->saveToDir . 'post/' . $id . "/" . $fileName;                
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
    public function getSavedFiles($post) 
    {
        $objects = $this->s3client->getIterator('ListObjects', [
            'Bucket' =>  $this->s3bucket,
            'Prefix' =>  $post->getId()
        ]);
        
        $files = array();
            
        // If draft, get tokenized URLs. 
        if($post->getStatus() == Post::STATUS_DRAFT){

            foreach ($objects as $object){

                $cmd = $this->s3client->getCommand('GetObject', [
                    'Bucket' => $this->s3bucket,
                    'Key'    => $object['Key']
                ]);

                $request = $this->s3client->createPresignedRequest($cmd, '+1 hour');

                // Get the actual presigned-url
                $files[$object['Tagging']] = (string) $request->getUri();

            }

        } else {
            
            foreach ($objects as $object){
                $files[$object['Tagging']] = $this->s3client->getObjectUrl($this->s3bucket, $object['Key']);
            }
            
        }    
               
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Returns the array of saved file names.
     * @return array List of uploaded file names.
     */
    public function getFirstSavedFiles($post, $count = 2) 
    {
        $objects = $this->s3client->getIterator('ListObjects', [
            'Bucket' =>  $this->s3bucket,
            'Prefix' =>  $post->getId()
        ]);
        
        $files = array();
        
        // If draft, get tokenized URLs. 
        if($post->getStatus() == Post::STATUS_DRAFT){
        
            $i=0;
            foreach ($objects as $object){
                $cmd = $this->s3client->getCommand('GetObject', [
                    'Bucket' => $this->s3bucket,
                    'Key'    => $object['Key']
                ]);

                $request = $this->s3client->createPresignedRequest($cmd, '+1 hour');

                // Get the actual presigned-url
                $files[$object['Tagging']] = (string) $request->getUri();
                $i++;
                if ($i>=$count){
                    return $files;
                }
            }
            
        } else {
            
            $i=0;
            foreach ($objects as $object){
                $files[$object['Tagging']] = $this->s3client->getObjectUrl($this->s3bucket, $object['Key']);
                $i++;
                if ($i>=$count){
                    return $files;
                }
            }
            
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
        $tempDir = $this->saveToDir . 'post/' . $id . '/titles/';
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
            
            // Copy all files
            $objects = $this->s3client->getIterator('ListObjects', [
                'Bucket' =>  $this->s3bucket,
                'Prefix' =>  $id
            ]);

            foreach ($objects as $object){
                $parts = explode('/', $object['Key']);
                $count = count($parts);
                if ($count == 2){
                    $tempDir = $this->saveToDir . 'post/' .  $id . '/';
                    $this->s3client->getObject([
                        'Bucket' => $this->s3bucket,
                        'Key' => $object['Key'],
                        'SaveAs' => $tempDir . $parts[$count-1]
                    ]);
                }else{
                    $tempDir = $this->saveToDir . 'post/' . $id . '/titles/';
                    $this->s3client->getObject([
                        'Bucket' => $this->s3bucket,
                        'Key' => $object['Key'],
                        'SaveAs' => $tempDir . $parts[$count-1]
                    ]);
                }    
            }
            
        }
        
        // Scan the directory and create the list of uploaded files.
        $tempDir = $this->saveToDir . 'post/' .  $id . '/';
        $files = array();        
        $handle  = opendir($tempDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            $name = explode('.', $entry);
            $name = $name[0];
            if(!is_dir($tempDir . '/' . $entry))
            $files[$name] = $entry;
        }
        closedir($handle);
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Returns the array of temp file titles.
     * @return array List of uploaded file titles.
     */
    public function getTempFileTitles($id, $dirty) 
    {
        // The directory where we plan to save uploaded files.
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $tempDir = $this->saveToDir . 'post/' .  $id . '/titles/';
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
            
            $files = array();
            
            // Copy all files
            $objects = $this->s3client->getIterator('ListObjects', [
                'Bucket' =>  $this->s3bucket,
                'Prefix' =>  $id . '/titles'
            ]);

            foreach ($objects as $object){
                $parts = explode('/', $object['Key']);
                $count = count($parts);
                $this->s3client->getObject([
                    'Bucket' => $this->s3bucket,
                    'Key' => $object['Key'],
                    'SaveAs' => $tempDir . $parts[$count-1]
                ]);
            }
            
        }
        
        // Scan the directory and create the list of uploaded files.
        $fileTitles = array(); 
        $handle  = opendir($tempDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            $name = explode('.', $entry);
            $name = $name[0];
            $fileHandle = fopen($tempDir . $entry, 'r');
            $data = fread($fileHandle,filesize($tempDir . $entry));
            fclose($fileHandle);
            $fileTitles[$name] = $data;
        }
        closedir($handle);
        
        // Return the list of uploaded files.
        return $fileTitles;
    }
    
    /**
     * Creates the temp image title file.
     */
    public function createTitleFile($id, $data) 
    {
        if (array_key_exists('file', $data)) {
            if ($data['file']['size'] > 0) {
                // The directory where we plan to save uploaded files.
                // Check whether the directory already exists, and if not,
                // create the directory.
                $tempDir = $this->saveToDir . 'post/' .  $id . '/titles/';
                if(!is_dir($tempDir)) {
                    if(!mkdir($tempDir, 0755, true)) {
                        throw new \Exception('Could not create directory for uploads: '. error_get_last());
                    }
                }
                
                $name = $data['file']['name'];
                $name = explode('.', $name);
                $name = $name[0] . '.txt';      
                $file = $tempDir . $name;
                $handle = fopen($file, 'w') or die('Cannot open file:  '.$file);
                $data = $data['file_title'];
                fwrite($handle, $data);
                fclose($handle);
            }
        }
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function saveTempFiles($post) 
    {
        // Delete all files
        $objects = $this->s3client->getIterator('ListObjects', [
            'Bucket' =>  $this->s3bucket,
            'Prefix' =>  $post->getId()
        ]);
        
        foreach ($objects as $object){
            $this->s3client->deleteObject(['Bucket' => $this->s3bucket, 'Key' => $object['Key']]);
        }
        
        // Copy all title files
        $tempDir = $this->saveToDir . 'post/' . $post->getId() . '/titles/';
        
        // Scan the directory and create the list of uploaded files.
        $fileTitles = array();        
        $handle  = opendir($tempDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            $name = explode('.', $entry);
            $name = $name[0];
            $fileHandle = fopen($tempDir . $entry, 'r');
            $data = fread($fileHandle,filesize($tempDir . $entry));
            fclose($fileHandle);
            $filesTitles[$name] = $data;
        }
        closedir($handle);
        
        $dir = opendir($tempDir);  
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) {      
                //copy($tempDir . $file, $permDir . $file);
                $fileHandle = fopen($tempDir . $file, 'rb');
                try{
                    $this->s3client->putObject([
                        'Bucket' => $this->s3bucket,
                        'Key' => $post->getId() . '/titles/' . $file,
                        'Body' => $fileHandle,
                        'ACL' => $post->getStatus() == Post::STATUS_DRAFT ? 'private' : 'public-read'
                    ]);                    
                } catch(S3Exception $e){
                    die ("There was an error uploading that file.");
                }
                fclose($fileHandle);
            } 
        }  
        closedir($dir);

        // Remove temp dir
        //array_map('unlink', glob($tempDir . '*.*'));
        //rmdir($tempDir);
        
        // Copy all files
        $tempDir = $this->saveToDir . 'post/' . $post->getId() . '/';
        $dir = opendir($tempDir);  
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) {      
                //copy($tempDir . $file, $permDir . $file);
                $name = explode('.', $file);
                $name = $name[0];
                if(!is_dir($tempDir . $file)){
                    $fileHandle = fopen($tempDir . $file, 'rb');
                    try{
                        $this->s3client->putObject([
                            'Bucket' => $this->s3bucket,
                            'Key' => $post->getId() . '/' . $file,
                            'Body' => $fileHandle,
                            'ACL' => $post->getStatus() == Post::STATUS_DRAFT ? 'private' : 'public-read',
                            'Metadata' => [     
                                'title' => $fileTitles[$name]
                            ]
                        ]);                    
                    } catch(S3Exception $e){
                        die ("There was an error uploading that file.");
                    }
                    fclose($fileHandle);
                }    
            } 
        }  
        closedir($dir);

        $tempDir = $this->saveToDir . 'post/' . $post->getId() . '/titles/';
        array_map('unlink', glob($tempDir . '*.*'));
        rmdir($tempDir);
        $tempDir = $this->saveToDir . 'post/' . $post->getId() . '/';
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
        closedir($handle);
        
        // Return the list of uploaded files.
        return $files;
    }
    
    /**
     * Saves the temp file to the permanent folder
     */
    public function saveAddTempFiles($post, $userId) 
    {
        
        // Delete all files
        $objects = $this->s3client->getIterator('ListObjects', [
            'Bucket' =>  $this->s3bucket,
            'Prefix' =>  $post->getId()
        ]);
        
        foreach ($objects as $object){
            $this->s3client->deleteObject(['Bucket' => $this->s3bucket, 'Key' => $object['Key']]);
        }
        
        // Copy all files
        $tempDir = $this->saveToDir . 'user/' . $userId . '/';
        $dir = opendir($tempDir);  
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) {      
                //copy($tempDir . $file, $permDir . $file);
                try{
                    $this->s3client->putObject([
                        'Bucket' => $this->s3bucket,
                        'Key' => $post->getId() . '/' . $file,
                        'Body' => fopen($tempDir . $file, 'rb'),
                        'ACL' => $post->getStatus() == Post::STATUS_DRAFT ? 'private' : 'public-read'
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
        
        // Delete all perm files
        $objects = $this->s3client->getIterator('ListObjects', [
            'Bucket' =>  $this->s3bucket,
            'Prefix' =>  $postId
        ]);
        
        foreach ($objects as $object){
            $this->s3client->deleteObject(['Bucket' => $this->s3bucket, 'Key' => $object['Key']]);
        }
        
        // Check whether the directory already exists, and if not,
        // create the directory.
        $tempDir = $this->saveToDir . 'post/' . $postId . '/';
        if(!is_dir($tempDir)) {
            if(!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
    
        // Remove temp dir
        array_map('unlink', glob($tempDir . '*.*'));
        rmdir($tempDir);
      
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
        $temp = $this->saveToDir . 'post/' . $postId . '/' . $fileName;

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



