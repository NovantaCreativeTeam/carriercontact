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

namespace Novanta\CarrierContact\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\LinkGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\DeleteActionTrait;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\EmailType;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CarrierContactDefinitionFactory extends AbstractGridDefinitionFactory
{
    use DeleteActionTrait;

    const GRID_ID = 'carrierContacts';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName(): string
    {
        return $this->trans('Carrier Contacts', [], 'Modules.CarrierContact.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new IdentifierColumn('id_carrier_contact'))
                ->setName($this->trans('ID', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'identifier_field' => 'id_carrier_contact',
                    'bulk_field' => 'id_carrier_contact',
                    'with_bulk_field' => true,
                ])
            )
            ->add((new DataColumn('carrier_name'))
                ->setName($this->trans('Carrier', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'field' => 'carrier_name',
                ])
            )
            ->add((new DataColumn('name'))
                ->setName($this->trans('Name', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'field' => 'name',
                ])
            )
            ->add((new DataColumn('email1'))
                ->setName($this->trans('Email 1', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'field' => 'email1',
                ])
            )
            ->add((new DataColumn('email2'))
                ->setName($this->trans('Email 2', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'field' => 'email2',
                ])
            )
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => $this->getRowActions(),
                ])
            );
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_carrier_contact', IntegerType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('id_carrier_contact')
            )
            ->add(
                (new Filter('name', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('name')
            )
            ->add(
                (new Filter('Email1', EmailType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('email1')
            )
            ->add(
                (new Filter('Email2', EmailType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('email2')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'admin_carrier_contact_index',
                    ])
                    ->setAssociatedColumn('actions')
            );
    }

    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new LinkGridAction('add_new_carrier_contact'))
                ->setName($this->trans('Add Carrier contact', [], 'Modules.CarrierContact.Admin'))
                ->setOptions([
                    'route' => 'admin_carrier_contact_new',
                ])
                ->setIcon('add')
            );
    }

    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add((new LinkRowAction('edit'))
                ->setIcon('edit')
                ->setOptions([
                    'route' => 'admin_carrier_contact_edit',
                    'route_param_name' => 'carrierContactId',
                    'route_param_field' => 'id_carrier_contact',
                ])
            )
            ->add(
                $this->buildDeleteAction(
                    'admin_carrier_contact_remove',
                    'carrierContactId',
                    'id_carrier_contact',
                    Request::METHOD_DELETE
                )
            );
    }
}
