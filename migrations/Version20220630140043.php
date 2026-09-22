<?php

namespace Makaira\PictureSimilarity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220630140043 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture_similarity ADD INDEX updated_at (updated_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture_similarity DROP INDEX updated_at');
    }
}
