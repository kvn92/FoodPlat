<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111183028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD difficulte_id INT DEFAULT NULL, ADD type_repas_id INT DEFAULT NULL, ADD date_publication DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD nb_like INT NOT NULL, ADD photo_recette INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390E6357589 FOREIGN KEY (difficulte_id) REFERENCES difficulte (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390D0DC4D56 FOREIGN KEY (type_repas_id) REFERENCES type_repas (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49BB639032A0EBB2 ON recette (titre_recette)');
        $this->addSql('CREATE INDEX IDX_49BB6390E6357589 ON recette (difficulte_id)');
        $this->addSql('CREATE INDEX IDX_49BB6390D0DC4D56 ON recette (type_repas_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390E6357589');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390D0DC4D56');
        $this->addSql('DROP INDEX UNIQ_49BB639032A0EBB2 ON recette');
        $this->addSql('DROP INDEX IDX_49BB6390E6357589 ON recette');
        $this->addSql('DROP INDEX IDX_49BB6390D0DC4D56 ON recette');
        $this->addSql('ALTER TABLE recette DROP difficulte_id, DROP type_repas_id, DROP date_publication, DROP nb_like, DROP photo_recette');
    }
}
