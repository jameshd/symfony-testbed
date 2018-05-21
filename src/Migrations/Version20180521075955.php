<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180521075955 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, email, full_name, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, username VARCHAR(50) NOT NULL COLLATE BINARY, password VARCHAR(40) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, full_name VARCHAR(50) NOT NULL COLLATE BINARY, roles CLOB DEFAULT NULL --(DC2Type:simple_array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, password, email, full_name, roles) SELECT id, username, password, email, full_name, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
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
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, email, full_name, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, username VARCHAR(50) NOT NULL, password VARCHAR(40) NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(50) NOT NULL, roles CLOB DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, password, email, full_name, roles) SELECT id, username, password, email, full_name, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
