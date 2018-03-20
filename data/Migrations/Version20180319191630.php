<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180319191630 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'Create the geography table.';
        return $description;
    }
    
    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create 'user' table
        $table = $schema->createTable('geography');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);        
        $table->addColumn('post_id', 'integer', ['notnull'=>false]);
        $table->addColumn('request', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('status', 'integer', ['notnull'=>false]);
        $table->addColumn('credit', 'text', ['notnull'=>false]);
        $table->addColumn('region', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('area_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('dma_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('country_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('country_name', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('continent_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('latitude', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('longitude', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('region_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('region_name', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('currency_code', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('currency_symbol', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('currency_symbol_utf8', 'string', ['notnull'=>false, 'length'=>256]);
        $table->addColumn('currency_converter', 'string', ['notnull'=>false, 'length'=>256]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('geography');
    }
}
