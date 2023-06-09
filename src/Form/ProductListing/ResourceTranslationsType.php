<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Form\ProductListing;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductListing\ProductTranslationInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class ResourceTranslationsType extends AbstractType
{
    /** @var string[] */
    private $definedLocalesCodes;

    /** @var string */
    private $defaultLocaleCode;

    public function __construct(TranslationLocaleProviderInterface $localeProvider)
    {
        $this->definedLocalesCodes = $localeProvider->getDefinedLocalesCodes();
        $this->defaultLocaleCode = $localeProvider->getDefaultLocaleCode();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {

            /** @var ProductTranslationInterface[]|null[] $translations */
            $translations = $event->getData();

            $parentForm = $event->getForm()->getParent();
            Assert::notNull($parentForm);

            foreach ($translations as $localeCode => $translation) {
                if (null === $translation) {
                    unset($translations[$localeCode]);

                    continue;
                }

                $translation->setLocale($localeCode);
            }

            $event->setData($translations);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entries' => $this->definedLocalesCodes,
            'entry_name' => function (string $localeCode): string {
                return $localeCode;
            },
            'entry_options' => function (string $localeCode): array {
                return [
                    'required' => $localeCode === $this->defaultLocaleCode,
                ];
            },
        ]);
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }

}