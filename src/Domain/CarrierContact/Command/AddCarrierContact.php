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

namespace Novanta\CarrierContact\Domain\CarrierContact\Command;

class AddCarrierContact
{
    private $carrierId;
    private $name;
    private $email1;
    private $email2;

    public function __construct(
        $carrierId,
        $name,
        $email1,
        $email2 = null
    ) {
        $this->carrierId = $carrierId;
        $this->name = $name;
        $this->email1 = $email1;
        $this->email2 = $email2;
    }

    /**
     * @return mixed
     */
    public function getCarrierId()
    {
        return $this->carrierId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * @return mixed
     */
    public function getEmail2()
    {
        return $this->email2;
    }
}
