<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180226215742 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'Create the PayPal transactions table.';
        return $description;
    }
    
    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create 'user' table
        $table = $schema->createTable('transactions_paypal');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);        
        $table->addColumn('user_id', 'integer', ['notnull'=>false]);
        $table->addColumn('payment_id', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('hash', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('complete', 'integer', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('transactions_paypal');
    }
}
