<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240616103805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applications (id UUID NOT NULL, created_by_id UUID DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, subdomain VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7C966F0B03A8386 ON applications (created_by_id)');
        $this->addSql('COMMENT ON COLUMN applications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN applications.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN applications.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE applications ADD CONSTRAINT FK_F7C966F0B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE applications DROP CONSTRAINT FK_F7C966F0B03A8386');
        $this->addSql('DROP TABLE applications');
    }
}
