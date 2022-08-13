<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806190416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `transaction_log` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                CREATE TABLE `transaction_log` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `line_num` INT NOT NULL,
                        `log_file_id` INT NOT NULL,
                        `service_name` VARCHAR(30) NOT NULL,
                        `endpoint` VARCHAR(255) NOT NULL,
                        `method` ENUM('POST', 'GET', 'PUT', 'DELETE', 'PATCH') NOT NULL,
                        `status_code` INT(3) NOT NULL,
                        `http_version` VARCHAR(30),
                        `log_date` DATETIME NOT NULL,
                        `created_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    INDEX `transaction_log_ix_log_file_id` (`log_file_id`),
                    INDEX `transaction_log_ix_method` (`method`),
                    INDEX `transaction_log_ix_status_code` (`status_code`),
                    INDEX `transaction_log_ix_service` (`service_name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                DROP TABLE transaction_log;
            SQL
        );
    }
}
