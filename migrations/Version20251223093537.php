<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223093537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ALTER metro_station_id TYPE INT');
        $this->addSql('ALTER TABLE account_metro_stations ALTER id TYPE INT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_massage_form_metro_stations ALTER metro_station_id TYPE UUID');
        $this->addSql('ALTER TABLE account_metro_stations ALTER id TYPE UUID');
    }
}
