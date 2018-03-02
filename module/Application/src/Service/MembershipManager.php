<?php

namespace Application\Service;
use Application\Entity\TransactionsPayPal;

class MembershipManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
   /**
     * This method adds a new transaction.
     * @param \Application\Entity\User $user
     * @param \PayPal\Api\Payment $payment
     */
    public function addNewTransaction($user, $payment, $hash) 
    {
        // Create new Post entity.
        $transaction = new TransactionsPayPal();
        $transaction->setUser($user);
        $transaction->setPaymentId($payment->getId());
        $transaction->setHash($hash);
        $transaction->setComplete(0);       
        
        // Add the entity to entity manager.
        $this->entityManager->persist($transaction);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }
    
    /**
     * Get membership.
     * @param \Application\Entity\User $user
     */
    public function getMembership($user) 
    {
        $transactions = $this->entityManager->getRepository(TransactionsPayPal::class)
                    ->findCompletedTransactionsByUser($user);
        
        if(count($transactions) == 0) return 'Free';
            
        $latestTransaction = $transactions[0];
        $membership = $latestTransaction->getMembershipAsString();
        
        return $membership;
    
        
    }
    
}
