<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180625182746 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This migration adds the description column to the post table.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        $table = $schema->getTable('post');
        $table->addColumn('description', 'text', ['notnull'=>true]);
    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('post');
        $table->dropColumn('description' );
    }
}
