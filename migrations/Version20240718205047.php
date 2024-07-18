<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718205047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serveur ADD COLUMN zone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__serveur AS SELECT id, numero, debut FROM serveur');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('CREATE TABLE serveur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL, debut DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO serveur (id, numero, debut) SELECT id, numero, debut FROM __temp__serveur');
        $this->addSql('DROP TABLE __temp__serveur');
    }
}