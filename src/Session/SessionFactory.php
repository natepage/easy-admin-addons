<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final readonly class SessionFactory implements SessionFactoryInterface
{
    public function __construct(
        private SessionFactoryInterface $decorated,
    ) {
    }

    public function createSession(): SessionInterface
    {
        $session = $this->decorated->createSession();

        $contentAlertsBag = new FlashBag(\sprintf('_%s', FlashBagManager::FLASH_BAG_NAME));
        $contentAlertsBag->setName(FlashBagManager::FLASH_BAG_NAME);

        $session->registerBag($contentAlertsBag);

        return $session;
    }
}
