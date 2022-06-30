<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190101000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE picture_similarity (
                id INT AUTO_INCREMENT NOT NULL,
                product_id VARCHAR(255) NOT NULL,
                similar_ids JSON NOT NULL,
                shop VARCHAR(255) NOT NULL,
                updated_at DATETIME NOT NULL,
                type VARCHAR(255) NOT NULL,
                INDEX search_idx (product_id, shop, type),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE picture_similarity');
    }
}
