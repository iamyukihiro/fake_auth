<?php
declare(strict_types=1);

namespace App\Service\Security\Dev;

use LogicException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FakePassport extends SelfValidatingPassport
{
    public function getUser(): UserInterface
    {
        if (null === $this->user) {
            if (!$this->hasBadge(FakeUserBadge::class)) {
                throw new LogicException('このパスポートに FakeUserBadge が追加されていないため、ログインユーザーを取得できません。');
            }

            /** @var FakeUserBadge $fakeUserBadge */
            $fakeUserBadge = $this->getBadge(FakeUserBadge::class);
            $this->user = $fakeUserBadge->getUser();
        }

        return $this->user;
    }
}