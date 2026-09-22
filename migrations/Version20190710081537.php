<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710081537 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE picture_similarity CHANGE product_id product_id VARCHAR(255) NOT NULL, CHANGE similar_ids similar_ids JSON NOT NULL, CHANGE shop shop VARCHAR(255) NOT NULL',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE picture_similarity CHANGE product_id product_id VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE similar_ids similar_ids LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE shop shop VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci',
        );
    }
}
