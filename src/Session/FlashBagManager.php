<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Session;

use NatePage\EasyAdminAddons\Twig\ValueObject\Alert;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

final class FlashBagManager
{
    public const string FLASH_BAG_NAME = 'easyAdminAddonFlashes';

    public function __construct(
        private readonly RequestStack $requestStack,
    ){
    }

    public function addContentAlert(Alert $alert): void
    {
        $this->getFlashBag()?->add('contentAlert', $alert);
    }

    public function getContentAlerts(): array
    {
        return $this->getFlashBag()?->get('contentAlert') ?? [];
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
}
