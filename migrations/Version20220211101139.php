<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211101139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase ADD payment_request_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B77883970 FOREIGN KEY (payment_request_id) REFERENCES paiement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6117D13B77883970 ON purchase (payment_request_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B77883970');
        $this->addSql('DROP INDEX UNIQ_6117D13B77883970 ON purchase');
        $this->addSql('ALTER TABLE purchase DROP payment_request_id');
    }
}
