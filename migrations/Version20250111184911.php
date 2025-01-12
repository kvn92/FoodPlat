<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111184911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD statut_recette TINYINT(1) DEFAULT NULL, ADD duree INT NOT NULL, CHANGE date_publication date_publication DATETIME NOT NULL, CHANGE photo_recette photo_recette VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP statut_recette, DROP duree, CHANGE date_publication date_publication DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE photo_recette photo_recette INT DEFAULT NULL');
    }
}
