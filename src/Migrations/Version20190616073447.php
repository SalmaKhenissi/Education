<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190616073447 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE punishment_student (punishment_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_824EAF5651E4B71 (punishment_id), INDEX IDX_824EAF56CB944F1A (student_id), PRIMARY KEY(punishment_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE punishment_student ADD CONSTRAINT FK_824EAF5651E4B71 FOREIGN KEY (punishment_id) REFERENCES punishment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punishment_student ADD CONSTRAINT FK_824EAF56CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punishment DROP FOREIGN KEY FK_7B0A4631CB944F1A');
        $this->addSql('DROP INDEX IDX_7B0A4631CB944F1A ON punishment');
        $this->addSql('ALTER TABLE punishment DROP student_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE punishment_student');
        $this->addSql('ALTER TABLE punishment ADD student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE punishment ADD CONSTRAINT FK_7B0A4631CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_7B0A4631CB944F1A ON punishment (student_id)');
    }
}
