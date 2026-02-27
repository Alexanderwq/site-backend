<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227123403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_favorite_massage_forms (massage_form_id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, group_id UUID DEFAULT NULL, PRIMARY KEY (massage_form_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3FABA7F2FE54D947 ON account_favorite_massage_forms (group_id)');
        $this->addSql('ALTER TABLE account_favorite_massage_forms ADD CONSTRAINT FK_3FABA7F2FE54D947 FOREIGN KEY (group_id) REFERENCES account_favorite_groups (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE account_favorite_profiles DROP CONSTRAINT fk_a0bf6db0fe54d947');
        $this->addSql('DROP TABLE account_favorite_profiles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_favorite_profiles (massage_form_id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, group_id UUID DEFAULT NULL, PRIMARY KEY (massage_form_id, user_id))');
        $this->addSql('CREATE INDEX idx_a0bf6db0fe54d947 ON account_favorite_profiles (group_id)');
        $this->addSql('ALTER TABLE account_favorite_profiles ADD CONSTRAINT fk_a0bf6db0fe54d947 FOREIGN KEY (group_id) REFERENCES account_favorite_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_favorite_massage_forms DROP CONSTRAINT FK_3FABA7F2FE54D947');
        $this->addSql('DROP TABLE account_favorite_massage_forms');
    }
}
