<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412201933 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE section DROP level, DROP nbr_group, DROP track, DROP school_year');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5591CC992');
        $this->addSql('DROP INDEX IDX_B0F6A6D5591CC992 ON teacher');
        $this->addSql('ALTER TABLE teacher DROP course_id');
        $this->addSql('ALTER TABLE course ADD coefficient INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course DROP coefficient');
        $this->addSql('ALTER TABLE section ADD level INT NOT NULL, ADD nbr_group INT NOT NULL, ADD track VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD school_year VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE teacher ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_B0F6A6D5591CC992 ON teacher (course_id)');
    }
}
