<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Controller\Action\Admin\ProductListing;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductListing\ProductListingInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Repository\ProductListing\ProductListingRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShowAction
{
    private ProductListingRepositoryInterface $productListingRepository;

    private Environment $twig;

    public function __construct(
        ProductListingRepositoryInterface $productListingRepository,
        Environment $twig
    ) {
        $this->productListingRepository = $productListingRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        /** @var ProductListingInterface $productListing */
        $productListing = $this->productListingRepository->find($request->attributes->get('id'));

        return new Response(
            $this->twig->render('@BitBagSyliusMultiVendorMarketplacePlugin/Admin/ProductListing/show_product_listing.html.twig', [
                'productListing' => $productListing
            ])
        );
    }
}
