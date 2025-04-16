<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416095701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE device_usage_log (id INT AUTO_INCREMENT NOT NULL, device_id INT NOT NULL, started_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ended_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', duration INT DEFAULT NULL, energy_used_kwh DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CFF03E7B94A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE device_usage_log ADD CONSTRAINT FK_CFF03E7B94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE device_usage_log DROP FOREIGN KEY FK_CFF03E7B94A4C7D4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE device_usage_log
        SQL);
    }
}
