<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704150253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_order_product (id INT AUTO_INCREMENT NOT NULL, customer_order_id INT UNSIGNED NOT NULL, product_id INT NOT NULL, quantity_ordered SMALLINT NOT NULL, quantity_packed SMALLINT DEFAULT 0 NOT NULL, INDEX IDX_4155DDE5A15A2E17 (customer_order_id), INDEX IDX_4155DDE54584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(80) NOT NULL, slug VARCHAR(100) NOT NULL, is_medical TINYINT(1) DEFAULT 0 NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, metric_value DOUBLE PRECISION DEFAULT NULL, metric_type VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04AD989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_order_product ADD CONSTRAINT FK_4155DDE5A15A2E17 FOREIGN KEY (customer_order_id) REFERENCES customer_order (id)');
        $this->addSql('ALTER TABLE customer_order_product ADD CONSTRAINT FK_4155DDE54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE customer_order CHANGE status_fulfillment status_fulfillment VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_order_product DROP FOREIGN KEY FK_4155DDE5A15A2E17');
        $this->addSql('ALTER TABLE customer_order_product DROP FOREIGN KEY FK_4155DDE54584665A');
        $this->addSql('DROP TABLE customer_order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE customer_order CHANGE status_fulfillment status_fulfillment SMALLINT UNSIGNED NOT NULL');
    }
}
