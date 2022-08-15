<?php

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20190101000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE picture_similarity (
                id INT AUTO_INCREMENT NOT NULL,
                product_id VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci,
                similar_ids LONGTEXT NOT NULL COLLATE utf8mb4_bin,
                shop VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci,
                updated_at DATETIME NOT NULL,
                INDEX search_idx (product_id, shop),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE picture_similarity');
    }
}
