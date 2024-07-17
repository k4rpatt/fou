<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717152804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE train (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, conducteur_id INTEGER DEFAULT NULL, passager1_id INTEGER DEFAULT NULL, passager2_id INTEGER DEFAULT NULL, passager3_id INTEGER DEFAULT NULL, passager4_id INTEGER DEFAULT NULL, depart DATE NOT NULL, CONSTRAINT FK_5C66E4A3F16F4AC6 FOREIGN KEY (conducteur_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C66E4A34279B306 FOREIGN KEY (passager1_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C66E4A350CC1CE8 FOREIGN KEY (passager2_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C66E4A3E8707B8D FOREIGN KEY (passager3_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C66E4A375A74334 FOREIGN KEY (passager4_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5C66E4A3F16F4AC6 ON train (conducteur_id)');
        $this->addSql('CREATE INDEX IDX_5C66E4A34279B306 ON train (passager1_id)');
        $this->addSql('CREATE INDEX IDX_5C66E4A350CC1CE8 ON train (passager2_id)');
        $this->addSql('CREATE INDEX IDX_5C66E4A3E8707B8D ON train (passager3_id)');
        $this->addSql('CREATE INDEX IDX_5C66E4A375A74334 ON train (passager4_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE train');
    }
}
