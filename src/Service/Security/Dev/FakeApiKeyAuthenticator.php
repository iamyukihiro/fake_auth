<?php
declare(strict_types=1);

namespace App\Service\Security\Dev;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class FakeApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('username');
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->headers->get('username');
        if (null === $username) {
            throw new CustomUserMessageAuthenticationException('HTTP ヘッダーに「Username」が含まれていません。');
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('HTTP ヘッダーの「Username」値から、該当するUserを検索しましたが、該当するUserが見つかりませんでした。');
        }

        $newFakeUserBadge = new FakeUserBadge('DUMMY_TOKEN');
        $newFakeUserBadge->setUser($user);
        return new FakePassport($newFakeUserBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
