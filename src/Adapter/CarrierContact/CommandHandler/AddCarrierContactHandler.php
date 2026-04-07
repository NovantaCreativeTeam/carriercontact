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

namespace Novanta\CarrierContact\Adapter\CarrierContact\CommandHandler;

use Novanta\CarrierContact\Adapter\CarrierContact\AbstractCarrierContactHandler;
use Novanta\CarrierContact\Domain\CarrierContact\Command\AddCarrierContact;
use Novanta\CarrierContact\Domain\CarrierContact\CommandHandler\AddCarrierContactHandlerInterface;
use Novanta\CarrierContact\Entity\CarrierContact;
use PrestaShop\PrestaShop\Core\Domain\Carrier\Exception\CarrierNotFoundException;

class AddCarrierContactHandler extends AbstractCarrierContactHandler implements AddCarrierContactHandlerInterface
{
    /**
     * @param AddCarrierContact $command
     *
     * @return int
     *
     * @throws CarrierNotFoundException
     */
    public function handle(AddCarrierContact $command)
    {
        // 1. Create Carrier Contact
        $carrierContact = new CarrierContact();

        // 2. Retrieve Carrier if passed
        if ($command->getCarrierId()) {
            $carrier = $this->getCarrier($command->getCarrierId());
            $carrierContact->setIdCarrier($carrier->id_reference);
        }

        // 3. Set Properties
        $carrierContact->setPhone($command->getPhone());
        $carrierContact->setEmail1($command->getEmail1());
        $carrierContact->setEmail2($command->getEmail2());
        $carrierContact->setName($command->getName());

        $this->entityManager->persist($carrierContact);
        $this->entityManager->flush();

        return $carrierContact->getId();
    }
}
