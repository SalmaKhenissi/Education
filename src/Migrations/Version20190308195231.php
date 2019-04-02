<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190308195231 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD clubs_desc VARCHAR(255) NOT NULL, ADD biling_desc VARCHAR(255) NOT NULL, ADD extra_desc VARCHAR(255) NOT NULL, ADD help_desc VARCHAR(255) NOT NULL, ADD program_desc VARCHAR(255) NOT NULL, ADD diploma_desc VARCHAR(255) NOT NULL, ADD teaching_desc VARCHAR(255) NOT NULL, ADD competitiopn_desc VARCHAR(255) NOT NULL, ADD innovation_desc VARCHAR(255) NOT NULL, ADD care_desc VARCHAR(255) NOT NULL, ADD facilities_desc VARCHAR(255) NOT NULL, DROP desc1, DROP desc2, DROP desc3, DROP desc4');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD desc1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD desc2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD desc3 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD desc4 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP clubs_desc, DROP biling_desc, DROP extra_desc, DROP help_desc, DROP program_desc, DROP diploma_desc, DROP teaching_desc, DROP competitiopn_desc, DROP innovation_desc, DROP care_desc, DROP facilities_desc');
    }
}
