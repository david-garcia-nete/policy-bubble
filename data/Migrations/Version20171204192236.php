<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171204192236 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'Allows email confirmation before registration is complete.';
        return $description;
    }
    
    public function up(Schema $schema)
    {
        $table = $schema->getTable('user');
        $table->addColumn('reg_conf_token', 'string', ['notnull'=>false, 'length'=>32]);
        $table->addColumn('reg_conf_token_creation_date', 'datetime', ['notnull'=>false]);

    }

    public function down(Schema $schema)
    {
        $table = $schema->getTable('user');
        $table->dropColumn('reg_conf_token' );
        $table->dropColumn('reg_conf_token_creation_date' );
    }
}
