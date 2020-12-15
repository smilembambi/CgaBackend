<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201117225925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bagage CHANGE code_barre code_barre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE passager ADD siege VARCHAR(255) DEFAULT NULL, ADD ticket VARCHAR(255) DEFAULT NULL, ADD pnr VARCHAR(255) DEFAULT NULL, ADD franchise_demande INT DEFAULT NULL, ADD franchise_disponible INT DEFAULT NULL, ADD excedant INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bagage CHANGE code_barre code_barre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE passager DROP siege, DROP ticket, DROP pnr, DROP franchise_demande, DROP franchise_disponible, DROP excedant');
    }
}
