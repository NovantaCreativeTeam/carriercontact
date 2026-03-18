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

namespace Novanta\CarrierContact\Adapter\CarrierContact\QueryHandler;

use Novanta\CarrierContact\Adapter\CarrierContact\AbstractCarrierContactHandler;
use Novanta\CarrierContact\Domain\CarrierContact\Exception\CarrierContactNotFoundException;
use Novanta\CarrierContact\Domain\CarrierContact\Query\GetCarrierContactForEditing;
use Novanta\CarrierContact\Domain\CarrierContact\QueryHandler\GetCarrierContactFotEditingHandlerInterface;
use Novanta\CarrierContact\Domain\CarrierContact\QueryResult\EditableCarrierContact;

class GetCarrierContactForEditingHandler extends AbstractCarrierContactHandler implements GetCarrierContactFotEditingHandlerInterface
{
    /**
     * @param GetCarrierContactForEditing $command
     *
     * @return EditableCarrierContact
     *
     * @throws CarrierContactNotFoundException
     */
    public function handle(GetCarrierContactForEditing $command)
    {
        // 1. Retrieve Carrier Contact
        $carrierContact = $this->getCarrierContact($command->getIdCarrierContact());

        // 2. Create DTO
        return new EditableCarrierContact(
            $carrierContact->getId(),
            $carrierContact->getIdCarrier(),
            $carrierContact->getName(),
            $carrierContact->getEmail1(),
            $carrierContact->getEmail2(),
        );
    }
}
