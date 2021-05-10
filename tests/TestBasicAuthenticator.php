<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class TestBasicAuthenticator extends AbstractGuardAuthenticator
{
    /** {@inheritdoc} */
    public function supports(Request $request): bool
    {
        return $request->headers->has('PHP_AUTH_USER') || $request->cookies->has('test_auth');
    }

    /** {@inheritdoc} */
    public function getCredentials(Request $request)
    {
        return $request->headers->has('PHP_AUTH_USER')
            ? $request->headers->get('PHP_AUTH_USER')
            : $request->cookies->get('test_auth');
    }

    /** {@inheritdoc} */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        return $userProvider->loadUserByUsername($credentials);
    }

    /** {@inheritdoc} */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /** {@inheritdoc} */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    /** {@inheritdoc} */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response(null, 401);
    }

    /** {@inheritdoc} */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response(null, 403);
    }

    /** {@inheritdoc}*/
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
