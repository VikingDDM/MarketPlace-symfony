<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class MultiVendorMarketplaceMenuListener
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();

        $mvmRootMenuItem =
            $menu
                ->addChild('marketplace')
                ->setLabel('bitbag_sylius_multi_vendor_marketplace_plugin.ui.marketplace');

        $mvmRootMenuItem
            ->addChild('multi_vendor_marketplace_product_listings', [
                'route' => 'bitbag_sylius_multi_vendor_marketplace_plugin_admin_product_listing_index',
            ])
            ->setLabel('bitbag_sylius_multi_vendor_marketplace_plugin.ui.product_listings')
            ->setLabelAttribute('icon', 'list');

        $mvmRootMenuItem
            ->addChild('vendors', [
                'route' => 'app_admin_vendor_index',
            ])
            ->setLabel('bitbag_sylius_multi_vendor_marketplace_plugin.ui.vendors')
            ->setLabelAttribute('icon', 'users');
    }
}

