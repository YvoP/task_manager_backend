<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260728065044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, project_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_659DF2AA166D1F9C (project_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE chat_project_user (chat_id INT NOT NULL, project_user_id INT NOT NULL, INDEX IDX_4AE977D01A9A7125 (chat_id), INDEX IDX_4AE977D03170DFF0 (project_user_id), PRIMARY KEY (chat_id, project_user_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, file_size INT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_8C9F3610B03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, transcript LONGTEXT DEFAULT NULL, summary LONGTEXT DEFAULT NULL, audio VARCHAR(255) DEFAULT NULL, duration INT NOT NULL, created_at DATETIME NOT NULL, project_id INT NOT NULL, INDEX IDX_F515E139166D1F9C (project_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE meeting_project_user (meeting_id INT NOT NULL, project_user_id INT NOT NULL, INDEX IDX_9441FBEC67433D9C (meeting_id), INDEX IDX_9441FBEC3170DFF0 (project_user_id), PRIMARY KEY (meeting_id, project_user_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, chat_id INT NOT NULL, project_user_id INT NOT NULL, INDEX IDX_B6BD307F1A9A7125 (chat_id), INDEX IDX_B6BD307F3170DFF0 (project_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, discord_server VARCHAR(255) DEFAULT NULL, discord_channel VARCHAR(255) DEFAULT NULL, auto_record_discord_meeting TINYINT NOT NULL, created_at DATETIME NOT NULL, is_archived TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE project_user (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(255) NOT NULL, project VARCHAR(255) NOT NULL, permissions JSON NOT NULL, joined_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, project_id INT NOT NULL, source_meeting_id INT DEFAULT NULL, created_by_id INT NOT NULL, INDEX IDX_527EDB25166D1F9C (project_id), INDEX IDX_527EDB251BF74CB8 (source_meeting_id), INDEX IDX_527EDB25B03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE task_content (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, priority INT NOT NULL, start_date DATETIME DEFAULT NULL, deadline DATETIME DEFAULT NULL, is_archived TINYINT NOT NULL, attributed_to_id INT DEFAULT NULL, modified_by_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_E1241A433288790B (attributed_to_id), INDEX IDX_E1241A4399049ECE (modified_by_id), INDEX IDX_E1241A438DB60186 (task_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE task_content_file (task_content_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_896CFFF898B3E842 (task_content_id), INDEX IDX_896CFFF893CB796C (file_id), PRIMARY KEY (task_content_id, file_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, discord_id VARCHAR(255) DEFAULT NULL, teams_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, roles JSON NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE chat_project_user ADD CONSTRAINT FK_4AE977D01A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_project_user ADD CONSTRAINT FK_4AE977D03170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610B03A8386 FOREIGN KEY (created_by_id) REFERENCES project_user (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E139166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE meeting_project_user ADD CONSTRAINT FK_9441FBEC67433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meeting_project_user ADD CONSTRAINT FK_9441FBEC3170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB251BF74CB8 FOREIGN KEY (source_meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES project_user (id)');
        $this->addSql('ALTER TABLE task_content ADD CONSTRAINT FK_E1241A433288790B FOREIGN KEY (attributed_to_id) REFERENCES project_user (id)');
        $this->addSql('ALTER TABLE task_content ADD CONSTRAINT FK_E1241A4399049ECE FOREIGN KEY (modified_by_id) REFERENCES project_user (id)');
        $this->addSql('ALTER TABLE task_content ADD CONSTRAINT FK_E1241A438DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_content_file ADD CONSTRAINT FK_896CFFF898B3E842 FOREIGN KEY (task_content_id) REFERENCES task_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_content_file ADD CONSTRAINT FK_896CFFF893CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA166D1F9C');
        $this->addSql('ALTER TABLE chat_project_user DROP FOREIGN KEY FK_4AE977D01A9A7125');
        $this->addSql('ALTER TABLE chat_project_user DROP FOREIGN KEY FK_4AE977D03170DFF0');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610B03A8386');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E139166D1F9C');
        $this->addSql('ALTER TABLE meeting_project_user DROP FOREIGN KEY FK_9441FBEC67433D9C');
        $this->addSql('ALTER TABLE meeting_project_user DROP FOREIGN KEY FK_9441FBEC3170DFF0');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3170DFF0');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB251BF74CB8');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task_content DROP FOREIGN KEY FK_E1241A433288790B');
        $this->addSql('ALTER TABLE task_content DROP FOREIGN KEY FK_E1241A4399049ECE');
        $this->addSql('ALTER TABLE task_content DROP FOREIGN KEY FK_E1241A438DB60186');
        $this->addSql('ALTER TABLE task_content_file DROP FOREIGN KEY FK_896CFFF898B3E842');
        $this->addSql('ALTER TABLE task_content_file DROP FOREIGN KEY FK_896CFFF893CB796C');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_project_user');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE meeting_project_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_user');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_content');
        $this->addSql('DROP TABLE task_content_file');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
