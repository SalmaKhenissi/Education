<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515110938 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE observation_section (observation_id INT NOT NULL, section_id INT NOT NULL, INDEX IDX_8FA1603B1409DD88 (observation_id), INDEX IDX_8FA1603BD823E37A (section_id), PRIMARY KEY(observation_id, section_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE observation_section ADD CONSTRAINT FK_8FA1603B1409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE observation_section ADD CONSTRAINT FK_8FA1603BD823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE observation ADD teacher_id INT DEFAULT NULL, DROP cours');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE041807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_C576DBE041807E1D ON observation (teacher_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE observation_section');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE041807E1D');
        $this->addSql('DROP INDEX IDX_C576DBE041807E1D ON observation');
        $this->addSql('ALTER TABLE observation ADD cours VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP teacher_id');
    }
}
