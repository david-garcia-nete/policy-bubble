<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use Application\Form\SearchForm;

/**
 * This is the main controller class of the Blog application. The 
 * controller class is used to receive user input,  
 * pass the data to the models and pass the results returned by models to the 
 * view for rendering.
 */
class BlogController extends AbstractActionController 
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
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $postManager, $imageManager) 
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->imageManager = $imageManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Recent Posts page containing the recent blog posts.
     */
    public function indexAction() 
    {
        $page = $this->params()->fromQuery('page', 1);
        $tagFilter = $this->params()->fromQuery('tag', null);
        
        // Create the form.
        $form = new SearchForm();
        
        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();       
                $query = $this->postManager->findPostsByTagSearch($data['search']);
                
            }               
        } else {
        
            if ($tagFilter) {

                // Filter posts by tag
                $query = $this->entityManager->getRepository(Post::class)
                        ->findPostsByTag($tagFilter);

            } else {
                // Get recent posts
                $query = $this->entityManager->getRepository(Post::class)
                        ->findPublishedPosts();
            }
        }    
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
                       
        // Get popular tags.
        $tagCloud = $this->postManager->getTagCloud();
        
        $myTagCloud = null;
        if ($this->identity()!=null) {
            $user = $this->currentUser();
            // User is logged in.  Retrieve user identity
            $myTagCloud = $this->postManager->getMyTagCloud($user);
        }
        
        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'posts' => $paginator,
            'postManager' => $this->postManager,
            'tagCloud' => $tagCloud,
            'myTagCloud' => $myTagCloud,
            'imageManager' => $this->imageManager,
        ]);
    }
}