<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122132245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE progression (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, joueur_id INTEGER NOT NULL, date_progression DATE NOT NULL, pc_tank DOUBLE PRECISION DEFAULT NULL, pc_avion DOUBLE PRECISION DEFAULT NULL, pc_missile DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_D5B25073A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D5B25073A9E2D76C ON progression (joueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE progression');
    }
}
