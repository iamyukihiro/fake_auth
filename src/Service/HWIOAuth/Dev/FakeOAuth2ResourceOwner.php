<?php
declare(strict_types=1);

namespace App\Service\HWIOAuth\Dev;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FakeOAuth2ResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected array $paths = [
        'username' => 'id',
    ];

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = []): string
    {
        return $_SERVER['SYMFONY_APPLICATION_DEFAULT_ROUTE_URL'].'login/fake-google-oauth';
    }

    /**
     * {@inheritdoc}
     */
    public function revokeToken($token): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'authorization_url' => 'https://example.com/o/oauth2/auth',
            'access_token_url' => 'https://example.com/o/oauth2/token',
            'revoke_token_url' => 'https://example.com/o/oauth2/revoke',
            'infos_url' => 'https://www.googleapis.com/oauth2/v1/userinfo', // Google OAuthをベースにしているので、どこかのタイミングでアクセスするらしいが、エラーが出ていても無視してログイン処理を続行させる。
            'scope' => null,
            'access_type' => null,
            'approval_prompt' => null,
            'display' => null,
            'hd' => null,
            'login_hint' => null,
            'prompt' => null,
            'request_visible_actions' => null,
        ]);

        $resolver
            ->setAllowedValues('access_type', ['online', 'offline', null])
            ->setAllowedValues('approval_prompt', ['force', 'auto', null])
            ->setAllowedValues('display', ['page', 'popup', 'touch', 'wap', null])
            ->setAllowedValues('login_hint', ['email address', 'sub', null])
            ->setAllowedValues('prompt', ['consent', 'select_account', null])
        ;
    }

    public function handles(HttpRequest $request): bool
    {
        return true;
    }


    /**
     * @param HttpRequest $request
     * @param mixed $redirectUri
     * @param array $extraParameters
     * @return string[]
     */
    public function getAccessToken(HttpRequest $request, mixed $redirectUri, array $extraParameters = [])
    {
        return [
            'username' => $request->query->get('username'),
            'access_token' => 'DUMMY_ACCESS_TOKEN'
        ];
    }
}