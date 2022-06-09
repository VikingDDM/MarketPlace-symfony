<?php

namespace spec\BitBag\SyliusMultiVendorMarketplacePlugin\Service;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Customer;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdateInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Service\RemoverInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Service\VendorProfileUpdateService;
use BitBag\SyliusMultiVendorMarketplacePlugin\Service\VendorProfileUpdateServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\User\Model\User;

class VendorProfileUpdateServiceSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager,
                 SenderInterface $sender,
                 RemoverInterface $remover)
    {
        $this->beConstructedWith($entityManager, $sender, $remover);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType(VendorProfileUpdateService::class);
    }    
}
