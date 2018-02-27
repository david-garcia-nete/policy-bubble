<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180226213917 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'Add membership column to user table.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        $table = $schema->getTable('user');
        $table->addColumn('membership', 'integer', ['notnull'=>false]);

    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('user');
        $table->dropColumn('membership' );
    }
}
