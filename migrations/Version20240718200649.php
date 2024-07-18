<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718200649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur ADD COLUMN resistance DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__joueur AS SELECT id, nom, niveau, puissance, pc_equipe1, pc_equipe2, pc_equipe3, pc_equipe4 FROM joueur');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('CREATE TABLE joueur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, niveau INTEGER DEFAULT NULL, puissance DOUBLE PRECISION DEFAULT NULL, pc_equipe1 DOUBLE PRECISION DEFAULT NULL, pc_equipe2 DOUBLE PRECISION DEFAULT NULL, pc_equipe3 DOUBLE PRECISION DEFAULT NULL, pc_equipe4 DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO joueur (id, nom, niveau, puissance, pc_equipe1, pc_equipe2, pc_equipe3, pc_equipe4) SELECT id, nom, niveau, puissance, pc_equipe1, pc_equipe2, pc_equipe3, pc_equipe4 FROM __temp__joueur');
        $this->addSql('DROP TABLE __temp__joueur');
    }
}
