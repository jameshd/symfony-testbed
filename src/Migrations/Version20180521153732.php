<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180521153732 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE post_likes (post_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(post_id, user_id))');
        $this->addSql('CREATE INDEX IDX_DED1C2924B89032C ON post_likes (post_id)');
        $this->addSql('CREATE INDEX IDX_DED1C292A76ED395 ON post_likes (user_id)');
        $this->addSql('DROP INDEX IDX_71BF8DE31896F387');
        $this->addSql('DROP INDEX IDX_71BF8DE3A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__following AS SELECT user_id, following_user_id FROM following');
        $this->addSql('DROP TABLE following');
        $this->addSql('CREATE TABLE following (user_id INTEGER NOT NULL, following_user_id INTEGER NOT NULL, PRIMARY KEY(user_id, following_user_id), CONSTRAINT FK_71BF8DE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_71BF8DE31896F387 FOREIGN KEY (following_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO following (user_id, following_user_id) SELECT user_id, following_user_id FROM __temp__following');
        $this->addSql('DROP TABLE __temp__following');
        $this->addSql('CREATE INDEX IDX_71BF8DE31896F387 ON following (following_user_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE3A76ED395 ON following (user_id)');
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

        $this->addSql('DROP TABLE post_likes');
        $this->addSql('DROP INDEX IDX_71BF8DE3A76ED395');
        $this->addSql('DROP INDEX IDX_71BF8DE31896F387');
        $this->addSql('CREATE TEMPORARY TABLE __temp__following AS SELECT user_id, following_user_id FROM following');
        $this->addSql('DROP TABLE following');
        $this->addSql('CREATE TABLE following (user_id INTEGER NOT NULL, following_user_id INTEGER NOT NULL, PRIMARY KEY(user_id, following_user_id))');
        $this->addSql('INSERT INTO following (user_id, following_user_id) SELECT user_id, following_user_id FROM __temp__following');
        $this->addSql('DROP TABLE __temp__following');
        $this->addSql('CREATE INDEX IDX_71BF8DE3A76ED395 ON following (user_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE31896F387 ON following (following_user_id)');
        $this->addSql('DROP INDEX IDX_2AEFE017A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, user_id, text, time FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER NOT NULL, user_id INTEGER NOT NULL, text VARCHAR(280) NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO micro_post (id, user_id, text, time) SELECT id, user_id, text, time FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
        $this->addSql('CREATE INDEX IDX_2AEFE017A76ED395 ON micro_post (user_id)');
    }
}
