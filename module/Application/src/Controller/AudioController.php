<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is designed for managing image file uploads.
 */
class AudioController extends AbstractActionController 
{
    /**
     * Image manager.
     * @var Application\Service\ImageManager;
     */
    private $audioManager;
    
    /**
     * Constructor.
     */
    public function __construct($audioManager)
    {
        $this->audioManager = $audioManager;
    }
       
    
    /**
     * This is the 'file' action that is invoked when a user wants to 
     * open the image file in a web browser or generate a thumbnail.        
     */
    public function fileAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('name', '');
        
        $postId = $this->params()->fromQuery('id', '');
        
        $loc = $this->params()->fromQuery('loc', '');
                
        // Check whether the user needs a thumbnail or a full-size image
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);
        
        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }
        
        // Get path to image file
        $fileName = $this->audioManager->getAudioPathByName($fileName, $postId, $loc);
                
        if($isThumbnail) {        
            // Resize the image
            $fileName = $this->audioManager->resizeAudio($fileName);
        }
                
        // Get image file info (size and MIME type).
        $fileInfo = $this->audioManager->getAudioFileInfo($fileName);        
        if ($fileInfo===false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);            
            return;
        }
                
        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);        
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);
            
        // Write file content        
        $fileContent = $this->audioManager->getAudioFileContent($fileName);
        if($fileContent!==false) {                
            $response->setContent($fileContent);
        } else {        
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }
        
        if($isThumbnail) {
            // Remove temporary thumbnail image file.
            unlink($fileName);
        }
        
        // Return Response to avoid default view rendering.
        return $this->getResponse();
    }    
    
    public function removeTempAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('name', '');
        
        $postId = $this->params()->fromQuery('id', '');
        
        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }
        
        // Get path to image file
        $this->audioManager->removeTemp($fileName, $postId);
                
       // Go to the next step.
        return $this->redirect()->toRoute('posts', ['action'=>'edit',
            'id'=>$postId, 'step'=>2]);
    }    
    
    public function removeAddTempAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('name', '');
        
        $userId = $this->params()->fromQuery('id', '');
        
        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }
        
        // Get path to image file
        $this->audioManager->removeAddTemp($fileName, $userId);
                
       // Go to the next step.
        return $this->redirect()->toRoute('posts', ['action'=>'add',
            'id'=>0, 'step'=>2]);
    }    
    
    
    /**
     * This is the 'file' action that is invoked when a user wants to 
     * open the image file in a web browser or generate a thumbnail.        
     */
    public function addFileAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('name', '');
        
        $userId = $this->params()->fromQuery('id', '');
                        
        // Check whether the user needs a thumbnail or a full-size image
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);
        
        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }
        
        // Get path to image file
        $fileName = $this->audioManager->getAddAudioPathByName($fileName, $userId);
                
        if($isThumbnail) {        
            // Resize the image
            $fileName = $this->audioManager->resizeAudio($fileName);
        }
                
        // Get image file info (size and MIME type).
        $fileInfo = $this->audioManager->getAudioFileInfo($fileName);        
        if ($fileInfo===false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);            
            return;
        }
                
        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);        
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);
            
        // Write file content        
        $fileContent = $this->audioManager->getAudioFileContent($fileName);
        if($fileContent!==false) {                
            $response->setContent($fileContent);
        } else {        
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }
        
        if($isThumbnail) {
            // Remove temporary thumbnail image file.
            unlink($fileName);
        }
        
        // Return Response to avoid default view rendering.
        return $this->getResponse();
    }    
}


