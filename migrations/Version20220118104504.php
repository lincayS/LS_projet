<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118104504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(40) NOT NULL, ADD prenom VARCHAR(40) NOT NULL, ADD add_numero INT NOT NULL, ADD rue VARCHAR(255) NOT NULL, ADD complement VARCHAR(45) NOT NULL, ADD code_postal VARCHAR(10) NOT NULL, ADD ville VARCHAR(45) NOT NULL, ADD hanches DOUBLE PRECISION NOT NULL, ADD bassin DOUBLE PRECISION NOT NULL, ADD cuisses DOUBLE PRECISION NOT NULL, ADD jambes DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP add_numero, DROP rue, DROP complement, DROP code_postal, DROP ville, DROP hanches, DROP bassin, DROP cuisses, DROP jambes');
    }
}
