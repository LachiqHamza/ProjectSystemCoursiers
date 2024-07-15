<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715103629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id_admin INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, tele VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, date_intergration VARCHAR(255) DEFAULT NULL, salaire DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id_client INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, tele VARCHAR(15) NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id_client)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coursiers (id_coursier INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, tele VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, date_intergration DATETIME DEFAULT NULL, salaire DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id_coursier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id_demande INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, id_admin INT DEFAULT NULL, id_coursier INT DEFAULT NULL, date VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_2694D7A5E173B1B8 (id_client), INDEX IDX_2694D7A5668B4C46 (id_admin), INDEX IDX_2694D7A55693F75A (id_coursier), PRIMARY KEY(id_demande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id_facture INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_FE866410E173B1B8 (id_client), PRIMARY KEY(id_facture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5668B4C46 FOREIGN KEY (id_admin) REFERENCES admin (id_admin)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A55693F75A FOREIGN KEY (id_coursier) REFERENCES coursiers (id_coursier)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id_client)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5E173B1B8');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5668B4C46');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A55693F75A');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410E173B1B8');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE coursiers');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
