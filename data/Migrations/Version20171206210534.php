<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171206210534 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This migration adds the post_user_id_fk foreign key to the post table.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        $table = $schema->getTable('post');
        $table->addForeignKeyConstraint('user', ['user_id'], ['id'], [], 'post_user_id_fk');
    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('post');
        $table->removeForeignKey('post_tag_tag_id_fk');
    }
}
