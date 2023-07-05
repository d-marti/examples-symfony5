<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230705061343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_order_product ADD quantity_to_pack SMALLINT NOT NULL');
        $this->addSql('UPDATE customer_order_product SET quantity_to_pack = quantity_ordered - quantity_packed');
        $this->addSql('ALTER TABLE customer_order_product DROP quantity_packed');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_order_product ADD quantity_packed SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('UPDATE customer_order_product SET quantity_packed = quantity_ordered - quantity_to_pack');
        $this->addSql('ALTER TABLE customer_order_product DROP quantity_to_pack');
    }
}
