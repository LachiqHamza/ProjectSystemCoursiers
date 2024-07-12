<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712101652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id_admin INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, tele VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, date_intergration VARCHAR(255) DEFAULT NULL, salaire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coursiers (id_coursier INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, tele VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, date_intergration DATE DEFAULT NULL, salaire DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id_coursier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id_facture INT AUTO_INCREMENT NOT NULL, id_client_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_FE86641099DED506 (id_client_id), PRIMARY KEY(id_facture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641099DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client MODIFY id_client INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON client');
        $this->addSql('ALTER TABLE client ADD name VARCHAR(100) NOT NULL, ADD lastname VARCHAR(100) NOT NULL, ADD email VARCHAR(100) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD tele VARCHAR(15) NOT NULL, ADD role VARCHAR(50) NOT NULL, CHANGE id_client id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE client ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641099DED506');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE coursiers');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE facture');
        $this->addSql('ALTER TABLE client MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON client');
        $this->addSql('ALTER TABLE client DROP name, DROP lastname, DROP email, DROP password, DROP tele, DROP role, CHANGE id id_client INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE client ADD PRIMARY KEY (id_client)');
    }
}
