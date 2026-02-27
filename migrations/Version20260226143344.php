<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226143344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_favorite_groups (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_favorite_profiles (profile_id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, group_id UUID DEFAULT NULL, PRIMARY KEY (profile_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A0BF6DB0FE54D947 ON account_favorite_profiles (group_id)');
        $this->addSql('ALTER TABLE account_favorite_profiles ADD CONSTRAINT FK_A0BF6DB0FE54D947 FOREIGN KEY (group_id) REFERENCES account_favorite_groups (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE favorite_favorite_profiles DROP CONSTRAINT fk_51418537fe54d947');
        $this->addSql('DROP TABLE favorite_favorite_groups');
        $this->addSql('DROP TABLE favorite_favorite_profiles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_favorite_groups (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE favorite_favorite_profiles (profile_id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, group_id UUID DEFAULT NULL, PRIMARY KEY (profile_id, user_id))');
        $this->addSql('CREATE INDEX idx_51418537fe54d947 ON favorite_favorite_profiles (group_id)');
        $this->addSql('ALTER TABLE favorite_favorite_profiles ADD CONSTRAINT fk_51418537fe54d947 FOREIGN KEY (group_id) REFERENCES favorite_favorite_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_favorite_profiles DROP CONSTRAINT FK_A0BF6DB0FE54D947');
        $this->addSql('DROP TABLE account_favorite_groups');
        $this->addSql('DROP TABLE account_favorite_profiles');
    }
}
