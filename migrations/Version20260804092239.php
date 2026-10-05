<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260804092239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD current_content_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB255B1B6A9F FOREIGN KEY (current_content_id) REFERENCES task_content (id)');
        $this->addSql('CREATE INDEX IDX_527EDB255B1B6A9F ON task (current_content_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB255B1B6A9F');
        $this->addSql('DROP INDEX IDX_527EDB255B1B6A9F ON task');
        $this->addSql('ALTER TABLE task DROP current_content_id');
    }
}
