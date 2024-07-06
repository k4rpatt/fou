<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706155133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alliance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, serveur_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, CONSTRAINT FK_6CBA583FB8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6CBA583FB8F06499 ON alliance (serveur_id)');
        $this->addSql('CREATE TABLE position (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, alliance_id INTEGER NOT NULL, serveur_id INTEGER NOT NULL, pos_x DOUBLE PRECISION NOT NULL, pos_y DOUBLE PRECISION NOT NULL, CONSTRAINT FK_462CE4F510A0EA3F FOREIGN KEY (alliance_id) REFERENCES alliance (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_462CE4F5B8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_462CE4F510A0EA3F ON position (alliance_id)');
        $this->addSql('CREATE INDEX IDX_462CE4F5B8F06499 ON position (serveur_id)');
        $this->addSql('CREATE TABLE serveur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE alliance');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
