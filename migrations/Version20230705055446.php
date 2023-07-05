<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230705055446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_order_product DROP FOREIGN KEY FK_4155DDE54584665A');
        $this->addSql('ALTER TABLE customer_order_product CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE customer_order_product ADD CONSTRAINT FK_4155DDE54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_order_product DROP FOREIGN KEY FK_4155DDE54584665A');
        $this->addSql('ALTER TABLE product CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE customer_order_product CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer_order_product ADD CONSTRAINT FK_4155DDE54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }
}
