<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223093919 extends AbstractMigration
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
        $this->addSql('CREATE TABLE account_massage_forms (id UUID NOT NULL, user_id UUID NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, phone_value INT NOT NULL, name_value VARCHAR(255) NOT NULL, description_value VARCHAR(255) NOT NULL, experience_value INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_massage_form_metro_stations (massage_form_id UUID NOT NULL, metro_station_id INT NOT NULL, PRIMARY KEY (massage_form_id, metro_station_id))');
        $this->addSql('CREATE INDEX IDX_2FAD5C3ED6D22E6A ON account_massage_form_metro_stations (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_2FAD5C3EF7D58AAA ON account_massage_form_metro_stations (metro_station_id)');
        $this->addSql('CREATE TABLE account_metro_stations (id INT NOT NULL, name VARCHAR(255) NOT NULL, descriptor VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE account_massage_form_prices ADD CONSTRAINT FK_66B0B7BD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ADD CONSTRAINT FK_2FAD5C3ED6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ADD CONSTRAINT FK_2FAD5C3EF7D58AAA FOREIGN KEY (metro_station_id) REFERENCES account_metro_stations (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_prices DROP CONSTRAINT FK_66B0B7BD6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations DROP CONSTRAINT FK_2FAD5C3ED6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations DROP CONSTRAINT FK_2FAD5C3EF7D58AAA');
        $this->addSql('DROP TABLE account_massage_form_prices');
        $this->addSql('DROP TABLE account_massage_forms');
        $this->addSql('DROP TABLE account_massage_form_metro_stations');
        $this->addSql('DROP TABLE account_metro_stations');
    }
}
