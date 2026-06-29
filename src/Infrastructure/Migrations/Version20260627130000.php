<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260627130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add last_reminder_at to battery for reminder scheduling';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE battery ADD last_reminder_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE battery DROP last_reminder_at');
    }
}
