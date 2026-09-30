<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260930105227 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_48FAE1474584665AAC6A4CA28CDE5729 ON picture_similarity');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48FAE1478CDE5729AC6A4CA24584665A ON picture_similarity (type, shop, product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_48FAE1478CDE5729AC6A4CA24584665A ON picture_similarity');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48FAE1474584665AAC6A4CA28CDE5729 ON picture_similarity (product_id, shop, type)');
    }
}
