<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */
namespace Novanta\CarrierContact\Adapter\Install;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Installer
{
    /**
     * Funzione che effettia l'istallazione del modulo
     *
     * @param \Module $module
     *
     * @return bool
     */
    public function install(\Module $module): bool
    {
        return $this->installDatabase()
            && $this->registerHooks($module)
            && $this->initializeConfiguration($module);
    }

    /**
     * Funzione che effettua la disistallazione del modulo
     *
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase()
            && $this->destroyConfiguration();
    }

    /**
     * Funzione che crea le tabelle del modulo
     *
     * @return bool
     */
    protected function installDatabase()
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carrier_contact` (
                `id_carrier_contact` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_carrier` int(11) NOT NULL,
                `name` varchar(64) NOT NULL,
                `email1` varchar(255) NOT NULL,
                `email2` varchar(255) NULL,
                PRIMARY KEY (`id_carrier_contact`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',

        ];

        return $this->executeQueries($queries);
    }

    /**
     * Funzione che elimina le tabelle del modulo
     *
     * @return bool
     */
    protected function uninstallDatabase()
    {
        $queries = [
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'carrier_contact`',
        ];

        return $this->executeQueries($queries);
    }

    /**
     * Funzione che registra gli hook del modulo
     *
     * @param \Module $module
     *
     * @return bool
     */
    protected function registerHooks(\Module $module)
    {
        $hooks = [];
        return (bool) $module->registerHook($hooks);
    }

    /**
     * Funzione che inizializza la configurazione del modulo
     *
     * @return bool
     */
    protected function initializeConfiguration(\Module $module)
    {
        return true;
    }

    /**
     * Funzione che cancella la configurazione del modulo
     *
     * @return bool
     */
    protected function destroyConfiguration()
    {
        // Do not destroy anything
        return true;
    }

    /**
     * Funzione che si occupa di eseguire le query
     * per l'istallazione e la disistallazione del modulo
     *
     * @param array $queries
     *
     * @return bool
     */
    private function executeQueries($queries)
    {
        foreach ($queries as $query) {
            if (!\Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
}
