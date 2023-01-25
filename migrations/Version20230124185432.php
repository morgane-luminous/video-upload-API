<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230124185432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Video table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE video_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE video (
            id INT NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            description TEXT DEFAULT NULL, 
            file_name VARCHAR(255) NOT NULL, 
            file_size INT NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id),
            mime_type VARCHAR(255) NOT NULL,
            user_id INT NOT NULL
       )');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CA76ED395 ON video (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
//        $this->addSql('ALTER TABLE video DROP CONSTRAINT FK_7CC7DA2CA76ED395');
//        $this->addSql('DROP INDEX IDX_7CC7DA2CA76ED395');
        $this->addSql('DROP SEQUENCE video_id_seq CASCADE');
        $this->addSql('DROP TABLE video');
    }
}
