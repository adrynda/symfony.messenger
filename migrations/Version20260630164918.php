<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630164918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chats (id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE chats_users (chat_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (chat_id, user_id))');
        $this->addSql('CREATE INDEX IDX_F17A2DBC1A9A7125 ON chats_users (chat_id)');
        $this->addSql('CREATE INDEX IDX_F17A2DBCA76ED395 ON chats_users (user_id)');
        $this->addSql('CREATE TABLE messages (id UUID NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, content TEXT NOT NULL, user_id UUID NOT NULL, chat_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_DB021E96A76ED395 ON messages (user_id)');
        $this->addSql('CREATE INDEX IDX_DB021E961A9A7125 ON messages (chat_id)');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, username TEXT NOT NULL, avatar TEXT DEFAULT NULL, credentials_email VARCHAR(50) NOT NULL, credentials_password VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9299C9369 ON users (credentials_email)');
        $this->addSql('ALTER TABLE chats_users ADD CONSTRAINT FK_F17A2DBC1A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chats_users ADD CONSTRAINT FK_F17A2DBCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E961A9A7125 FOREIGN KEY (chat_id) REFERENCES chats (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chats_users DROP CONSTRAINT FK_F17A2DBC1A9A7125');
        $this->addSql('ALTER TABLE chats_users DROP CONSTRAINT FK_F17A2DBCA76ED395');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_DB021E961A9A7125');
        $this->addSql('DROP TABLE chats');
        $this->addSql('DROP TABLE chats_users');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE users');
    }
}
