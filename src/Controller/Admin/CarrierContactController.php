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

namespace Novanta\CarrierContact\Controller\Admin;

use Novanta\CarrierContact\Domain\CarrierContact\Command\DeleteCarrierContact;
use Novanta\CarrierContact\Domain\CarrierContact\Exception\CarrierContactNotFoundException;
use Novanta\CarrierContact\Grid\Definition\Factory\CarrierContactDefinitionFactory;
use Novanta\CarrierContact\Search\Filters\CarrierContactFilters;
use Novanta\Quote\Grid\Definition\Factory\QuoteDefinitionFactory;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarrierContactController extends FrameworkBundleAdminController
{

    public function indexAction(Request $request, CarrierContactFilters $filters) {
        $carrierContactGridFactory = $this->get('novanta.carriercontact.grid.carrier_contact_grid_factory');
        $carrierContactGrid = $carrierContactGridFactory->getGrid($filters);

        $toolbarButtons = [
            'clear_cache' => [
                'href' => $this->generateUrl('admin_carrier_contact_new'),
                'desc' => $this->trans('New Contact', 'Modules.Carriercontact.Admin'),
                'icon' => 'add',
            ],
        ];

        return $this->render(
            '@Modules/carriercontact/views/templates/admin/CarrierContact/index.html.twig',
            [
                'layoutTitle' => $this->trans('Carrier Contacts', 'Modules.Carriercontact.Admin'),
                'layoutHeaderToolbarBtn' => $toolbarButtons,
                'carrierContactGrid' => $this->presentGrid($carrierContactGrid),
            ]
        );
    }

    /**
     * Funzione che si occupa di gestire l'azione di ricerca della grid
     *
     * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('novanta.carriercontact.grid.definition.factory.carrier_contact'),
            $request,
            CarrierContactDefinitionFactory::GRID_ID,
            'admin_carrier_contact_index'
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $carrierContactFormBuilder = $this->get('novanta.carriercontact.form.identifiable_object.builder.carrier_contact_form_builder');
        $carrierContactForm = $carrierContactFormBuilder->getForm();

        $carrierContactForm->handleRequest($request);

        $carrierContactFormHandler = $this->get('novanta.carriercontact.form.identifiable_object.handler.carrier_contact_form_handler');
        $result = $carrierContactFormHandler->handle($carrierContactForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash('success', $this->trans('Carrier contact created successfully.', 'Modules.Carriercontact.Notifications'));

            return $this->redirectToRoute('admin_carrier_contact_index');
        }

        return $this->render('@Modules/carriercontact/views/templates/admin/CarrierContact/create.html.twig', [
            'carrierContactForm' => $carrierContactForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $carrierContactId
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, int $carrierContactId)
    {
        $carrierContactFormBuilder = $this->get('novanta.carriercontact.form.identifiable_object.builder.carrier_contact_form_builder');
        $carrierContactForm = $carrierContactFormBuilder->getFormFor($carrierContactId);

        $carrierContactForm->handleRequest($request);

        $carrierContactFormHandler = $this->get('novanta.carriercontact.form.identifiable_object.handler.carrier_contact_form_handler');
        $result = $carrierContactFormHandler->handleFor($carrierContactId, $carrierContactForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash('success', $this->trans('Carrier contact updated successfully.', 'Modules.Carriercontact.Notifications'));

            return $this->redirectToRoute('admin_carrier_contact_index');
        }

        return $this->render('@Modules/carriercontact/views/templates/admin/CarrierContact/update.html.twig', [
            'carrierContactForm' => $carrierContactForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $carrierContactId
     *
     * @return Response
     */
    public function removeAction(Request $request, int $carrierContactId): Response
    {
        try {
            $this->getCommandBus()->handle(new DeleteCarrierContact($carrierContactId));
            $this->addFlash('success', $this->trans('Carrier contact deleted correctly!', 'Modules.Carriercontact.Admin'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages($e)));
        }

        return $this->redirectToRoute('admin_carrier_contact_index');
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    private function getErrorMessages(\Exception $e)
    {
        return [
            CarrierContactNotFoundException::class => $this->trans('Carrier contact cannot be loaded (or found)', 'Modules.Carriercontact.Notifications'),
        ];
    }
}
