<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122204321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_massage_form_photos (id UUID NOT NULL, name VARCHAR(255) NOT NULL, massage_form_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_EAD686FBD6D22E6A ON account_massage_form_photos (massage_form_id)');
        $this->addSql('ALTER TABLE account_massage_form_photos ADD CONSTRAINT FK_EAD686FBD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_photos DROP CONSTRAINT FK_EAD686FBD6D22E6A');
        $this->addSql('DROP TABLE account_massage_form_photos');
    }
}
