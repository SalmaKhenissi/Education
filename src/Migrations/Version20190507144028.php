<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507144028 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student_exam DROP FOREIGN KEY FK_798DD1E578D5E91');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE student_exam');
        $this->addSql('ALTER TABLE document CHANGE description description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, quarter_id INT DEFAULT NULL, course_id INT DEFAULT NULL, section_id INT DEFAULT NULL, room_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, pass_at DATETIME NOT NULL, start_at DATETIME NOT NULL, finish_at DATETIME NOT NULL, INDEX IDX_38BBA6C6D823E37A (section_id), INDEX IDX_38BBA6C6BED4A2B2 (quarter_id), INDEX IDX_38BBA6C654177093 (room_id), INDEX IDX_38BBA6C6591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE student_exam (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, exam_id INT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, discipline VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_798DD1ECB944F1A (student_id), INDEX IDX_798DD1E578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C654177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6BED4A2B2 FOREIGN KEY (quarter_id) REFERENCES quarter (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE student_exam ADD CONSTRAINT FK_798DD1E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE student_exam ADD CONSTRAINT FK_798DD1ECB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE document CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
