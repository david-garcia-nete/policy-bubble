<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Application\Form\AddPostForm;
use Application\Entity\Post;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
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
     * Video manager.
     * @var Application\Service\VideoManager;
     */
    private $videoManager;
    
    /**
     * Audio manager.
     * @var Application\Service\AudioManager;
     */
    private $audioManager;
    
    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;
    
    /**
     * Translator.
     * @var Zend\I18n\Translator\Translator
     */
    private $translator;

    
    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $postManager, $imageManager, 
            $videoManager, $audioManager, $sessionContainer, $translator) 
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->imageManager = $imageManager;
        $this->videoManager = $videoManager;
        $this->audioManager = $audioManager;
        $this->sessionContainer = $sessionContainer;
        $this->translator = $translator;
    }
    
    /**
     * This action displays the "New Post" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Post entity will be created.
     */
    public function addAction() 
    {   
        $user = $this->currentUser();
        // Check the post limit and redirect accordingly.
//        if($this->postManager->postLimitReached($user)){
//            // Redirect the user to the "membership" page.
//            return $this->redirect()->toRoute('membership');
//        }
        
        $stepParam = $this->params()->fromRoute('step', 1);
        // Determine the current step.
        $step = 1;
        if ((isset($this->sessionContainer->addUserChoices['addStepCount']))&&($stepParam==2)) {
            $step = $this->sessionContainer->addUserChoices['addStepCount'];            
        }
        
        // Ensure the step is correct (between 1 and 2).
        if ($step<1 || $step>4)
            $step = 1;
        
        if ($step==1) {
            // Init user choices.
            $this->sessionContainer->addUserChoices = [];
            $this->sessionContainer->addUserChoices['addStep2Dirty'] = false;
            $this->sessionContainer->addUserChoices['addStep3Dirty'] = false;
            $this->sessionContainer->addUserChoices['addStep4Dirty'] = false;
            $this->sessionContainer->addUserChoices['addParentPostId'] = 
                    $this->params()->fromQuery('id', false);
        }
        
        // Create image holder.
        $files = null;
               
        // Create the form.
        $form = new AddPostForm($step, $user->getId(), $this->translator);
        
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
                $this->sessionContainer->addUserChoices["addStep$step"] = $data;
                
                // Increase step if photo has not been selected.
                $fileExists = $this->postManager->checkFileExists($data);      
               
                if($fileExists == false){
                    $step ++;
                    $this->sessionContainer->addUserChoices['addStepCount'] = $step;
                }
                
                if ($step>4) {
                    
                    // Use post manager service to add new post to database.
                    $data = $this->sessionContainer->addUserChoices['addStep1'];
                    $this->postManager->addNewPost(
                            $data, $user, $this->sessionContainer->addUserChoices['addParentPostId']);
                    $posts = $this->entityManager->getRepository(Post::class)
                        ->findPostsByUser($user);
                    $post = $posts[0];   
                    $postId = $post->getId();
                    $this->imageManager->saveAddTempFiles($post, $user->getId());
                    $this->videoManager->saveAddTempFiles($post, $user->getId());
                    $this->audioManager->saveAddTempFiles($post, $user->getId());
                    
                    // Redirect the user to "admin" page.
                    return $this->redirect()->toRoute('posts', ['action'=>'admin']);
                }
                
                // Go to the next step.
                return $this->redirect()->toRoute('posts', ['action'=>'add', 
                    'id'=>0, 'step'=>2]);
            }
        }   

        if ($step==2) {

            // Get the list of already saved files.
            $files = $this->imageManager->getAddTempFiles($user->getId(), 
                    $this->sessionContainer->addUserChoices['addStep2Dirty']);
            $this->sessionContainer->addUserChoices['addStep2Dirty'] = true;  
        }

        if ($step==3) {

            // Get the list of already saved files.
            $files = $this->videoManager->getAddTempFiles($user->getId(), 
                    $this->sessionContainer->addUserChoices['addStep3Dirty']);
            $this->sessionContainer->addUserChoices['addStep3Dirty'] = true;  
        } 
        
        if ($step==4) {

            // Get the list of already saved files.
            $files = $this->audioManager->getAddTempFiles($user->getId(), 
                    $this->sessionContainer->addUserChoices['addStep4Dirty']);
            $this->sessionContainer->addUserChoices['addStep4Dirty'] = true;  
        } 
  
        
        
        // Render the view template.
        $viewModel = new ViewModel([
            'files' => $files,
            'form' => $form,
            'user' => $user
        ]);
        $viewModel->setTemplate("application/post/add$step");
        
        return $viewModel;
    }    
    
    /**
     * This action displays the "View Post" page allowing to see the post title
     * and content. The page also contains a form allowing
     * to add a comment to post. 
     */
    public function viewAction() 
    {          
        $page = $this->params()->fromQuery('page', 1);
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
        
        // Only the post owner can see a draft post.
        if ($this->identity()!=null) {
            if(($post->getUser()->getId()!= $this->currentUser()->getId())&& ($post->getStatus()==1)){
                return $this->redirect()->toRoute('not-authorized'); 
            }
        } else {
            if($post->getStatus()==1){
                return $this->redirect()->toRoute('not-authorized'); 
            }
        }
        
        $query = $this->entityManager->getRepository(Post::class)
                            ->findChildPosts($post, true);

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
        
        $responseCountString = $this->postManager->getResponseCountStr($post);
                        
        // Get the list of already saved files.
        $files = $this->imageManager->getSavedFiles($post);
        // Get the list of already saved files.
        $videos = $this->videoManager->getSavedFiles($post);
        $audioFiles = $this->audioManager->getSavedFiles($post);
        
        // Render the view template.
        return new ViewModel([
            'responseCountString' => $responseCountString,
            'posts' => $paginator,
            'files'=>$files,
            'videos'=>$videos,
            'audioFiles'=>$audioFiles,
            'post' => $post,
            'postManager' => $this->postManager,
            'imageManager' => $this->imageManager
        ]);
    }  
    
    /**
     * This action displays the page allowing to edit a post.
     */
    public function editAction() 
    {
        $stepParam = $this->params()->fromRoute('step', 1);
        // Determine the current step.
        $step = 1;
        if ((isset($this->sessionContainer->userChoices["step"]))&&($stepParam==2)) {
            $step = $this->sessionContainer->userChoices["step"];            
        }
        
        // Ensure the step is correct (between 1 and 2).
        if ($step<1 || $step>4)
            $step = 1;
        
        if ($step==1) {
            // Init user choices.
            $this->sessionContainer->userChoices = [];
            $this->sessionContainer->userChoices['step2Dirty'] = false;
            $this->sessionContainer->userChoices['step3Dirty'] = false;
            $this->sessionContainer->userChoices['step4Dirty'] = false;
        }
        
        // Create image holder.
        $files = null;
        
        // Get post ID.
        $postId = (int)$this->params()->fromRoute('id', -1);
        
        // Create form.
        $form = new PostForm($step, $postId, $this->translator);
        
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
                    $this->sessionContainer->userChoices["step"] = $step;
                }else{
                    // Create the title file.
                    $this->imageManager->createTitleFile($postId, $data);
                }
                
                // If we completed both steps.
                if ($step>4) {
                    // Use post manager service update existing post.
                    $this->postManager->updatePost($post, 
                            $this->sessionContainer->userChoices['step1']);
                    $this->imageManager->saveTempFiles($post);
                    $this->videoManager->saveTempFiles($post);
                    $this->audioManager->saveTempFiles($post);
                    
                    // Redirect the user to "admin" page.
                    return $this->redirect()->toRoute('posts', ['action'=>'admin']);
                }
                
                 // Go to the next step.
                return $this->redirect()->toRoute('posts', ['action'=>'edit',
                    'id'=>$postId, 'step'=>2]);

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

        }
        
        if ($step==2) {
                
                // Get the list of already saved files.
                $files = $this->imageManager->getTempFiles($postId, 
                        $this->sessionContainer->userChoices['step2Dirty']);
                $this->sessionContainer->userChoices['step2Dirty'] = true;  
            }
            
        if ($step==3) {
                
                // Get the list of already saved files.
                $files = $this->videoManager->getTempFiles($postId, 
                        $this->sessionContainer->userChoices['step3Dirty']);
                $this->sessionContainer->userChoices['step3Dirty'] = true;  
            }    
            
        if ($step==4) {
                
                // Get the list of already saved files.
                $files = $this->audioManager->getTempFiles($postId, 
                        $this->sessionContainer->userChoices['step4Dirty']);
                $this->sessionContainer->userChoices['step4Dirty'] = true;  
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
        $this->imageManager->removePost($postId);
        $this->videoManager->removePost($postId);
        $this->audioManager->removePost($postId);
        
        // Redirect the user to "admin" page.
        return $this->redirect()->toRoute('posts', ['action'=>'admin']);        
                
    }
    
    /**
     * This "admin" action displays the Manage Posts page. This page contains
     * the list of posts with an ability to edit/delete any post.
     */
    public function adminAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        // Get recent posts
        $user = $this->currentUser();
        $query = $this->entityManager->getRepository(Post::class)
                ->findPostsByUser($user, true);
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
        
        // Render the view template
        return new ViewModel([
            'posts' => $paginator,
            'postManager' => $this->postManager,
            'imageManager' => $this->imageManager
        ]);        
    }
}