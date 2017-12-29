<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171229213148 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'A migration which creates the `post_hierarchy` table.';
        return $description;
    }
    
    /**
     * Upgrades the schema to its newer state.
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create 'post_hierarchy' table (contains parent-child relationships between posts)
        $table = $schema->createTable('post_hierarchy');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);        
        $table->addColumn('parent_post_id', 'integer', ['notnull'=>true]);
        $table->addColumn('child_post_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('post', ['parent_post_id'], ['id'], 
                ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'post_post_parent_post_id_fk');
        $table->addForeignKeyConstraint('post', ['child_post_id'], ['id'], 
                ['onDelete'=>'CASCADE', 'onUpdate'=>'CASCADE'], 'post_post_child_post_id_fk');
        $table->addOption('engine' , 'InnoDB');
    }
    /**
     * Reverts the schema changes.
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('post_hierarchy');
    }
}
