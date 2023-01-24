<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124212048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Category table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE category_video (category_id INT NOT NULL, video_id INT NOT NULL, PRIMARY KEY(category_id, video_id))');
        $this->addSql('CREATE INDEX IDX_94F4956512469DE2 ON category_video (category_id)');
        $this->addSql('CREATE INDEX IDX_94F4956529C1004E ON category_video (video_id)');
        $this->addSql('ALTER TABLE category_video ADD CONSTRAINT FK_94F4956512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_video ADD CONSTRAINT FK_94F4956529C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('ALTER TABLE category_video DROP CONSTRAINT FK_94F4956512469DE2');
        $this->addSql('ALTER TABLE category_video DROP CONSTRAINT FK_94F4956529C1004E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_video');
    }
}
