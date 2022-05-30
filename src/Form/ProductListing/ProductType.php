<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Form\ProductListing;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
                'disabled' => ($builder->getData()->getCode())
                ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ProductTranslationType::class,
                'label' => 'sylius.form.product.translations',
                'attr' => [
                    'class' => 'ui styled fluid accordion'
                    ]
            ])
            ->add('save', SubmitType::class,[
                'attr' => [
                    'class' => 'ui labeled icon primary button'
                ]
            ])
            ->add('saveAndAdd', SubmitType::class,[
                'attr' => [
                    'class' => 'ui labeled icon secondary button'
                ]
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $productDraft = $event->getData();

            $event->getForm()->add('productListingPrice', ChannelCollectionType::class, [
                'entry_type' => ProductPriceType::class,
                'entry_options' => fn(ChannelInterface $channel) => [
                    'channel' => $channel,
                    'product_draft' => $productDraft,
                    'required' => false,
                ],
                'label' => 'sylius.form.variant.price',
                'disabled' => ($event->getData()->getCode())
            ]);
        });
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_product';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
        ]);
    }
}
