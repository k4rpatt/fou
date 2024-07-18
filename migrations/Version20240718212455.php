<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718212455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance ADD COLUMN zone VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__serveur AS SELECT id, numero, debut FROM serveur');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('CREATE TABLE serveur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL, debut DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO serveur (id, numero, debut) SELECT id, numero, debut FROM __temp__serveur');
        $this->addSql('DROP TABLE __temp__serveur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__alliance AS SELECT id, serveur_id, nom, couleur FROM alliance');
        $this->addSql('DROP TABLE alliance');
        $this->addSql('CREATE TABLE alliance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, serveur_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, CONSTRAINT FK_6CBA583FB8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO alliance (id, serveur_id, nom, couleur) SELECT id, serveur_id, nom, couleur FROM __temp__alliance');
        $this->addSql('DROP TABLE __temp__alliance');
        $this->addSql('CREATE INDEX IDX_6CBA583FB8F06499 ON alliance (serveur_id)');
        $this->addSql('ALTER TABLE serveur ADD COLUMN zone VARCHAR(255) DEFAULT NULL');
    }
}
