<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190425102213 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialty_level (specialty_id INT NOT NULL, level_id INT NOT NULL, INDEX IDX_399CE93F9A353316 (specialty_id), INDEX IDX_399CE93F5FB14BA7 (level_id), PRIMARY KEY(specialty_id, level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specialty_level ADD CONSTRAINT FK_399CE93F9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialty_level ADD CONSTRAINT FK_399CE93F5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE specialty_level DROP FOREIGN KEY FK_399CE93F9A353316');
        $this->addSql('DROP TABLE specialty');
        $this->addSql('DROP TABLE specialty_level');
    }
}
