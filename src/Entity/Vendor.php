<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Vendor implements VendorDataInterface, VendorInterface, ResourceInterface
{
    public const STATUS_UNVERIFIED = 'unverified';

    public const STATUS_VERIFIED = 'verified';

    public const BLOCKED = 'blocked';

    public const UNBLOCKED = 'unblocked';

    private ?int $id;

    private CustomerInterface $customer;

    private ?string $companyName;

    private ?string $taxIdentifier;

    private ?string $phoneNumber;

    private ?VendorAddressInterface $vendorAddress;

    private string $status = self::STATUS_UNVERIFIED;

    private string $blocked = self::UNBLOCKED;

    private ?string $editDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function getTaxIdentifier(): ?string
    {
        return $this->taxIdentifier;
    }

    public function setTaxIdentifier(?string $taxIdentifier): void
    {
        $this->taxIdentifier = $taxIdentifier;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getVendorAddress(): ?VendorAddressInterface
    {
        return $this->vendorAddress;
    }

    public function setVendorAddress(?VendorAddressInterface $vendorAddress): void
    {
        $this->vendorAddress = $vendorAddress;
    }

    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getBlocked(): string
    {
        return $this->blocked;
    }

    public function setBlocked(string $blocked): void
    {
        $this->blocked = $blocked;
    }

    public function getEditDate(): ?string
    {
        return $this->editDate;
    }

    public function setEditDate(?string $editDate): void
    {
        $this->editDate = $editDate;
    }
}
