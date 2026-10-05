<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260916192419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting ADD craig_id VARCHAR(255) DEFAULT NULL, ADD recorded_at DATETIME NOT NULL, ADD craig_key VARCHAR(255) DEFAULT NULL, ADD status VARCHAR(255) NOT NULL, ADD active_process VARCHAR(255) DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting DROP craig_id, DROP recorded_at, DROP craig_key, DROP status, DROP active_process, CHANGE project_id project_id INT NOT NULL');
    }
}
