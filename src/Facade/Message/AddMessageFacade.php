<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Facade\Message;

use BitBag\SyliusMultiVendorMarketplacePlugin\Repository\Conversation\ConversationRepositoryInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Resolver\ActualUserResolverInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Uploader\FileUploaderInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\ConversationInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\MessageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class AddMessageFacade implements AddMessageFacadeInterface
{
    private ActualUserResolverInterface $actualUserResolver;

    private FileUploaderInterface $fileUploader;

    private ConversationRepositoryInterface $conversationRepository;

    public function __construct(
        ActualUserResolverInterface $actualUserResolver,
        FileUploaderInterface $fileUploader,
        ConversationRepositoryInterface $conversationRepository
    ) {
        $this->actualUserResolver = $actualUserResolver;
        $this->fileUploader = $fileUploader;
        $this->conversationRepository = $conversationRepository;
    }

    public function createWithConversation(
        int $conversationId,
        MessageInterface $message,
        ?UploadedFile $file = null,
        bool $stripTags = true
    ): void {
        $currentUser = $this->actualUserResolver->resolve();

        if (!$currentUser) {
            throw new UserNotFoundException();
        }

        /** @var ConversationInterface $conversation */
        $conversation = $this->conversationRepository->find($conversationId);

        if ($file) {
            $filename = $this->fileUploader->upload($file);
            $message->setFilename($filename);
        }

        if ($stripTags) {
            $message->setContent(strip_tags($message->getContent()));
        }

        $message->setAuthor($currentUser);

        $conversation->addMessage($message);
        $this->conversationRepository->add($conversation);
    }
}
