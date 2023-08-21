<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703090556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assistance (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, order_invoice_id INT DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, file VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_906517449A530CA8 (order_invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449A530CA8 FOREIGN KEY (order_invoice_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE carousel ADD btn_title VARCHAR(255) NOT NULL, ADD bnt_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD state INT NOT NULL, DROP is_paid');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517449A530CA8');
        $this->addSql('DROP TABLE assistance');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('ALTER TABLE carousel DROP btn_title, DROP bnt_url');
        $this->addSql('ALTER TABLE `order` ADD is_paid TINYINT(1) NOT NULL, DROP state');
        $this->addSql('ALTER TABLE user DROP avatar');
    }
}
