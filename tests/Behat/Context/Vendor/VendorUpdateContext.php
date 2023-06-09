<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiVendorMarketplacePlugin\Behat\Context\Vendor;

use Behat\MinkExtension\Context\MinkContext;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Vendor;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorAddress;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorAddressUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdate;
use Doctrine\Persistence\ObjectManager;
use function PHPUnit\Framework\assertNotEquals;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Addressing\Model\Country;
use Sylius\Component\User\Repository\UserRepositoryInterface;

class VendorUpdateContext extends MinkContext
{
    private SharedStorageInterface $sharedStorage;

    private UserRepositoryInterface $userRepository;

    private ExampleFactoryInterface $userFactory;

    private ObjectManager $manager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        UserRepositoryInterface $userRepository,
        ExampleFactoryInterface $userFactory,
        ObjectManager $manager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->manager = $manager;
    }

    /**
     * @Given there is a vendor user :vendor_user_email registered in country :country_code
     */
    public function thereIsAVendorUserRegisteredInCountry($vendor_user_email, $country_code)
    {
        $user = $this->userFactory->create(['email' => $vendor_user_email, 'password' => 'password', 'enabled' => true]);

        $this->sharedStorage->set('user', $user);

        $this->userRepository->add($user);
        $customer = $this->sharedStorage->get('user')->getCustomer();
        $country = $this->manager->getRepository(Country::class)->findOneBy(['code' => $country_code]);
        $vendor = new Vendor();
        $vendor->setCompanyName('sdasdsa');
        $vendor->setCustomer($customer);
        $vendor->setPhoneNumber('333333333');
        $vendor->setTaxIdentifier('543455');
        $vendor->setVendorAddress(new VendorAddress());
        $vendor->getVendorAddress()->setCountry($country);
        $vendor->getVendorAddress()->setCity('Warsaw');
        $vendor->getVendorAddress()->setPostalCode('00-111');
        $vendor->getVendorAddress()->setStreet('Tajna 13');
        $this->manager->persist($vendor);
        $this->manager->flush();
        $this->sharedStorage->set('vendor', $vendor);
    }

    /**
     * @Then Pending update data should appear in database
     */
    public function pendingUpdateDataShouldAppearInDatabase()
    {
        $vendor = $this->sharedStorage->get('vendor');
        $pendingData = $this->manager->getRepository(VendorProfileUpdate::class)->findOneBy(['vendor' => $vendor]);

        assertNotEquals(null, $pendingData);
    }

    /**
     * @Given There is pending update data with token value :token for logged in vendor
     */
    public function thereIsPendingUpdateDataWithTokenValueForLoggedInVendor($token)
    {
        $vendor = $this->sharedStorage->get('vendor');
        $country = $this->manager->getRepository(Country::class)->findOneBy(['code' => 'PL']);
        $pendigUpdate = new VendorProfileUpdate();
        $pendigUpdate->setVendorAddress(new VendorAddressUpdate());
        $pendigUpdate->setVendor($vendor);
        $pendigUpdate->setToken($token);
        $pendigUpdate->setCompanyName('new Company');
        $pendigUpdate->setTaxIdentifier('new ID');
        $pendigUpdate->setPhoneNumber('new number');
        $pendigUpdate->getVendorAddress()->setStreet('new street');
        $pendigUpdate->getVendorAddress()->setCity('new city');
        $pendigUpdate->getVendorAddress()->setPostalCode('new code');
        $pendigUpdate->getVendorAddress()->setCountry($country);

        $this->manager->persist($pendigUpdate);
        $this->manager->flush();

        $this->sharedStorage->set('pendingUpdate', $pendigUpdate);
    }
}
