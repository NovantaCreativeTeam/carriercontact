<?php

function upgrade_module_1_1_1($module) {
    $queries = [
        'ALTER TABLE `' . _DB_PREFIX_ . 'carrier_contact` ADD COLUMN `phone` varchar(32) NULL AFTER `name`',
    ];

    foreach ($queries as $query) {
        if (Db::getInstance()->execute($query) == false) {
            return false;
        }
    }

}
