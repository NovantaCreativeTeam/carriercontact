<?php

use Novanta\CarrierContact\Install\InstallerFactory;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

$autoloadPath = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class CarrierContact extends Module {

    public function __construct()
    {
        $this->name = 'carriercontact';
        $this->tab = 'administration';
        $this->version = '1.1.0';
        $this->author = 'Novanta';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Carrier Contact', [], 'Modules.Carriercontact.Admin');
        $this->description = $this->trans('Enable functionality to specify Carrier contact details.', [], 'Modules.Carriercontact.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Carriercontact.Admin');
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
    }

    /**
     * @return true
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install(): bool
    {
        $installer = InstallerFactory::create();

        return parent::install()
            && $installer->install($this);
    }

    public function uninstall(): bool
    {
        $installer = InstallerFactory::create();

        return parent::uninstall()
            && $installer->uninstall($this);
    }

    public function getContent()
    {
        Tools::redirectAdmin(SymfonyContainer::getInstance()->get('router')->generate('admin_carrier_contact_index'));
    }
}
