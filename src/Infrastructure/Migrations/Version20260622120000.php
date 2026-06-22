<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add the missing reset_password_request columns (user relation + token data).
 *
 * The previous migration only created the "id" column, so the columns required
 * by ResetPasswordRequestTrait and the User relation are added here.
 */
final class Version20260622120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user, selector, hashed_token, requested_at and expires_at columns to reset_password_request';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE reset_password_request ADD COLUMN IF NOT EXISTS user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD COLUMN IF NOT EXISTS selector VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD COLUMN IF NOT EXISTS hashed_token VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD COLUMN IF NOT EXISTS requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD COLUMN IF NOT EXISTS expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP COLUMN expires_at');
        $this->addSql('ALTER TABLE reset_password_request DROP COLUMN requested_at');
        $this->addSql('ALTER TABLE reset_password_request DROP COLUMN hashed_token');
        $this->addSql('ALTER TABLE reset_password_request DROP COLUMN selector');
        $this->addSql('ALTER TABLE reset_password_request DROP COLUMN user_id');
    }
}
