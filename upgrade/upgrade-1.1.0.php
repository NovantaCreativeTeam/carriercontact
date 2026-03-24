<?php

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

function upgrade_module_1_1_0($module) {
    $translator = SymfonyContainer::getInstance()->get('translator');
    $tab_names = [];

    foreach (\Language::getLanguages() as $lang) {
        $tab_names[$lang['id_lang']] = $translator->trans('Carrier Contacts', [], 'Modules.Carriercontact.Admin', $lang['locale']);
    }

    return addTab_1_1_0($tab_names, 'AdminCarrierContact', 'AdminParentShipping', $module);
}

function addTab_1_1_0($name, $className, $parentClassName, $module)
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
    $tab->module = $module->name;

    return $tab->save();
}
