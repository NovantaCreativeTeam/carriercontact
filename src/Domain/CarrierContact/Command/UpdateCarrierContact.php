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

class UpdateCarrierContact
{
    private $carrierContactId;

    private $carrierId;

    private $name;

    private $phone;

    private $email1;

    private $email2;

    public function __construct($carrierContactId)
    {
        $this->carrierContactId = $carrierContactId;
    }

    /**
     * @return mixed
     */
    public function getCarrierContactId()
    {
        return $this->carrierContactId;
    }

    /**
     * @return mixed
     */
    public function getCarrierId()
    {
        return $this->carrierId;
    }

    /**
     * @param mixed $carrierId
     */
    public function setCarrierId($carrierId): void
    {
        $this->carrierId = $carrierId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * @param mixed $email1
     */
    public function setEmail1($email1): void
    {
        $this->email1 = $email1;
    }

    /**
     * @return mixed
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * @param mixed $email2
     */
    public function setEmail2($email2): void
    {
        $this->email2 = $email2;
    }
}
