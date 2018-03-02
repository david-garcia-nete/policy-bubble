<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180302181410 extends AbstractMigration
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
        $table->addColumn('membership', 'integer', ['notnull'=>false]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>false]);
        $table->addColumn('date_completed', 'datetime', ['notnull'=>false]);

    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('transactions_paypal');
        $table->dropColumn('membership' );
        $table->dropColumn('date_created' );
        $table->dropColumn('date_completed' );
    }
}
