<?php
declare(strict_types=1);

namespace App\Service\HWIOAuth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use LogicException;
use PHPMentors\DomainCommons\DateTime\Clock;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FailSafeEntityUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    public function __construct(
        /** @var EntityUserProvider $delegate */
        private readonly OAuthAwareUserProviderInterface $delegate,
        private readonly EntityManagerInterface $em,
        private ?Clock $clock = null,
    ) {
        $this->clock = $clock ?? new Clock();
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): User
    {
        try {
            try {
                /** @var User $user */
                $user = $this->delegate->loadUserByOAuthUserResponse($response);
                $user->setLastLoggedInAt($this->clock->now());
            } catch (UserNotFoundException $e) {
                $user = (new User());
                $user
                    ->setUsername($response->getUsername())
                    ->setFullName($response->getRealName())
                    ->setLastLoggedInAt($this->clock->now())
                ;
            }

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        } catch (Exception $exception) {
            throw new LogicException('unreachable', 0, $exception);
        }
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->delegate->loadUserByIdentifier($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->delegate->refreshUser($user);
    }

    public function supportsClass(string $class): bool
    {
        return $this->delegate->supportsClass($class);
    }
}
