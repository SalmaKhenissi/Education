<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429172700 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE student_section (student_id INT NOT NULL, section_id INT NOT NULL, INDEX IDX_C045CF17CB944F1A (student_id), INDEX IDX_C045CF17D823E37A (section_id), PRIMARY KEY(student_id, section_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_section ADD CONSTRAINT FK_C045CF17CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_section ADD CONSTRAINT FK_C045CF17D823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exam ADD section_id INT DEFAULT NULL, ADD room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C654177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C6D823E37A ON exam (section_id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C654177093 ON exam (room_id)');
        $this->addSql('ALTER TABLE section CHANGE name libelle VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33D823E37A');
        $this->addSql('DROP INDEX IDX_B723AF33D823E37A ON student');
        $this->addSql('ALTER TABLE student DROP section_id');
        $this->addSql('ALTER TABLE student_exam ADD discipline VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE student_section');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6D823E37A');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C654177093');
        $this->addSql('DROP INDEX IDX_38BBA6C6D823E37A ON exam');
        $this->addSql('DROP INDEX IDX_38BBA6C654177093 ON exam');
        $this->addSql('ALTER TABLE exam DROP section_id, DROP room_id');
        $this->addSql('ALTER TABLE section CHANGE libelle name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE student ADD section_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('CREATE INDEX IDX_B723AF33D823E37A ON student (section_id)');
        $this->addSql('ALTER TABLE student_exam DROP discipline');
    }
}
