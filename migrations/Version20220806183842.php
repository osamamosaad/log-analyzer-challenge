<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806183842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `log_file` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                CREATE TABLE `log_file` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `unique_name` CHAR(32) NOT NULL,
                        `file_name` VARCHAR(255) NOT NULL,
                        `status` ENUM('in-progress', 'stopped', 'done') NOT NULL,
                        `total_lines` INT NOT NULL,
                        `last_line` INT NOT NULL DEFAULT 0,
                        `created_at` DATETIME NOT NULL,
                        `updated_at` DATETIME DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                DROP TABLE log_file;
            SQL
        );
    }
}
