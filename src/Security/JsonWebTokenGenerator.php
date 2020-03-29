<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Model\JsonWebToken;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JsonWebTokenGenerator
{
    /**
     * @var int
     */
    private $ttl;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    public function __construct(JWTTokenManagerInterface $tokenManager, UserProviderInterface $userProvider, int $ttl)
    {
        $this->tokenManager = $tokenManager;
        $this->ttl = $ttl;
        $this->userProvider = $userProvider;
    }

    public function generateTokenForUser(User $user): JsonWebToken
    {
        $rawToken = $this->tokenManager->create($user);

        $expiration = $this->calculateTokenExpiresAt();

        return new JsonWebToken($user, $expiration, $rawToken);
    }

    public function generateLoginTokenForUsername(string $username): JsonWebToken
    {
        $user = $this->userProvider->loadUserByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        return $this->generateTokenForUser($user);
    }

    private function calculateTokenExpiresAt(): DateTime
    {
        $modificationString = sprintf('+ %d seconds', $this->ttl);

        return (new DateTime())->modify($modificationString);
    }
}
