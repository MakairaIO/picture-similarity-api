<?php

declare(strict_types=1);

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303170745 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('DROP INDEX search_idx ON picture_similarity');
        $this->addSql('ALTER TABLE picture_similarity ADD type VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX search_idx ON picture_similarity (product_id, shop, type)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX search_idx ON picture_similarity');
        $this->addSql('ALTER TABLE picture_similarity DROP type');
        $this->addSql('CREATE INDEX search_idx ON picture_similarity (product_id, shop)');
    }
}
