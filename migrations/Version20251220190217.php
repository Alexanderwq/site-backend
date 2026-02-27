<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251220190217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_massage_form_prices (id UUID NOT NULL, location_value VARCHAR(255) NOT NULL, duration_value INT NOT NULL, amount_value INT NOT NULL, massage_form_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_66B0B7BD6D22E6A ON account_massage_form_prices (massage_form_id)');
        $this->addSql('ALTER TABLE account_massage_form_prices ADD CONSTRAINT FK_66B0B7BD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_forms ADD date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE account_massage_forms RENAME COLUMN age_value TO experience_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_prices DROP CONSTRAINT FK_66B0B7BD6D22E6A');
        $this->addSql('DROP TABLE account_massage_form_prices');
        $this->addSql('ALTER TABLE account_massage_forms DROP date_of_birth');
        $this->addSql('ALTER TABLE account_massage_forms RENAME COLUMN experience_value TO age_value');
    }
}
