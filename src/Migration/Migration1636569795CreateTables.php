<?php declare(strict_types=1);

namespace Shyim\Pizza\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1636569795CreateTables extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1636569795;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
'CREATE TABLE `pizza` (
    `id` BINARY(16) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `price` DOUBLE NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function updateDestructive(Connection $connection): void
    {}
}
