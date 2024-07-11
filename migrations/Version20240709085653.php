<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709085653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position ADD COLUMN moment DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__position AS SELECT id, alliance_id, serveur_id, pos_x, pos_y, cible FROM position');
        $this->addSql('DROP TABLE position');
        $this->addSql('CREATE TABLE position (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, alliance_id INTEGER NOT NULL, serveur_id INTEGER NOT NULL, pos_x DOUBLE PRECISION NOT NULL, pos_y DOUBLE PRECISION NOT NULL, cible VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_462CE4F510A0EA3F FOREIGN KEY (alliance_id) REFERENCES alliance (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_462CE4F5B8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO position (id, alliance_id, serveur_id, pos_x, pos_y, cible) SELECT id, alliance_id, serveur_id, pos_x, pos_y, cible FROM __temp__position');
        $this->addSql('DROP TABLE __temp__position');
        $this->addSql('CREATE INDEX IDX_462CE4F510A0EA3F ON position (alliance_id)');
        $this->addSql('CREATE INDEX IDX_462CE4F5B8F06499 ON position (serveur_id)');
    }
}
