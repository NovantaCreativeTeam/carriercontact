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

namespace Novanta\CarrierContact\Form\Type;

use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use PrestaShopBundle\Form\Admin\Type\EmailType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class CarrierContactType extends TranslatorAwareType
{
    private FormChoiceProviderInterface $carrierChoiceProvider;

    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        FormChoiceProviderInterface $carrierChoiceProvider
    ) {
        parent::__construct($translator, $locales);
        $this->carrierChoiceProvider = $carrierChoiceProvider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_carrier', ChoiceType::class, [
                'label' => $this->trans('Carrier', 'Module.Carriercontact.Admin'),
                'help' => $this->trans('Choose a carrier associated with this contact', 'Module.Carriercontact.Admin'),
                'required' => false,
                'choices' => $this->carrierChoiceProvider->getChoices(),
            ])
            ->add('name', TextType::class, [
                'label' => $this->trans('Carrier', 'Module.Carriercontact.Admin'),
                'required' => true,
            ])
            ->add('email1', EmailType::class, [
                'label' => $this->trans('Email 1', 'Module.Carriercontact.Admin'),
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => $this->trans(
                            '%s is invalid.',
                            'Admin.Notifications.Error'
                        ),
                    ]),
                ],
            ])
            ->add('email2', EmailType::class, [
                'label' => $this->trans('Email 2', 'Module.Carriercontact.Admin'),
                'required' => false,

                'constraints' => [
                    new Email([
                        'message' => $this->trans(
                            '%s is invalid.',
                            'Admin.Notifications.Error'
                        ),
                    ]),
                ],
            ]);
    }
}
