<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260512200919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chats (id BINARY(16) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE chats_users (chat_id BINARY(16) NOT NULL, user_id BINARY(16) NOT NULL, INDEX IDX_F17A2DBC1A9A7125 (chat_id), INDEX IDX_F17A2DBCA76ED395 (user_id), PRIMARY KEY (chat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messages (id BINARY(16) NOT NULL, sent_at DATETIME NOT NULL, content LONGTEXT NOT NULL, user_id BINARY(16) NOT NULL, chat_id BINARY(16) NOT NULL, INDEX IDX_DB021E96A76ED395 (user_id), INDEX IDX_DB021E961A9A7125 (chat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id BINARY(16) NOT NULL, username LONGTEXT NOT NULL, avatar LONGTEXT DEFAULT NULL, credentials_email VARCHAR(50) NOT NULL, credentials_password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9299C9369 (credentials_email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chats_users ADD CONSTRAINT FK_F17A2DBC1A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chats_users ADD CONSTRAINT FK_F17A2DBCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E961A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chats_users DROP FOREIGN KEY FK_F17A2DBC1A9A7125');
        $this->addSql('ALTER TABLE chats_users DROP FOREIGN KEY FK_F17A2DBCA76ED395');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E961A9A7125');
        $this->addSql('DROP TABLE chats');
        $this->addSql('DROP TABLE chats_users');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE users');
    }
}
