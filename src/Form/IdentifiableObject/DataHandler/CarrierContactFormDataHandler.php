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

namespace Novanta\CarrierContact\Form\IdentifiableObject\DataHandler;

use Novanta\CarrierContact\Domain\CarrierContact\Command\AddCarrierContact;
use Novanta\CarrierContact\Domain\CarrierContact\Command\UpdateCarrierContact;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class CarrierContactFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $command = new AddCarrierContact(
            $data['id_carrier'],
            $data['name'],
            $data['phone'],
            $data['email1'],
            $data['email2'],
        );

        $carrierContactId = $this->commandBus->handle($command);

        return $carrierContactId;
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return void
     */
    public function update($id, array $data)
    {
        $command = new UpdateCarrierContact($id);
        $command->setCarrierId($data['id_carrier']);
        $command->setName($data['name']);
        $command->setPhone($data['phone']);
        $command->setEmail1($data['email1']);
        $command->setEmail2($data['email2']);

        $this->commandBus->handle($command);
    }
}
