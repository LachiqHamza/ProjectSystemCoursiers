<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715104449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD adress_source VARCHAR(255) DEFAULT NULL, ADD adress_dest VARCHAR(255) DEFAULT NULL, ADD poids DOUBLE PRECISION DEFAULT NULL, ADD date_demande DATE DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL, ADD date_livraison DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP adress_source, DROP adress_dest, DROP poids, DROP date_demande, DROP status, DROP date_livraison');
    }
}
