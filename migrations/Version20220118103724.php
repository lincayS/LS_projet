<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118103724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, jeans_id INT NOT NULL, purchase_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_98344FA6F8A706DB (jeans_id), INDEX IDX_98344FA6558FBEB9 (purchase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, purchase_id INT NOT NULL, created_at DATETIME NOT NULL, stripe_session_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B1DC7A1E558FBEB9 (purchase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, created_at DATETIME NOT NULL, reference VARCHAR(40) NOT NULL, INDEX IDX_6117D13B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA6F8A706DB FOREIGN KEY (jeans_id) REFERENCES jeans (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA6558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA6558FBEB9');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E558FBEB9');
        $this->addSql('DROP TABLE detail_commande');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE purchase');
    }
}
