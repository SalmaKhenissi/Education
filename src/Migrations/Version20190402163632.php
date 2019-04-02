<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402163632 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter DROP clubs_desc, DROP diploma_desc, DROP teaching_desc, DROP competitiopn_desc, DROP innovation_desc, DROP care_desc, DROP facilities_desc');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD clubs_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD diploma_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD teaching_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD competitiopn_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD innovation_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD care_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD facilities_desc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
