<?php
namespace Application\Repository;
use Doctrine\ORM\EntityRepository;
use Application\Entity\Post;
/**
 * This is the custom repository class for Post entity.
 */
class PostRepository extends EntityRepository
{
    /**
     * Retrieves all published posts in descending date order.
     * @return Query
     */
    public function findPublishedPosts()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        return $queryBuilder->getQuery();
    }
    
    /**
     * Finds all published posts having any tag.
     * @return array
     */
    public function findPostsHavingAnyTag()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        $posts = $queryBuilder->getQuery()->getResult();
        
        return $posts;
    }
    
    /**
     * Finds all of the user's published posts having any tag.
     * @return array
     */
    public function findMyPostsHavingAnyTag($user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('p.user = ?2')    
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED)
            ->setParameter('2', $user);
        
        $posts = $queryBuilder->getQuery()->getResult();
        
        return $posts;
    }
    
    /**
     * Finds all published posts having the given tag.
     * @param string $tagName Name of the tag.
     * @return Query
     */
    public function findPostsByTag($tagName)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('t.name = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED)
            ->setParameter('2', $tagName);
        
        return $queryBuilder->getQuery();
    }   
    
    /**
     * Finds all published posts having the given tag.
     * @param string $tagName Name of the tag.
     * @return Query
     */
    public function findMyPostsByTag($tagName, $user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('t.name = ?2')
            ->andWhere('p.user = ?3')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED)
            ->setParameter('2', $tagName)
            ->setParameter('3', $user);
        
        return $queryBuilder->getQuery();
    }   
    
    /**
     * Finds all posts having the given user.
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findPostsByUser($user, $query=false)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('p.user = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', $user);
        
        if($query){
            return $queryBuilder->getQuery();
        }
        
        $posts = $queryBuilder->getQuery()->getResult();
        
        return $posts;
    } 
    
    /**
     * Finds all published posts having the given user.
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findPublishedPostsByUser($user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('p.user = ?1')
            ->andWhere('p.status = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', $user)
            ->setParameter('2', Post::STATUS_PUBLISHED);

        return $queryBuilder->getQuery();
    } 
    
    /**
     * Finds all published posts having the given parent post id.
     * @return array
     */
    public function findChildPosts($parentPost, $query=false)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('?1 MEMBER OF p.parentPosts')
            ->andWhere('p.status = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', $parentPost)
            ->setParameter('2', Post::STATUS_PUBLISHED);
        
        if($query){
            return $queryBuilder->getQuery();
        }
        
        $posts = $queryBuilder->getQuery()->getResult();
        
        return $posts;
    } 
    
    /**
     * Finds all published posts having the given user.
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findMonthPostCountByUser($user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('COUNT(p)')
            ->from(Post::class, 'p')
            ->where('p.user = ?1')
            ->andWhere('MONTH(p.dateCreated) = ?2')
            ->setParameter('1', $user)
            ->setParameter('2', date('m'));
     
        $count = $queryBuilder->getQuery()->getResult();
        
        return $count[0][1];
    } 
    
     /**
     * Finds all published posts having the given user.
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findPostsByIdArray($array)
    {

        $postIds = implode(',', $array);   
        
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        if ($postIds==null){
            $queryBuilder->select('p')->distinct()
                ->from(Post::class, 'p')
                ->where('p.id = ?1')
                ->orderBy('p.dateCreated', 'DESC')
                ->setParameter('1', -1);
        }
        else{
            $queryBuilder->select('p')->distinct()
                ->from(Post::class, 'p')
                ->where('p.status = ?1')
                ->andWhere("p.id in ($postIds)")
                ->orderBy('p.dateCreated', 'DESC')
                ->setParameter('1', Post::STATUS_PUBLISHED);
        }
     
        $query = $queryBuilder->getQuery();
        
        return $query;
    } 
    
    /**
     * Finds all distinct published tags.
     * @return array
     */
    
     public function findDistinctPublishedTags()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
               
        $queryBuilder->select('t.name')->distinct()
            ->from(Post::class, 'p')
            ->join('p.tags', 't')    
            ->where('p.status = ?1')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * Finds all distinct published child posts.
     * @return string
     */
    
     public function findPublishedChildPostsCount()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
               
        $queryBuilder->select('count(cp)')
            ->from(Post::class, 'p')
            ->join('p.childPosts', 'cp')    
            ->where('cp.status = ?1')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        $count = $queryBuilder->getQuery()->getResult();
        
        return $count[0][1];
    }
    
    /**
     * Finds all distinct published geography records based on input field.
     * @param string
     * @return array
     */
    
    public function findDistinctPublishedGeography($field)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
               
        $queryBuilder->select("g.$field")->distinct()
            ->from(Post::class, 'p')
            ->join('p.geography', 'g')    
            ->where('p.status = ?1')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        return $queryBuilder->getQuery()->getResult();
  
    }
    
}