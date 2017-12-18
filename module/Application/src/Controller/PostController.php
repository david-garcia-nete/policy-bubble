<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Application\Form\ImagetForm;
use Application\Entity\Post;
use Application\Form\CommentForm;
/**
 * This is the Post controller class of the Blog application. 
 * This controller is used for managing posts (adding/editing/viewing/deleting).
 */
class PostController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Post manager.
     * @var Application\Service\PostManager 
     */
    private $postManager;
    
   /**
     * Image manager.
     * @var Application\Service\ImageManager;
     */
    private $imageManager;
    
    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;

    
    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $postManager, $imageManager, $sessionContainer) 
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->imageManager = $imageManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    /**
     * This action displays the "New Post" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Post entity will be created.
     */
    public function addAction() 
    {     
        // Create the form.
        $form = new PostForm();
        
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            
            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                $user = $this->currentUser();
                // Use post manager service to add new post to database.                
                $this->postManager->addNewPost($data, $user);
                
                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('posts');
            }
        }
        
        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }    
    
    /**
     * This action displays the "View Post" page allowing to see the post title
     * and content. The page also contains a form allowing
     * to add a comment to post. 
     */
    public function viewAction() 
    {       
        $postId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find the post by ID
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);
        
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        
        // Create the form.
        $form = new CommentForm();
        
        // Check whether this post is a POST request.
        if($this->getRequest()->isPost()) {
            
            // Get POST data.
            $data = $this->params()->fromPost();
            
            // Fill form with data.
            $form->setData($data);
            if($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                // Use post manager service to add new comment to post.
                $this->postManager->addCommentToPost($post, $data);
                
                // Redirect the user again to "view" page.
                return $this->redirect()->toRoute('posts', ['action'=>'view', 'id'=>$postId]);
            }
        }
        
        // Get the list of already saved files.
        $files = $this->imageManager->getSavedFiles();
        
        // Render the view template.
        return new ViewModel([
            'files'=>$files,
            'post' => $post,
            'form' => $form,
            'postManager' => $this->postManager
        ]);
    }  
    
    /**
     * This action displays the page allowing to edit a post.
     */
    public function editAction() 
    {
        // Determine the current step.
        $step = 1;
        if (isset($this->sessionContainer->step)) {
            $step = $this->sessionContainer->step;            
        }
        
        // Ensure the step is correct (between 1 and 3).
        if ($step<1 || $step>2)
            $step = 1;
        
        if ($step==1) {
            // Init user choices.
            $this->sessionContainer->userChoices = [];
        }

        // Create form.
        $form = new PostForm($step);
        
        // Create image holder.
        $files = null;
        
        // Get post ID.
        $postId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find the existing post in the database.
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);        
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
        
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            
            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                // Save user choices in session.
                $this->sessionContainer->userChoices["step$step"] = $data;

                // Increase step if photo has not been selected.
                $fileExists = $this->postManager->checkFileExists($data);                
                
                if($fileExists == false){
                    $step ++;
                    $this->sessionContainer->step = $step;
                }
                
                // If we completed all 3 steps.
                if ($step>2) {
                    // Use post manager service update existing post.
                    $this->postManager->updatePost($post, 
                            $this->sessionContainer->userChoices['step1']);
                    
                    // Redirect the user to "admin" page.
                    return $this->redirect()->toRoute('posts', ['action'=>'admin']);
                }
                
                 // Go to the next step.
                return $this->redirect()->toRoute('posts', ['action'=>'edit',
                    'id'=>$post->getId()]);

            }
        } else {
            
            if ($step==1) {
                $data = [
                    'title' => $post->getTitle(),
                    'content' => $post->getContent(),
                    'tags' => $this->postManager->convertTagsToString($post),
                    'status' => $post->getStatus()
                ];
            $form->setData($data);
            }
            
            if ($step==2) {
                // Get the list of already saved files.
                $files = $this->imageManager->getSavedFiles();
                
            }
  
        }
        
        if (!$this->access('post.own.edit', ['post'=>$post])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        // Render the view template.
        $viewModel = new ViewModel([
            'files' => $files,
            'form' => $form,
            'post' => $post
        ]);
        $viewModel->setTemplate("application/post/edit$step");
        
        return $viewModel;
    }
    
     /**
     * This action allows to upload a single image and return to the post form.
     */
    public function uploadImageAction() 
    {
        // Create the form model
        $form = new ImageForm();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Pass data to form
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Move uploaded file to its destination directory.
                $data = $form->getData();
                                
                // Redirect the user to post form.
                return $this->redirect()->toRoute('posts', ['action'=>'edit']);
            }                        
        } 
        
        // Render the page
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    /**
     * This "delete" action deletes the given post.
     */
    public function deleteAction()
    {
        $postId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);        
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }
        
        if (!$this->access('post.own.delete', ['post'=>$post])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        $this->postManager->removePost($post);
        
        // Redirect the user to "admin" page.
        return $this->redirect()->toRoute('posts', ['action'=>'admin']);        
                
    }
    
    /**
     * This "admin" action displays the Manage Posts page. This page contains
     * the list of posts with an ability to edit/delete any post.
     */
    public function adminAction()
    {
        // Get recent posts
        $user = $this->currentUser();
        $posts = $this->entityManager->getRepository(Post::class)
                ->findPostsByUser($user);
        
        // Render the view template
        return new ViewModel([
            'posts' => $posts,
            'postManager' => $this->postManager
        ]);        
    }
}