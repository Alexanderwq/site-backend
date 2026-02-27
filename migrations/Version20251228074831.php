<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228074831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_massage_form_districts (massage_form_id UUID NOT NULL, district_id INT NOT NULL, PRIMARY KEY (massage_form_id, district_id))');
        $this->addSql('CREATE INDEX IDX_B65BE579D6D22E6A ON account_massage_form_districts (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_B65BE579B08FA272 ON account_massage_form_districts (district_id)');
        $this->addSql('ALTER TABLE account_massage_form_districts ADD CONSTRAINT FK_B65BE579D6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_districts ADD CONSTRAINT FK_B65BE579B08FA272 FOREIGN KEY (district_id) REFERENCES account_districts (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_districts DROP CONSTRAINT FK_B65BE579D6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_districts DROP CONSTRAINT FK_B65BE579B08FA272');
        $this->addSql('DROP TABLE account_massage_form_districts');
    }
}
