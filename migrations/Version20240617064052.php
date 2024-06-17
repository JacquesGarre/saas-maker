<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617064052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application_users (id UUID NOT NULL, application_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F71742933E030ACD ON application_users (application_id)');
        $this->addSql('CREATE INDEX IDX_F7174293A76ED395 ON application_users (user_id)');
        $this->addSql('COMMENT ON COLUMN application_users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN application_users.application_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN application_users.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE application_users ADD CONSTRAINT FK_F71742933E030ACD FOREIGN KEY (application_id) REFERENCES applications (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_users ADD CONSTRAINT FK_F7174293A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE application_users DROP CONSTRAINT FK_F71742933E030ACD');
        $this->addSql('ALTER TABLE application_users DROP CONSTRAINT FK_F7174293A76ED395');
        $this->addSql('DROP TABLE application_users');
    }
}
