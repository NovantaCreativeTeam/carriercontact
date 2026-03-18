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

namespace Novanta\CarrierContact\Adapter\CarrierContact;

use Doctrine\ORM\EntityManagerInterface;
use Novanta\CarrierContact\Domain\CarrierContact\Exception\CarrierContactNotFoundException;
use Novanta\CarrierContact\Entity\CarrierContact;
use PrestaShop\PrestaShop\Adapter\Validate;
use PrestaShop\PrestaShop\Core\Domain\Carrier\Exception\CarrierNotFoundException;

abstract class AbstractCarrierContactHandler
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws CarrierNotFoundException
     */
    public function getCarrier($carrierId)
    {
        $carrier = \Carrier::getCarrierByReference($carrierId);
        if (!Validate::isLoadedObject($carrier)) {
            throw new CarrierNotFoundException(sprintf('Carrier "%s" not found.', $carrierId));
        }

        return $carrier;
    }

    /**
     * @param $idCarrierContact
     *
     * @return CarrierContact
     *
     * @throws CarrierContactNotFoundException
     */
    public function getCarrierContact($idCarrierContact): CarrierContact
    {
        $carrierContact = $this->entityManager->getRepository(CarrierContact::class)->find($idCarrierContact);
        if (!$carrierContact) {
            throw new CarrierContactNotFoundException(sprintf('Carrier contact with id "%s" not found.', $idCarrierContact));
        }

        return $carrierContact;
    }
}
