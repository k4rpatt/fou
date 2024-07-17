<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716213229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serveur ADD COLUMN debut DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__serveur AS SELECT id, numero FROM serveur');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('CREATE TABLE serveur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL)');
        $this->addSql('INSERT INTO serveur (id, numero) SELECT id, numero FROM __temp__serveur');
        $this->addSql('DROP TABLE __temp__serveur');
    }
}
