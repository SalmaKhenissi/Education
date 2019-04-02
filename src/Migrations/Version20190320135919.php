<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320135919 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE club ADD updated_at DATETIME NOT NULL, CHANGE image image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD updated_at DATETIME NOT NULL, CHANGE image image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD updated_at DATETIME NOT NULL, CHANGE photo image_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE club DROP updated_at, CHANGE image_name image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE event DROP updated_at, CHANGE image_name image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user DROP updated_at, CHANGE image_name photo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
