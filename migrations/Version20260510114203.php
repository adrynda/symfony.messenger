<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260510114203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE credentials_email credentials_email VARCHAR(50) NOT NULL, CHANGE credentials_password credentials_password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9299C9369 ON users (credentials_email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1483A5E9299C9369 ON users');
        $this->addSql('ALTER TABLE users CHANGE credentials_email credentials_email TINYTEXT NOT NULL, CHANGE credentials_password credentials_password LONGTEXT NOT NULL');
    }
}
