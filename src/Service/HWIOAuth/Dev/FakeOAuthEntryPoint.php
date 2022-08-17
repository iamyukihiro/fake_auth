<?php
declare(strict_types=1);

namespace App\Service\HWIOAuth\Dev;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class FakeOAuthEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly string $loginPath,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if ($this->isBrowserAccess($request)) {
            return new RedirectResponse($this->loginPath);
        }

        return new Response('認証されていない Http Request です。ブラウザ以外からのHttp Requestには、「Username」ヘッダーが必要です', Response::HTTP_UNAUTHORIZED);
    }

    private function isBrowserAccess(Request $request): bool
    {
        return is_null($request->headers->get('username'));
    }
}