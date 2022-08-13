<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806223439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make relation `transaction_log` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                ALTER TABLE `transaction_log`
                    ADD CONSTRAINT `transaction_log_fk_log_file_id`
                    FOREIGN KEY (`log_file_id`)
                    REFERENCES `log_file`(`id`)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT;
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                ALTER TABLE `transaction_log` DROP FOREIGN KEY `transaction_log_fk_log_file_id`
            SQL
        );
    }
}
