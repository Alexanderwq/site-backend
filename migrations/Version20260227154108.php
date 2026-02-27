<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227154108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_additional_options (id INT NOT NULL, name VARCHAR(255) NOT NULL, group_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_F63CB029FE54D947 ON account_additional_options (group_id)');
        $this->addSql('CREATE TABLE account_areas (id INT NOT NULL, name VARCHAR(255) NOT NULL, abbr_name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_districts (id INT NOT NULL, name VARCHAR(255) NOT NULL, descriptor VARCHAR(255) NOT NULL, area_id INT DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_4A99C087BD0F409C ON account_districts (area_id)');
        $this->addSql('CREATE TABLE account_favorite_groups (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_favorite_massage_forms (massage_form_id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, group_id UUID DEFAULT NULL, PRIMARY KEY (massage_form_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3FABA7F2FE54D947 ON account_favorite_massage_forms (group_id)');
        $this->addSql('CREATE TABLE account_group_options (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_massage_form_additional_options (price_value INT DEFAULT NULL, description_value VARCHAR(255) DEFAULT NULL, massage_form_id UUID NOT NULL, additional_option_id INT NOT NULL, PRIMARY KEY (massage_form_id, additional_option_id))');
        $this->addSql('CREATE INDEX IDX_204C93D3D6D22E6A ON account_massage_form_additional_options (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_204C93D3C8CEBB32 ON account_massage_form_additional_options (additional_option_id)');
        $this->addSql('CREATE TABLE account_massage_form_photos (id UUID NOT NULL, name VARCHAR(255) NOT NULL, is_preview BOOLEAN NOT NULL, is_main BOOLEAN NOT NULL, massage_form_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_EAD686FBD6D22E6A ON account_massage_form_photos (massage_form_id)');
        $this->addSql('CREATE TABLE account_massage_form_prices (id UUID NOT NULL, location_value VARCHAR(255) NOT NULL, duration_value INT NOT NULL, amount_value INT NOT NULL, day_time_value VARCHAR(255) NOT NULL, massage_form_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_66B0B7BD6D22E6A ON account_massage_form_prices (massage_form_id)');
        $this->addSql('CREATE TABLE account_massage_forms (id UUID NOT NULL, user_id UUID NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, disabled BOOLEAN DEFAULT false NOT NULL, phone_value VARCHAR(255) NOT NULL, name_value VARCHAR(255) NOT NULL, description_value VARCHAR(255) NOT NULL, experience_value INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_massage_form_metro_stations (massage_form_id UUID NOT NULL, metro_station_id INT NOT NULL, PRIMARY KEY (massage_form_id, metro_station_id))');
        $this->addSql('CREATE INDEX IDX_2FAD5C3ED6D22E6A ON account_massage_form_metro_stations (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_2FAD5C3EF7D58AAA ON account_massage_form_metro_stations (metro_station_id)');
        $this->addSql('CREATE TABLE account_massage_form_districts (massage_form_id UUID NOT NULL, district_id INT NOT NULL, PRIMARY KEY (massage_form_id, district_id))');
        $this->addSql('CREATE INDEX IDX_B65BE579D6D22E6A ON account_massage_form_districts (massage_form_id)');
        $this->addSql('CREATE INDEX IDX_B65BE579B08FA272 ON account_massage_form_districts (district_id)');
        $this->addSql('CREATE TABLE account_metro_stations (id INT NOT NULL, name VARCHAR(255) NOT NULL, descriptor VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE user_users (status VARCHAR(16) NOT NULL, email VARCHAR DEFAULT NULL, password_hash VARCHAR(255) DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, new_email_token VARCHAR(255) DEFAULT NULL, role VARCHAR NOT NULL, id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reset_token_token VARCHAR(255) DEFAULT NULL, reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name_value VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB186EC69F0 ON user_users (reset_token_token)');
        $this->addSql('ALTER TABLE account_additional_options ADD CONSTRAINT FK_F63CB029FE54D947 FOREIGN KEY (group_id) REFERENCES account_group_options (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_districts ADD CONSTRAINT FK_4A99C087BD0F409C FOREIGN KEY (area_id) REFERENCES account_areas (id)');
        $this->addSql('ALTER TABLE account_favorite_massage_forms ADD CONSTRAINT FK_3FABA7F2FE54D947 FOREIGN KEY (group_id) REFERENCES account_favorite_groups (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD CONSTRAINT FK_204C93D3D6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD CONSTRAINT FK_204C93D3C8CEBB32 FOREIGN KEY (additional_option_id) REFERENCES account_additional_options (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_photos ADD CONSTRAINT FK_EAD686FBD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_prices ADD CONSTRAINT FK_66B0B7BD6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ADD CONSTRAINT FK_2FAD5C3ED6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ADD CONSTRAINT FK_2FAD5C3EF7D58AAA FOREIGN KEY (metro_station_id) REFERENCES account_metro_stations (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_districts ADD CONSTRAINT FK_B65BE579D6D22E6A FOREIGN KEY (massage_form_id) REFERENCES account_massage_forms (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_massage_form_districts ADD CONSTRAINT FK_B65BE579B08FA272 FOREIGN KEY (district_id) REFERENCES account_districts (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_additional_options DROP CONSTRAINT FK_F63CB029FE54D947');
        $this->addSql('ALTER TABLE account_districts DROP CONSTRAINT FK_4A99C087BD0F409C');
        $this->addSql('ALTER TABLE account_favorite_massage_forms DROP CONSTRAINT FK_3FABA7F2FE54D947');
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP CONSTRAINT FK_204C93D3D6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP CONSTRAINT FK_204C93D3C8CEBB32');
        $this->addSql('ALTER TABLE account_massage_form_photos DROP CONSTRAINT FK_EAD686FBD6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_prices DROP CONSTRAINT FK_66B0B7BD6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations DROP CONSTRAINT FK_2FAD5C3ED6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_metro_stations DROP CONSTRAINT FK_2FAD5C3EF7D58AAA');
        $this->addSql('ALTER TABLE account_massage_form_districts DROP CONSTRAINT FK_B65BE579D6D22E6A');
        $this->addSql('ALTER TABLE account_massage_form_districts DROP CONSTRAINT FK_B65BE579B08FA272');
        $this->addSql('DROP TABLE account_additional_options');
        $this->addSql('DROP TABLE account_areas');
        $this->addSql('DROP TABLE account_districts');
        $this->addSql('DROP TABLE account_favorite_groups');
        $this->addSql('DROP TABLE account_favorite_massage_forms');
        $this->addSql('DROP TABLE account_group_options');
        $this->addSql('DROP TABLE account_massage_form_additional_options');
        $this->addSql('DROP TABLE account_massage_form_photos');
        $this->addSql('DROP TABLE account_massage_form_prices');
        $this->addSql('DROP TABLE account_massage_forms');
        $this->addSql('DROP TABLE account_massage_form_metro_stations');
        $this->addSql('DROP TABLE account_massage_form_districts');
        $this->addSql('DROP TABLE account_metro_stations');
        $this->addSql('DROP TABLE user_users');
    }
}
