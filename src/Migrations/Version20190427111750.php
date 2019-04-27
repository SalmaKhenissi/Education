<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190427111750 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam ADD quarter_id INT DEFAULT NULL, ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6BED4A2B2 FOREIGN KEY (quarter_id) REFERENCES quarter (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C6BED4A2B2 ON exam (quarter_id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C6591CC992 ON exam (course_id)');
        $this->addSql('ALTER TABLE quarter ADD school_year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quarter ADD CONSTRAINT FK_1C81E107D2EECC3F FOREIGN KEY (school_year_id) REFERENCES school_year (id)');
        $this->addSql('CREATE INDEX IDX_1C81E107D2EECC3F ON quarter (school_year_id)');
        $this->addSql('ALTER TABLE section ADD school_year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFD2EECC3F FOREIGN KEY (school_year_id) REFERENCES school_year (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEFD2EECC3F ON section (school_year_id)');
        $this->addSql('ALTER TABLE student_exam ADD student_id INT DEFAULT NULL, ADD exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student_exam ADD CONSTRAINT FK_798DD1ECB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_exam ADD CONSTRAINT FK_798DD1E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('CREATE INDEX IDX_798DD1ECB944F1A ON student_exam (student_id)');
        $this->addSql('CREATE INDEX IDX_798DD1E578D5E91 ON student_exam (exam_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6BED4A2B2');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6591CC992');
        $this->addSql('DROP INDEX IDX_38BBA6C6BED4A2B2 ON exam');
        $this->addSql('DROP INDEX IDX_38BBA6C6591CC992 ON exam');
        $this->addSql('ALTER TABLE exam DROP quarter_id, DROP course_id');
        $this->addSql('ALTER TABLE quarter DROP FOREIGN KEY FK_1C81E107D2EECC3F');
        $this->addSql('DROP INDEX IDX_1C81E107D2EECC3F ON quarter');
        $this->addSql('ALTER TABLE quarter DROP school_year_id');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFD2EECC3F');
        $this->addSql('DROP INDEX IDX_2D737AEFD2EECC3F ON section');
        $this->addSql('ALTER TABLE section DROP school_year_id');
        $this->addSql('ALTER TABLE student_exam DROP FOREIGN KEY FK_798DD1ECB944F1A');
        $this->addSql('ALTER TABLE student_exam DROP FOREIGN KEY FK_798DD1E578D5E91');
        $this->addSql('DROP INDEX IDX_798DD1ECB944F1A ON student_exam');
        $this->addSql('DROP INDEX IDX_798DD1E578D5E91 ON student_exam');
        $this->addSql('ALTER TABLE student_exam DROP student_id, DROP exam_id');
    }
}
