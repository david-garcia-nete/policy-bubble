<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180302211845 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'Update PayPal Transactions table.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        $table = $schema->getTable('transactions_paypal');
        $table->addColumn('amount', 'decimal', ['precision'=>15, 'scale'=>2, 'notnull'=>false]);

    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('transactions_paypal');
        $table->dropColumn('amount' );
        
    }
}
