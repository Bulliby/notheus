<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818185945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, project_group_id INT DEFAULT NULL, project_list_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_2FB3D0EEC31A529C (project_group_id), INDEX IDX_2FB3D0EEABB01AEE (project_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_detail (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_39A294D9166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEC31A529C FOREIGN KEY (project_group_id) REFERENCES project_group (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEABB01AEE FOREIGN KEY (project_list_id) REFERENCES project_list (id)');
        $this->addSql('ALTER TABLE project_detail ADD CONSTRAINT FK_39A294D9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEC31A529C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEABB01AEE');
        $this->addSql('ALTER TABLE project_detail DROP FOREIGN KEY FK_39A294D9166D1F9C');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_detail');
        $this->addSql('DROP TABLE project_group');
        $this->addSql('DROP TABLE project_list');
        $this->addSql('DROP TABLE messenger_messages');
    }
}