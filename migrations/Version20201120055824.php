<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201120055824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bagage ADD statut_id INT DEFAULT NULL, DROP statut');
        $this->addSql('ALTER TABLE bagage ADD CONSTRAINT FK_A82C5715F6203804 FOREIGN KEY (statut_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_A82C5715F6203804 ON bagage (statut_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bagage DROP FOREIGN KEY FK_A82C5715F6203804');
        $this->addSql('DROP INDEX IDX_A82C5715F6203804 ON bagage');
        $this->addSql('ALTER TABLE bagage ADD statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP statut_id');
    }
}
