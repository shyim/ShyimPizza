<?php declare(strict_types=1);

namespace Shyim\Pizza;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class ShyimPizza extends Plugin
{
    public function postInstall(InstallContext $installContext): void
    {
        $sql = <<<SQL
INSERT INTO `pizza` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(UNHEX('cfcd208495d565ef66e7dff9f98764da'),	'Small Pizza',	5,	'2021-11-10 20:24:26.000',	NULL);
SQL;

        $this->container->get(Connection::class)->executeStatement($sql);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        $this->container->get(Connection::class)->executeStatement('DROP TABLE IF EXISTS pizza');
    }
}
