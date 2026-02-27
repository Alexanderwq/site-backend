<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116072114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD price_value INT NOT NULL');
        $this->addSql('ALTER TABLE account_massage_form_additional_options ADD description_value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE account_massage_form_prices ADD day_time_value VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP price_value');
        $this->addSql('ALTER TABLE account_massage_form_additional_options DROP description_value');
        $this->addSql('ALTER TABLE account_massage_form_prices DROP day_time_value');
    }
}
