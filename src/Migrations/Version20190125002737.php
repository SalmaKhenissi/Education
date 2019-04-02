<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125002737 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE course_seance');
        $this->addSql('ALTER TABLE seance ADD section_id INT DEFAULT NULL, ADD course_id INT DEFAULT NULL, ADD jour VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0ED823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0ED823E37A ON seance (section_id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0E591CC992 ON seance (course_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE course_seance (course_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_71453C43E3797A94 (seance_id), INDEX IDX_71453C43591CC992 (course_id), PRIMARY KEY(course_id, seance_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE course_seance ADD CONSTRAINT FK_71453C43591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_seance ADD CONSTRAINT FK_71453C43E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0ED823E37A');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E591CC992');
        $this->addSql('DROP INDEX IDX_DF7DFD0ED823E37A ON seance');
        $this->addSql('DROP INDEX IDX_DF7DFD0E591CC992 ON seance');
        $this->addSql('ALTER TABLE seance DROP section_id, DROP course_id, DROP jour');
    }
}
