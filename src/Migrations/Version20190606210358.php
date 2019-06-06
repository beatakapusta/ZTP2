<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190606210358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B57E9E4C8C');
        $this->addSql('DROP INDEX UNIQ_A369E2B57E9E4C8C ON recipes');
        $this->addSql('ALTER TABLE recipes DROP photo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipes ADD photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B57E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A369E2B57E9E4C8C ON recipes (photo_id)');
    }
}
