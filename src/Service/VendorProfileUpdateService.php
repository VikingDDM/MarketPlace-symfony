<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Service;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorAddressUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorDataInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdateInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class VendorProfileUpdateService implements VendorProfileUpdateServiceInterface
{
    private EntityManagerInterface $entityManager;

    private SenderInterface $sender;     

    public function __construct(       
        EntityManagerInterface $entityManager,
        SenderInterface $sender,        
    ) {       
        $this->entityManager = $entityManager;
        $this->sender = $sender;       
    }

    public function createPendingVendorProfileUpdate(VendorInterface $vendorData, VendorInterface $currentVendor): void
    {
        $pendingVendorUpdate = new VendorProfileUpdate();    
        $pendingVendorUpdate->setVendorAddress(new VendorAddressUpdate());
        $pendingVendorUpdate->setVendor($currentVendor);
        $token = md5(mt_rand(1, 90000) . 'SALT');
        $pendingVendorUpdate->setToken($token);        
        $this->setVendorFromData($pendingVendorUpdate, $vendorData);
        $user = $currentVendor->getCustomer()->getUser();
        if (null == $user) {
            return;
        }
        $this->sendEmail($user->getUsername(), $token);
        
    }

    private function setVendorFromData(VendorDataInterface $vendor, VendorDataInterface $data): void
    {
        $vendor->setCompanyName($data->getCompanyName());
        $vendor->setTaxIdentifier($data->getTaxIdentifier());
        $vendor->setPhoneNumber($data->getPhoneNumber());
        $newVendorAddress = $data->getVendorAddress();
//        dd($vendor, $data);
        if (null == $newVendorAddress) {
            return;
        }    
        $vendor->getVendorAddress()->setCity($newVendorAddress->getCity());
        $vendor->getVendorAddress()->setCountry($newVendorAddress->getCountry());
        $vendor->getVendorAddress()->setPostalCode($newVendorAddress->getPostalCode());
        $vendor->getVendorAddress()->setStreet($newVendorAddress->getStreet());
       
        $this->entityManager->persist($vendor);
        $this->entityManager->flush();
    }

    public function sendEmail(string $recipientAddress, string $token): void
    {
        $this->sender->send('vendor_profile_update', [$recipientAddress], ['token' => $token]);
    }

    public function updateVendorFromPendingData(VendorProfileUpdateInterface $vendorData): void
    {
        $vendor = $vendorData->getVendor();
        if (null == $vendor) {
            return;
        }
        $this->setVendorFromData($vendor, $vendorData);
        $this->deletePendingData($vendorData);
    }    

    private function deletePendingData(VendorProfileUpdateInterface $vendorData): void
    {   
        $pendingAddressChange = $vendorData->getVendorAddress();
//        dd($vendorData);
        $this->entityManager->remove($pendingAddressChange);
        $this->entityManager->remove($vendorData);
        $this->entityManager->flush();
    }
}