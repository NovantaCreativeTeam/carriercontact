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

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

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
            && $this->installTabs()
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
            && $this->uninstallTabs()
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

    /**
     * Funzione che si occupa di istallare le tab del menù
     * per l'accesso alla gestione dei preventivi
     *
     * @return bool
     */
    public function installTabs()
    {
        $translator = SymfonyContainer::getInstance()->get('translator');
        $tab_names = [];

        foreach (\Language::getLanguages() as $lang) {
            $tab_names[$lang['id_lang']] = $translator->trans('Carrier Contacts', [], 'Modules.Carriercontact.Admin', $lang['locale']);
        }

        return $this->addTab($tab_names, 'AdminCarrierContact', 'AdminParentCustomerThreads');
    }

    /**
     * Funzione che si occupa di disitallare le tab del menù
     * per l'accesso alla gestione dei preventivi
     *
     * @return bool
     */
    public function uninstallTabs()
    {
        $tabRepository = SymfonyContainer::getInstance()->get('prestashop.core.admin.tab.repository');
        $tabId = (int) $tabRepository->findOneIdByClassName('AdminCarrierContact');
        if (!$tabId) {
            return true;
        }

        $tab = new \Tab($tabId);

        return $tab->delete();
    }

    /**
     * Funzione che registra una nuova Tab del menù di amministrazione
     *
     * @param string $name
     * @param string $className
     * @param string $parentClassName
     *
     * @return bool
     */
    private function addTab($name, $className, $parentClassName)
    {
        $tabRepository = SymfonyContainer::getInstance()->get('prestashop.core.admin.tab.repository');

        $tabId = (int) $tabRepository->findOneIdByClassName($className);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new \Tab($tabId);
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = $name;
        $tab->id_parent = (int) $tabRepository->findOneIdByClassName($parentClassName);
        $tab->module = 'carriercontact';

        return $tab->save();
    }
}
