<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp;

use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderInterface;

/**
 * @final
 */
class TotpAuthenticatorTwoFactorProvider implements TwoFactorProviderInterface
{
    public function __construct(private TotpAuthenticatorInterface $authenticator, private TwoFactorFormRendererInterface $formRenderer)
    {
    }

    public function beginAuthentication(AuthenticationContextInterface $context): bool
    {
        $user = $context->getUser();

        return $user instanceof TwoFactorInterface
            && $user->isTotpAuthenticationEnabled()
            && $user->getTotpAuthenticationConfiguration();
    }

    public function prepareAuthentication(mixed $user): void
    {
    }

    public function validateAuthenticationCode(mixed $user, string $authenticationCode): bool
    {
        if (!($user instanceof TwoFactorInterface)) {
            return false;
        }

        return $this->authenticator->checkCode($user, $authenticationCode);
    }

    public function getFormRenderer(): TwoFactorFormRendererInterface
    {
        return $this->formRenderer;
    }
}
