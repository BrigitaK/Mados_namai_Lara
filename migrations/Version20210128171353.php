<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128171353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE master (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outfit (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, color VARCHAR(20) NOT NULL, size SMALLINT NOT NULL, about LONGTEXT NOT NULL, master_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE masters');
        $this->addSql('DROP TABLE outfits');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE masters (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, surname VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE outfits (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, color VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, size TINYINT(1) NOT NULL, about LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, master_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE master');
        $this->addSql('DROP TABLE outfit');
    }
}
