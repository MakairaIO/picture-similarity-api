<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260930091201 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_similarity RENAME TO picture_similarity_migration');
        $this->addSql('CREATE TABLE picture_similarity LIKE picture_similarity_migration');
        $this->addSql('ALTER TABLE picture_similarity DROP INDEX search_idx, ADD UNIQUE INDEX UNIQ_48FAE1474584665AAC6A4CA28CDE5729 (product_id, shop, type)');
        $this->addSql('ALTER TABLE picture_similarity CHANGE similar_ids similar_ids JSON NOT NULL');
        $this->addSql('INSERT IGNORE INTO picture_similarity (SELECT * FROM picture_similarity_migration ORDER BY updated_at DESC)');
        $this->addSql('DROP TABLE picture_similarity_migration');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_similarity DROP INDEX UNIQ_48FAE1474584665AAC6A4CA28CDE5729, ADD INDEX search_idx (product_id, shop, type)');
        $this->addSql('ALTER TABLE picture_similarity CHANGE similar_ids similar_ids LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
