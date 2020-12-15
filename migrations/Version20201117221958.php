<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201117221958 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bagage (id INT AUTO_INCREMENT NOT NULL, passager_id INT DEFAULT NULL, escale_depart_id INT DEFAULT NULL, escale_arrive_id INT DEFAULT NULL, agent_chargement_contenaire_id INT DEFAULT NULL, agent_chargement_soute_id INT DEFAULT NULL, agent_dechargement_soute_id INT DEFAULT NULL, agent_dechargement_contenaire_id INT DEFAULT NULL, agent_livraison_id INT DEFAULT NULL, vol_id INT DEFAULT NULL, agent_upload_manisfet_id INT DEFAULT NULL, escale_transit JSON DEFAULT NULL, contenu_bagage LONGTEXT DEFAULT NULL, est_uploder TINYINT(1) DEFAULT NULL, date_upload DATETIME DEFAULT NULL, date_chargement_contenaire DATETIME DEFAULT NULL, date_chargement_soute DATETIME DEFAULT NULL, date_dechargement_soute DATETIME DEFAULT NULL, date_dechargement_contaire DATETIME DEFAULT NULL, date_livraison DATETIME DEFAULT NULL, code_barre VARCHAR(255) NOT NULL, poids VARCHAR(255) DEFAULT NULL, est_inconnu TINYINT(1) DEFAULT NULL, est_en_detresse TINYINT(1) DEFAULT NULL, origine_detresse VARCHAR(255) DEFAULT NULL, manifest_charge TINYINT(1) DEFAULT NULL, INDEX IDX_A82C571571A51189 (passager_id), INDEX IDX_A82C571597942EEB (escale_depart_id), INDEX IDX_A82C5715CD9456E8 (escale_arrive_id), INDEX IDX_A82C571540082392 (agent_chargement_contenaire_id), INDEX IDX_A82C57155986E90A (agent_chargement_soute_id), INDEX IDX_A82C5715AB267538 (agent_dechargement_soute_id), INDEX IDX_A82C5715938D4837 (agent_dechargement_contenaire_id), INDEX IDX_A82C5715F81874EE (agent_livraison_id), INDEX IDX_A82C57159F2BFB7A (vol_id), INDEX IDX_A82C57152F7AADEB (agent_upload_manisfet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connexion (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, date_connexion DATETIME DEFAULT NULL, date_deconnexion DATETIME DEFAULT NULL, INDEX IDX_936BF99CFB88E14F (utilisateur_id), INDEX IDX_936BF99C9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE escale (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, aeroport VARCHAR(255) NOT NULL, indice_aeroport VARCHAR(30) DEFAULT NULL, INDEX IDX_C39FEDD3A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passager (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, nationalite VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, escale_id INT DEFAULT NULL, service_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) NOT NULL, update_at DATETIME NOT NULL, create_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D64962EE4DEE (escale_id), INDEX IDX_8D93D649ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol (id INT AUTO_INCREMENT NOT NULL, depart_id INT DEFAULT NULL, arrive_id INT DEFAULT NULL, numero_vol VARCHAR(255) NOT NULL, escale JSON DEFAULT NULL, INDEX IDX_95C97EBAE02FE4B (depart_id), INDEX IDX_95C97EBF4028648 (arrive_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C571571A51189 FOREIGN KEY (passager_id) REFERENCES passager (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C571597942EEB FOREIGN KEY (escale_depart_id) REFERENCES escale (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C5715CD9456E8 FOREIGN KEY (escale_arrive_id) REFERENCES escale (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C571540082392 FOREIGN KEY (agent_chargement_contenaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C57155986E90A FOREIGN KEY (agent_chargement_soute_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C5715AB267538 FOREIGN KEY (agent_dechargement_soute_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C5715938D4837 FOREIGN KEY (agent_dechargement_contenaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C5715F81874EE FOREIGN KEY (agent_livraison_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C57159F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id)');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C57152F7AADEB FOREIGN KEY (agent_upload_manisfet_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE connexion ADD CONSTRAINT FK_936BF99CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE connexion ADD CONSTRAINT FK_936BF99C9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE escale ADD CONSTRAINT FK_C39FEDD3A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64962EE4DEE FOREIGN KEY (escale_id) REFERENCES escale (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EBAE02FE4B FOREIGN KEY (depart_id) REFERENCES escale (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EBF4028648 FOREIGN KEY (arrive_id) REFERENCES escale (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C571597942EEB');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C5715CD9456E8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64962EE4DEE');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBAE02FE4B');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBF4028648');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C571571A51189');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ED5CA9E6');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C571540082392');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C57155986E90A');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C5715AB267538');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C5715938D4837');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C5715F81874EE');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C57152F7AADEB');
        $this->addSql('ALTER TABLE connexion DROP FOREIGN KEY FK_936BF99CFB88E14F');
        $this->addSql('ALTER TABLE escale DROP FOREIGN KEY FK_C39FEDD3A73F0036');
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C57159F2BFB7A');
        $this->addSql('ALTER TABLE connexion DROP FOREIGN KEY FK_936BF99C9F2C3FAB');
        $this->addSql('DROP TABLE bagage');
        $this->addSql('DROP TABLE connexion');
        $this->addSql('DROP TABLE escale');
        $this->addSql('DROP TABLE passager');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE vol');
        $this->addSql('DROP TABLE zone');
    }
}
