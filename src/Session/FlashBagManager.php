<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Session;

use NatePage\EasyAdminAddons\Twig\ValueObject\Alert;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Contracts\Service\ResetInterface;

final class FlashBagManager implements ResetInterface
{
    private const string FLASH_BAG_NAME = 'easyAdminAddonFlashes';

    private bool $initialized = false;

    public function __construct(
        private readonly RequestStack $requestStack,
    ){
    }

    public function addContentAlert(Alert $alert): void
    {
        $this->initialize();
        $this->getFlashBag()?->add('contentAlert', $alert);
    }

    public function getContentAlerts(): array
    {
        $this->initialize();

        return $this->getFlashBag()?->get('contentAlert') ?? [];
    }

    public function reset(): void
    {
        $this->initialized = false;
    }

    private function getFlashBag(): ?FlashBag
    {
        try {
            $session = $this->requestStack->getCurrentRequest()?->getSession();
        } catch (\RuntimeException) {
            return null;
        }

        /** @var FlashBag $flashBag */
        $flashBag = $session->getBag(self::FLASH_BAG_NAME);

        return $flashBag;
    }

    private function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest?->hasSession() ?? false) {
            $contentAlertsBag = new FlashBag(\sprintf('_%s', self::FLASH_BAG_NAME));
            $contentAlertsBag->setName(self::FLASH_BAG_NAME);

            $currentRequest->getSession()->registerBag($contentAlertsBag);
        }

        $this->initialized = true;
    }
}
