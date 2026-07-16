<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260716115426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_tokens RENAME COLUMN enum TO type');
        $this->addSql('ALTER TABLE users ADD credentials_active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE users ADD credentials_roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_tokens RENAME COLUMN type TO enum');
        $this->addSql('ALTER TABLE users DROP credentials_active');
        $this->addSql('ALTER TABLE users DROP credentials_roles');
    }
}
