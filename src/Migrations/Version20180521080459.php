<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180521080459 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2AEFE017A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, user_id, text, time FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER NOT NULL, user_id INTEGER NOT NULL, text VARCHAR(280) NOT NULL COLLATE BINARY, time DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_2AEFE017A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO micro_post (id, user_id, text, time) SELECT id, user_id, text, time FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
        $this->addSql('CREATE INDEX IDX_2AEFE017A76ED395 ON micro_post (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2AEFE017A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, user_id, text, time FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER NOT NULL, user_id INTEGER NOT NULL, text VARCHAR(280) NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO micro_post (id, user_id, text, time) SELECT id, user_id, text, time FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
        $this->addSql('CREATE INDEX IDX_2AEFE017A76ED395 ON micro_post (user_id)');
    }
}
