<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417150954 extends AbstractMigration
{
    
    /**
    * Returns the description of this migration.
    */
    public function getDescription()
    {
        $description = 'Add language field to user table.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->getTable('user');
        $table->addColumn('language', 'string', ['notnull'=>false, 'length'=>32]);
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $table = $schema->getTable('user');
        $table->dropColumn('language' );
    }
}
