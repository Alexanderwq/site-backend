<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112140954 extends AbstractMigration
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
        $this->addSql('CREATE TABLE account_group_options (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE account_additional_options ADD CONSTRAINT FK_F63CB029FE54D947 FOREIGN KEY (group_id) REFERENCES account_group_options (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_additional_options DROP CONSTRAINT FK_F63CB029FE54D947');
        $this->addSql('DROP TABLE account_additional_options');
        $this->addSql('DROP TABLE account_group_options');
    }
}
