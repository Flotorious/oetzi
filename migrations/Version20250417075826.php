<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417075826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_energy_snapshot (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, timestamp DATETIME NOT NULL, consumption_kwh DOUBLE PRECISION NOT NULL, INDEX IDX_BF6A519DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_energy_snapshot ADD CONSTRAINT FK_BF6A519DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD feed_url VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_energy_snapshot DROP FOREIGN KEY FK_BF6A519DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_energy_snapshot
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP feed_url
        SQL);
    }
}
