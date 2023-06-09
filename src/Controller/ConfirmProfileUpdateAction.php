<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Controller;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Security\Voter\TokenOwningVoter;
use BitBag\SyliusMultiVendorMarketplacePlugin\Service\VendorProfileUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

final class ConfirmProfileUpdateAction
{
    private EntityManagerInterface $entityManager;

    private VendorProfileUpdateService $vendorProfileUpdateService;

    private Security $security;

    private Router $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        VendorProfileUpdateService $vendorProfileUpdateService,
        Security $security,
        Router $router
    ) {
        $this->entityManager = $entityManager;
        $this->vendorProfileUpdateService = $vendorProfileUpdateService;
        $this->security = $security;
        $this->router = $router;
    }

    public function __invoke(string $token): Response
    {
        $vendorProfileUpdateData = $this->entityManager->getRepository(VendorProfileUpdate::class)->findOneBy(['token' => $token]);
        $profileRoot = $this->router->generate('vendor_profile');
        $vendorIsGranted = $this->security->isGranted(TokenOwningVoter::UPDATE, $vendorProfileUpdateData);
        if ($vendorIsGranted && null !== $vendorProfileUpdateData) {
            $this->vendorProfileUpdateService->updateVendorFromPendingData($vendorProfileUpdateData);
        }

        return new RedirectResponse($profileRoot);
    }
}
