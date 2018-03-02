<?php
namespace Application\Repository;
use Doctrine\ORM\EntityRepository;
use Application\Entity\TransactionsPayPal;
/**
 * This is the custom repository class for TransactionsPayPal entity.
 */
class TransactionsPayPalRepository extends EntityRepository
{
    
    
    /**
     * Finds all complerted transactions having the given user.
     * @param \Application\Entity\User $user
     * @return array
     */
    public function findCompletedTransactionsByUser($user)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('t')
            ->from(TransactionsPayPal::class, 't')
            ->where('t.user = ?1')
            ->andWhere('MONTH(t.dateCompleted) = ?2')
            ->orderBy('t.dateCompleted', 'DESC')    
            ->setParameter('1', $user)
            ->setParameter('2', date('m'));
     
        $transactions = $queryBuilder->getQuery()->getResult();
        
        return $transactions;
    } 
}