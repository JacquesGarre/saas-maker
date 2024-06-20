<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620174229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applications_users (id UUID NOT NULL, user_id UUID DEFAULT NULL, application_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_26951658A76ED395 ON applications_users (user_id)');
        $this->addSql('CREATE INDEX IDX_269516583E030ACD ON applications_users (application_id)');
        $this->addSql('COMMENT ON COLUMN applications_users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN applications_users.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN applications_users.application_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE applications_users ADD CONSTRAINT FK_26951658A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE applications_users ADD CONSTRAINT FK_269516583E030ACD FOREIGN KEY (application_id) REFERENCES applications (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE applications_users DROP CONSTRAINT FK_26951658A76ED395');
        $this->addSql('ALTER TABLE applications_users DROP CONSTRAINT FK_269516583E030ACD');
        $this->addSql('DROP TABLE applications_users');
    }
}
