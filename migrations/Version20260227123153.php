<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227123153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_favorite_profiles DROP CONSTRAINT account_favorite_profiles_pkey');
        $this->addSql('ALTER TABLE account_favorite_profiles RENAME COLUMN profile_id TO massage_form_id');
        $this->addSql('ALTER TABLE account_favorite_profiles ADD PRIMARY KEY (massage_form_id, user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_favorite_profiles DROP CONSTRAINT account_favorite_profiles_pkey');
        $this->addSql('ALTER TABLE account_favorite_profiles RENAME COLUMN massage_form_id TO profile_id');
        $this->addSql('ALTER TABLE account_favorite_profiles ADD PRIMARY KEY (profile_id, user_id)');
    }
}
