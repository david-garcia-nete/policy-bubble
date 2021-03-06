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
    public function addNewTransaction($user, $payment, $hash, $membership, $amount) 
    {
        // Create new Post entity.
        $transaction = new TransactionsPayPal();
        $transaction->setUser($user);
        $transaction->setPaymentId($payment->getId());
        $transaction->setHash($hash);
        $transaction->setComplete(0);
        $transaction->setMembership($membership);
        $transaction->setAmount(floatval($amount));
        $currentDate = date('Y-m-d H:i:s');
        $transaction->setDateCreated($currentDate);
        
        
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

// This goes in the home page but I moved it here so no one can see it.
//<!--    <div class="col-md-4">
//        <div class="panel panel-default">
//            <div class="panel-heading">
//                <h3 class="panel-title">Membership</h3>
//            </div>
//            <div class="panel-body">
//                <p>
//                    Update your posting limit.
//                </p>
//
//                <p>
//                    <a class="btn btn-info pull-right" 
//                       href="<php echo $this->url('membership'); >">Update &raquo;
//                    </a>
//                </p>
//            </div>
//        </div>
//    </div>-->
