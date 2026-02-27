<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222114941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_massage_form_metro_stations (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE massage_form_metro_station (massage_form_id UUID NOT NULL, metro_station_id UUID NOT NULL, PRIMARY KEY (massage_form_id, metro_station_id))');
        $this->addSql('CREATE INDEX IDX_983980EAD6D22E6A ON massage_form_metro_station (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_983980EAF7D58AAA ON massage_form_metro_station (metro_station_id)');
        $this->addSql('ALTER TABLE massage_form_metro_station ADD CONSTRAINT FK_983980EAD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE massage_form_metro_station ADD CONSTRAINT FK_983980EAF7D58AAA FOREIGN KEY (metro_station_id) REFERENCES account_massage_form_metro_stations (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE massage_form_metro_station DROP CONSTRAINT FK_983980EAD6D22E6A');
        $this->addSql('ALTER TABLE massage_form_metro_station DROP CONSTRAINT FK_983980EAF7D58AAA');
        $this->addSql('DROP TABLE account_massage_form_metro_stations');
        $this->addSql('DROP TABLE massage_form_metro_station');
    }
}
