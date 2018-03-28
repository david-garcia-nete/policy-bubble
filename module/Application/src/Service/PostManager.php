<?php
namespace Application\Service;
use Application\Entity\Post;
use Application\Entity\Comment;
use Application\Entity\Tag;
use Application\Entity\Geography;
use Zend\Filter\StaticFilter;
/**
 * The PostManager service is responsible for adding new posts, updating existing
 * posts, adding tags to post, etc.
 */
class PostManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;
    
    /**
    * Membership Manager.
    * @var Application\Service\MembershipManager
    */
    private $membershipManager;
    
    /**
    * Geography plugin.
    * @var Application\Service\GeoPlugin
    */
    private $geoPlugin;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager, $membershipManager, $geoPlugin)
    {
        $this->entityManager = $entityManager;
        $this->membershipManager = $membershipManager;
        $this->geoPlugin = $geoPlugin;
    }
    
    /**
     * This method adds a new post.
     * @param \Application\Entity\User $user
     */
    public function addNewPost($data, $user, $parentId = false) 
    {
        // Create new Post entity.
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $post->setUser($user);
        $currentDate = date('Y-m-d H:i:s');
        $post->setDateCreated($currentDate);

        if($parentId){
            $parentPost = $this->entityManager->getRepository(Post::class)
            ->findOneBy(array('id' => $parentId));
            if ($parentPost==null) {
                throw new \Exception('Parent post ' . $parentId . ' doesn\'t exist');
            }
            $parentPost->addChildPost($post);
        }        
        
        // Add the entity to entity manager.
        $this->entityManager->persist($post);
        
        // Add tags to post
        $this->addTagsToPost($data['tags'], $post);
        
        // Create new Geography entity.
        $geography = new Geography();
        $this->geoPlugin->locate();
        $geography->setPost($post);
        $geography->setRequest($this->geoPlugin->request);
        $geography->setStatus($this->geoPlugin->status);
        $geography->setCredit($this->geoPlugin->credit);
        $geography->setCity($this->geoPlugin->city);
        $geography->setRegion($this->geoPlugin->region);
        $geography->setAreaCode($this->geoPlugin->areaCode);
        $geography->setDmaCode($this->geoPlugin->dmaCode);
        $geography->setCountryCode($this->geoPlugin->countryCode);
        $geography->setCountryName($this->geoPlugin->countryName);
        $geography->setContinentCode($this->geoPlugin->continentCode);
        $geography->setLatitude($this->geoPlugin->latitude);
        $geography->setLongitude($this->geoPlugin->longitude);
        $geography->setRegionCode($this->geoPlugin->regionCode);
        $geography->setRegionName($this->geoPlugin->regionName);
        $geography->setCurrencyCode($this->geoPlugin->currencyCode);
        $geography->setCurrencySymbol($this->geoPlugin->currencySymbol);
        $geography->setCurrencySymbolUtf8($this->geoPlugin->currencySymbolUtf8);
        $geography->setCurrencyConverter($this->geoPlugin->currencyConverter);
        
        // Add the entity to entity manager.
        $this->entityManager->persist($geography);
      
        // Apply changes to database.
        $this->entityManager->flush();
    }
    
    /**
     * This method allows to update data of a single post.
     */
    public function updatePost($post, $data) 
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        
        // Add tags to post
        $this->addTagsToPost($data['tags'], $post);
        
        $geography = $this->entityManager->getRepository(Geography::class)
                    ->findOneBy(['post'=>$post]);
        $this->geoPlugin->locate();
        $geography->setPost($post);
        $geography->setRequest($this->geoPlugin->request);
        $geography->setStatus($this->geoPlugin->status);
        $geography->setCredit($this->geoPlugin->credit);
        $geography->setCity($this->geoPlugin->city);
        $geography->setRegion($this->geoPlugin->region);
        $geography->setAreaCode($this->geoPlugin->areaCode);
        $geography->setDmaCode($this->geoPlugin->dmaCode);
        $geography->setCountryCode($this->geoPlugin->countryCode);
        $geography->setCountryName($this->geoPlugin->countryName);
        $geography->setContinentCode($this->geoPlugin->continentCode);
        $geography->setLatitude($this->geoPlugin->latitude);
        $geography->setLongitude($this->geoPlugin->longitude);
        $geography->setRegionCode($this->geoPlugin->regionCode);
        $geography->setRegionName($this->geoPlugin->regionName);
        $geography->setCurrencyCode($this->geoPlugin->currencyCode);
        $geography->setCurrencySymbol($this->geoPlugin->currencySymbol);
        $geography->setCurrencySymbolUtf8($this->geoPlugin->currencySymbolUtf8);
        $geography->setCurrencyConverter($this->geoPlugin->currencyConverter);
               
        // Apply changes to database.
        $this->entityManager->flush();
    }
    /**
     * Adds/updates tags in the given post.
     */
    private function addTagsToPost($tagsStr, $post) 
    {
        // Remove tag associations (if any)
        $tags = $post->getTags();
        foreach ($tags as $tag) {            
            $post->removeTagAssociation($tag);
        }
        
        // Add tags to post
        $tags = explode(',', $tagsStr);
        foreach ($tags as $tagName) {
            
            $tagName = StaticFilter::execute($tagName, 'StringTrim');
            if (empty($tagName)) {
                continue; 
            }
            
            $tag = $this->entityManager->getRepository(Tag::class)
                    ->findOneByName($tagName);
            if ($tag == null)
                $tag = new Tag();
            
            $tag->setName($tagName);
            $tag->addPost($post);
            
            $this->entityManager->persist($tag);
            
            $post->addTag($tag);
        }
    }    
    
    /**
     * Returns status as a string.
     */
    public function getPostStatusAsString($post) 
    {
        switch ($post->getStatus()) {
            case Post::STATUS_DRAFT: return 'Draft';
            case Post::STATUS_PUBLISHED: return 'Published';
        }
        
        return 'Unknown';
    }
    
    /**
     * Converts tags of the given post to comma separated list (string).
     */
    public function convertTagsToString($post) 
    {
        $tags = $post->getTags();
        $tagCount = count($tags);
        $tagsStr = '';
        $i = 0;
        foreach ($tags as $tag) {
            $i ++;
            $tagsStr .= $tag->getName();
            if ($i < $tagCount) 
                $tagsStr .= ', ';
        }
        
        return $tagsStr;
    }    
    /**
     * Returns count of responses for given post as properly formatted string.
     */
    public function getResponseCountStr($post)
    {
        $responsePosts = $this->entityManager->getRepository(Post::class)
                            ->findChildPosts($post);
        $responseCount = count($responsePosts);
        if ($responseCount == 0)
            return 'No responses';
        else if ($responseCount == 1) 
            return '1 response';
        else
            return $responseCount . ' responses';
    }
    /**
     * This method adds a new comment to post.
     */
    public function addCommentToPost($post, $data) 
    {
        // Create new Comment entity.
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setContent($data['comment']);        
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);
        // Add the entity to entity manager.
        $this->entityManager->persist($comment);
        // Apply changes.
        $this->entityManager->flush();
    }
    
    /**
     * Removes post and all associated comments.
     */
    public function removePost($post) 
    {
        // Remove associated comments
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }
        
        // Remove tag associations (if any)
        $tags = $post->getTags();
        foreach ($tags as $tag) {
            
            $post->removeTagAssociation($tag);
        }
        
        $this->entityManager->remove($post);
        
        $geography = $this->entityManager->getRepository(Geography::class)
                    ->findOneBy(['post'=>$post]);
        $this->entityManager->remove($geography);
        
        $this->entityManager->flush();
    }
    
    /**
     * Calculates frequencies of tag usage.
     */
    public function getTagCloud()
    {
        $tagCloud = [];
                
        $posts = $this->entityManager->getRepository(Post::class)
                    ->findPostsHavingAnyTag();
        $totalPostCount = count($posts);
        
        $tags = $this->entityManager->getRepository(Tag::class)
                ->findAll();
        foreach ($tags as $tag) {
            
            $postsByTag = $this->entityManager->getRepository(Post::class)
                    ->findPostsByTag($tag->getName())->getResult();
            
            $postCount = count($postsByTag);
            if ($postCount > 0) {
                $tagCloud[$tag->getName()] = $postCount;
            }
        }
        
        $normalizedTagCloud = [];
        
        // Normalize
        foreach ($tagCloud as $name=>$postCount) {
            $normalizedTagCloud[$name] =  $postCount/$totalPostCount;
        }
        
        return $normalizedTagCloud;
    }
    
     /**
     * Calculates frequencies of user tag usage.
     */
    public function getMyTagCloud($user)
    {
        $tagCloud = [];
                
        $posts = $this->entityManager->getRepository(Post::class)
                    ->findMyPostsHavingAnyTag($user);
        $totalPostCount = count($posts);
        
        $tags = $this->entityManager->getRepository(Tag::class)
                ->findAllByUser($user);
        foreach ($tags as $tag) {
            
            $postsByTag = $this->entityManager->getRepository(Post::class)
                    ->findMyPostsByTag($tag->getName(), $user)->getResult();
            
            $postCount = count($postsByTag);
            if ($postCount > 0) {
                $tagCloud[$tag->getName()] = $postCount;
            }
        }
        
        $normalizedTagCloud = [];
        
        // Normalize
        foreach ($tagCloud as $name=>$postCount) {
            $normalizedTagCloud[$name] =  $postCount/$totalPostCount;
        }
        
        return $normalizedTagCloud;
    }
    
    /**
     * Checks if file exists.
     */
    public function checkFileExists($data) 
    {
        $fileExists = false;
        if (array_key_exists('file', $data)) {
            if ($data['file'][0]['size'] > 0) {
                $fileExists = true;
            }
        }
        
        if (array_key_exists('video', $data)) {
            if ($data['video']['size'] > 0) {
                $fileExists = true;
            }
        }
        
        if (array_key_exists('audio', $data)) {
            if ($data['audio']['size'] > 0) {
                $fileExists = true;
            }
        }
        
        return $fileExists;
    }
    
    /**
    * Check if the user's post limit has been reached for this month.
    * @param \Application\Entity\User $user
    */
    public function postLimitReached($user) 
    {
        $membershipStatus = $this->membershipManager->getMembership($user);
        switch ($membershipStatus) {
            case 'Free':
                $limit = 3;
                break;
            case 'Bronze':
                $limit = 10;
                break;
            case 'Silver':
                $limit = 25;
                break;
            case 'Gold':
                $limit = 50;
                break;
            case 'Platinum':
                $limit = 100;
                break;
        }
        // Get the user's post count for this month
        $postCount = $this->entityManager->getRepository(Post::class)
                ->findMonthPostCountByUser($user);
        if ($postCount >= $limit){
            return true;
        }
        
        return false;
    }
    
    /**
     * Find posts by tag search query.
     */
    public function findPostsBySearch($data) 
    {
        if(
                $data['tags'] == '' &&
                $data['country'] == '' &&
                $data['region'] == '' &&
                $data['city'] == ''
           ){
            $query = $this->entityManager->getRepository(Post::class)
                       ->findPublishedPosts();
            return $query;
        }
        $tags = explode(',', $data['tags']);
        $results = array();
        $inputCount = 0;
        foreach ($tags as $tag) {
            if (strlen($tag)>0){
                $inputCount++;
                $tag = trim($tag);
                $query = $this->entityManager->getRepository(Post::class)
                            ->findPostsByTag($tag);
                $posts = $query->getResult();
                $postIds = array();
                foreach($posts as $post){
                    $postIds[] = $post->getId();
                }
                if(count($postIds)>0){
                    $results[] = $postIds; 
                }
            }
        }

        if (strlen($data['country'])>0){
            $inputCount++;
        }

        $geographies = $this->entityManager->getRepository(Geography::class)
                        ->findBy(['countryName'=>$data['country']]);
        $postIds = array();
        foreach($geographies as $geography){
            $postIds[] = $geography->getPost()->getId();
        }
        if(count($postIds)>0){
                $results[] = $postIds; 
            }
        if (strlen($data['region'])>0){
            $inputCount++;
        }    

        $geographies = $this->entityManager->getRepository(Geography::class)
                        ->findBy(['region'=>$data['region']]);
        $postIds = array();
        foreach($geographies as $geography){
            $postIds[] = $geography->getPost()->getId();
        }
        if(count($postIds)>0){
                $results[] = $postIds; 
            }
        if (strlen($data['city'])>0){
            $inputCount++;
        }     
        
        $geographies = $this->entityManager->getRepository(Geography::class)
                        ->findBy(['city'=>$data['city']]);
        $postIds = array();
        foreach($geographies as $geography){
            $postIds[] = $geography->getPost()->getId();
        }
        if(count($postIds)>0){
                $results[] = $postIds; 
            }
              
        $resultCount = 0;
        $resultHolder = [];
        foreach ($results as $result){
            if (count($result)>0){
                $resultCount++;
                $resultHolder[] = $result;
            }
        }

        //An array intersect will return nothing so return the result instead.
        if (($resultCount == 1)&&($inputCount == 1)){
         
            $result = $resultHolder[0];
        } 
        
        //All the criteria have not been met.
        if (($resultCount == 1)&&($inputCount > 1)){
            
            $result = null;
        } 
        
        //Perform an array intersect to find the posts that match all search items.  
        if ($resultCount > 1){
              
            $result = call_user_func_array('array_intersect', $results);
        }

        $query = $this->entityManager->getRepository(Post::class)
                       ->findPostsByIdArray($result);  
        
        return $query;
    }
    
}
