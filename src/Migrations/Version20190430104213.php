<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430104213 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD biling_name VARCHAR(255) NOT NULL, ADD extra_name VARCHAR(255) NOT NULL, ADD help_name VARCHAR(255) NOT NULL, ADD program_name VARCHAR(255) NOT NULL, ADD comp_desc VARCHAR(255) NOT NULL, ADD innov_desc VARCHAR(255) NOT NULL, ADD educ_desc VARCHAR(255) NOT NULL, ADD diploma_desc VARCHAR(255) NOT NULL, ADD faci_desc VARCHAR(255) NOT NULL, ADD health_desc VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL, DROP biling_desc, DROP extra_desc, DROP help_desc, DROP program_desc');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD biling_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD extra_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD help_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD program_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP biling_name, DROP extra_name, DROP help_name, DROP program_name, DROP comp_desc, DROP innov_desc, DROP educ_desc, DROP diploma_desc, DROP faci_desc, DROP health_desc, DROP updated_at');
    }
}
