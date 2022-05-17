<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Form\Type\Conversation;


use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\Category;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\Conversation;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\ConversationInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Repository\VendorRepositoryInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Resolver\ActualUserResolverInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConversationType extends AbstractType
{
    private ActualUserResolverInterface $actualUserResolver;

    private VendorRepositoryInterface $vendorRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        ActualUserResolverInterface $actualUserResolver,
        VendorRepositoryInterface   $vendorRepository,
        UserRepositoryInterface     $userRepository
    ) {
        $this->actualUserResolver = $actualUserResolver;
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'mvm.ui.form.conversation.category',
                'choice_label' => 'name',
            ])
            ->add('messages', CollectionType::class, [
                'entry_type' => MessageType::class,
                'allow_add' => true,
            ])
            ->addEventListener(FormEvents::SUBMIT, [$this, 'onSubmit'])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData'])
            ;
    }

    public function postSetData(FormEvent $event): void
    {
        $user = $this->actualUserResolver->resolve();

        if ($user instanceof AdminUserInterface) {
            $form = $event->getForm();

            $form->add('users', ChoiceType::class, [
                'choices' => [
                    'Vendors' => $this->vendorRepository->findAll(),
                ],
                'choice_label' => 'username',
                'mapped' => false,
                'label' => 'mvm.ui.form.conversation.users',
            ]);

            $form->remove('category');
        }
    }

    public function onSubmit(FormEvent $event): void
    {
        /** @var ConversationInterface $conversation */
        $conversation = $event->getData();

        if ($event->getForm()->has('users')) {
            $conversation->setApplicant($event->getForm()->get('users')->getData());

            return;
        }

        $resolvedUser = $this->actualUserResolver->resolve();
        $conversation->setApplicant($resolvedUser);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Conversation::class);
    }

    public function getBlockPrefix(): string
    {
        return 'mvm_conversation';
    }
}