<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430172558 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parameter DROP biling_name, DROP extra_name, DROP help_name, DROP program_name, DROP comp_desc, DROP innov_desc, DROP educ_desc, DROP diploma_desc, DROP faci_desc, DROP health_desc');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE parameter ADD biling_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD extra_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD help_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD program_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD comp_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD innov_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD educ_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD diploma_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD faci_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD health_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
