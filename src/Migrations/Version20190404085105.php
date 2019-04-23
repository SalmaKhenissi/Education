<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404085105 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event ADD short_description VARCHAR(255) NOT NULL, CHANGE description long_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE club ADD short_description VARCHAR(255) NOT NULL, CHANGE description long_description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE club ADD description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP long_description, DROP short_description');
        $this->addSql('ALTER TABLE event ADD description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP long_description, DROP short_description');
    }
}
