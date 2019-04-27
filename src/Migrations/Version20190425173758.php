<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190425173758 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE section ADD specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF9A353316 ON section (specialty_id)');
        $this->addSql('ALTER TABLE course ADD specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB99A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB99A353316 ON course (specialty_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB99A353316');
        $this->addSql('DROP INDEX IDX_169E6FB99A353316 ON course');
        $this->addSql('ALTER TABLE course DROP specialty_id');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF9A353316');
        $this->addSql('DROP INDEX IDX_2D737AEF9A353316 ON section');
        $this->addSql('ALTER TABLE section DROP specialty_id');
    }
}
