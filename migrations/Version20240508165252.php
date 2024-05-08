<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508165252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_offer (purchase_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_FD1D0414558FBEB9 (purchase_id), INDEX IDX_FD1D041453C674EE (offer_id), PRIMARY KEY(purchase_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D0414558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D041453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D0414558FBEB9');
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D041453C674EE');
        $this->addSql('DROP TABLE purchase_offer');
    }
}
