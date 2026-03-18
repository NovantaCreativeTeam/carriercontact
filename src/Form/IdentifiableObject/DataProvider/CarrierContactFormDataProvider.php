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

namespace Novanta\CarrierContact\Form\IdentifiableObject\DataProvider;

use Novanta\CarrierContact\Domain\CarrierContact\Query\GetCarrierContactForEditing;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CarrierContactFormDataProvider implements FormDataProviderInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(
        CommandBusInterface $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getData($id)
    {
        $command = new GetCarrierContactForEditing($id);
        $carrierContact = $this->commandBus->handle($command);

        return [
            'id_carrier' => $carrierContact->getId(),
            'name' => $carrierContact->getName(),
            'email1' => $carrierContact->getEmail1(),
            'email2' => $carrierContact->getEmail2(),
        ];
    }

    public function getDefaultData()
    {
        // TODO: Implement getDefaultData() method.
    }
}
