<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207234656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(50) NOT NULL, statut_categorie TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_497DD634497DD634 (categorie), PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id_commentaire INT AUTO_INCREMENT NOT NULL, recette_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, text VARCHAR(300) NOT NULL, statut_commentaire TINYINT(1) DEFAULT NULL, date_commentaire DATETIME DEFAULT NULL, INDEX IDX_67F068BC89312FE9 (recette_id), INDEX IDX_67F068BCFB88E14F (utilisateur_id), PRIMARY KEY(id_commentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulte (id_difficulte INT AUTO_INCREMENT NOT NULL, difficulte VARCHAR(50) NOT NULL, statut_difficulte TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_AF6A33A0AF6A33A0 (difficulte), PRIMARY KEY(id_difficulte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id_etape INT AUTO_INCREMENT NOT NULL, recette_id INT DEFAULT NULL, etape LONGTEXT NOT NULL, INDEX IDX_285F75DD89312FE9 (recette_id), PRIMARY KEY(id_etape)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori (id_favori INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, recette_id INT DEFAULT NULL, INDEX IDX_EF85A2CCFB88E14F (utilisateur_id), INDEX IDX_EF85A2CC89312FE9 (recette_id), PRIMARY KEY(id_favori)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id_ingredient INT AUTO_INCREMENT NOT NULL, nom_ingredient VARCHAR(50) NOT NULL, statut_ingredient TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6BAF78706AE0F807 (nom_ingredient), PRIMARY KEY(id_ingredient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_recette (id_like_recette INT AUTO_INCREMENT NOT NULL, recette_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, boolean_like TINYINT(1) DEFAULT NULL, INDEX IDX_1A3629B389312FE9 (recette_id), INDEX IDX_1A3629B3FB88E14F (utilisateur_id), PRIMARY KEY(id_like_recette)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id_pays INT AUTO_INCREMENT NOT NULL, nom_pays VARCHAR(30) NOT NULL, statut_pays TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_349F3CAEC64FF6C0 (nom_pays), PRIMARY KEY(id_pays)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id_recette INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, difficulte_id INT DEFAULT NULL, type_repas_id INT DEFAULT NULL, pays_id INT DEFAULT NULL, viande_id INT DEFAULT NULL, ingredient_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, titre_recette VARCHAR(100) NOT NULL, date_publication DATETIME NOT NULL, nb_like INT NOT NULL, photo_recette VARCHAR(255) DEFAULT NULL, statut_recette TINYINT(1) DEFAULT NULL, duree INT NOT NULL, UNIQUE INDEX UNIQ_49BB639032A0EBB2 (titre_recette), INDEX IDX_49BB6390BCF5E72D (categorie_id), INDEX IDX_49BB6390E6357589 (difficulte_id), INDEX IDX_49BB6390D0DC4D56 (type_repas_id), INDEX IDX_49BB6390A6E44244 (pays_id), INDEX IDX_49BB63904C61F684 (viande_id), INDEX IDX_49BB6390933FE08C (ingredient_id), INDEX IDX_49BB6390FB88E14F (utilisateur_id), PRIMARY KEY(id_recette)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_repas (id_type_repas INT AUTO_INCREMENT NOT NULL, type_repas VARCHAR(50) NOT NULL, statut_type_repas TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A6DFD644A6DFD644 (type_repas), PRIMARY KEY(id_type_repas)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id_utilisateur INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, photo VARCHAR(255) DEFAULT NULL, statut_utilisateur TINYINT(1) DEFAULT NULL, date_inscription DATE NOT NULL, UNIQUE INDEX UNIQ_1D1C63B386CC499D (pseudo), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id_utilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_followers (follower_id INT NOT NULL, following_id INT NOT NULL, INDEX IDX_84E87043AC24F853 (follower_id), INDEX IDX_84E870431816E3A3 (following_id), PRIMARY KEY(follower_id, following_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE viande (id_viande INT AUTO_INCREMENT NOT NULL, nom_viande VARCHAR(50) NOT NULL, statut_viande TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_501177B85CE882C (nom_viande), PRIMARY KEY(id_viande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id_recette)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id_recette)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id_recette) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_recette ADD CONSTRAINT FK_1A3629B389312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id_recette)');
        $this->addSql('ALTER TABLE like_recette ADD CONSTRAINT FK_1A3629B3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390E6357589 FOREIGN KEY (difficulte_id) REFERENCES difficulte (id_difficulte)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390D0DC4D56 FOREIGN KEY (type_repas_id) REFERENCES type_repas (id_type_repas)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id_pays)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB63904C61F684 FOREIGN KEY (viande_id) REFERENCES viande (id_viande)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id_ingredient)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE user_followers ADD CONSTRAINT FK_84E87043AC24F853 FOREIGN KEY (follower_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE user_followers ADD CONSTRAINT FK_84E870431816E3A3 FOREIGN KEY (following_id) REFERENCES utilisateur (id_utilisateur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC89312FE9');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFB88E14F');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD89312FE9');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCFB88E14F');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC89312FE9');
        $this->addSql('ALTER TABLE like_recette DROP FOREIGN KEY FK_1A3629B389312FE9');
        $this->addSql('ALTER TABLE like_recette DROP FOREIGN KEY FK_1A3629B3FB88E14F');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390BCF5E72D');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390E6357589');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390D0DC4D56');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A6E44244');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB63904C61F684');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390933FE08C');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390FB88E14F');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user_followers DROP FOREIGN KEY FK_84E87043AC24F853');
        $this->addSql('ALTER TABLE user_followers DROP FOREIGN KEY FK_84E870431816E3A3');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE difficulte');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE like_recette');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE type_repas');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE user_followers');
        $this->addSql('DROP TABLE viande');
    }
}
