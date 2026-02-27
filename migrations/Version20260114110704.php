<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114110704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_massage_form_additional_options (massage_form_id UUID NOT NULL, additional_option_id INT NOT NULL, PRIMARY KEY (massage_form_id, additional_option_id))');
        $this->addSql('CREATE INDEX IDX_204C93D3D6D22E6A ON account_massage_form_additional_options (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_204C93D3C8CEBB32 ON account_massage_form_additional_options (additional_option_id)');
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD CONSTRAINT FK_204C93D3D6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD CONSTRAINT FK_204C93D3C8CEBB32 FOREIGN KEY (additional_option_id) REFERENCES account_additional_options (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP CONSTRAINT FK_204C93D3D6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP CONSTRAINT FK_204C93D3C8CEBB32');
        $this->addSql('DROP TABLE account_massage_form_additional_options');
    }
}
