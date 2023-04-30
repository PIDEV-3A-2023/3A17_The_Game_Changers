<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428085330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP rating');
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY fk_emp');
        $this->addSql('DROP INDEX fk_emp ON reparation');
        $this->addSql('ALTER TABLE reparation DROP id_employe');
        $this->addSql('ALTER TABLE vehicule ADD reparation_id INT DEFAULT NULL, CHANGE marque marque VARCHAR(255) NOT NULL, CHANGE vitesse_max vitesse_max VARCHAR(255) NOT NULL, CHANGE auto_batterie auto_batterie VARCHAR(255) NOT NULL, CHANGE couleur couleur VARCHAR(255) NOT NULL, CHANGE type_vehicule type_vehicule VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D97934BA FOREIGN KEY (reparation_id) REFERENCES reparation (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1D97934BA ON vehicule (reparation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD rating INT NOT NULL');
        $this->addSql('ALTER TABLE reparation ADD id_employe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT fk_emp FOREIGN KEY (id_employe) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX fk_emp ON reparation (id_employe)');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D97934BA');
        $this->addSql('DROP INDEX IDX_292FFF1D97934BA ON vehicule');
        $this->addSql('ALTER TABLE vehicule DROP reparation_id, CHANGE marque marque VARCHAR(30) NOT NULL, CHANGE vitesse_max vitesse_max VARCHAR(30) NOT NULL, CHANGE auto_batterie auto_batterie VARCHAR(50) NOT NULL, CHANGE couleur couleur VARCHAR(30) NOT NULL, CHANGE type_vehicule type_vehicule VARCHAR(30) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
