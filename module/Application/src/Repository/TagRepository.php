<?php
namespace Application\Repository;
use Doctrine\ORM\EntityRepository;
use Application\Entity\Tag;
/**
 * This is the custom repository class for Tag entity.
 */
class TagRepository extends EntityRepository
{
    /**
     * Finds all published tags by user
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findAllByUser($user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('t')
            ->from(Tag::class, 't')
            ->join('t.posts', 'p')
            ->where('p.user = ?1')
            ->setParameter('1', $user);
        
        return $queryBuilder->getQuery()->getResult();
    }   
}