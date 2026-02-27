<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223163751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_areas (id INT NOT NULL, name VARCHAR(255) NOT NULL, abbr_name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE account_districts (id INT NOT NULL, name VARCHAR(255) NOT NULL, descriptor VARCHAR(255) NOT NULL, area_id INT DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_4A99C087BD0F409C ON account_districts (area_id)');
        $this->addSql('ALTER TABLE account_districts ADD CONSTRAINT FK_4A99C087BD0F409C FOREIGN KEY (area_id) REFERENCES account_areas (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_districts DROP CONSTRAINT FK_4A99C087BD0F409C');
        $this->addSql('DROP TABLE account_areas');
        $this->addSql('DROP TABLE account_districts');
    }
}
