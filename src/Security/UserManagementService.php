<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Mailer\MailerServiceInterface;
use App\Model\PasswordReset;
use App\Repository\UserRepository;
use App\Service\PasswordManagementService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserManagementService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MailerServiceInterface
     */
    private $mailerService;

    private const RESET_REQUEST_TTL = 6000; // TODO env param
    /**
     * @var PasswordManagementService
     */
    private $passwordManagementService;

    public function __construct(EntityManagerInterface $entityManager, MailerServiceInterface $mailerService, PasswordManagementService $passwordManipulationService)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
        $this->passwordManagementService = $passwordManipulationService;
    }

    /**
     * @throws Exception
     */
    public function requestNewPassword(string $email): void
    {
        $user = $this->findUserByEmail($email);

        $this->passwordManagementService->setPaswordRequestedToken($user);

        if ($this->mailerService->sendPasswordResetEmailMessage($user)) {
            $this->entityManager->flush();

            return;
        }

        throw new Exception('Sending Password reset email message failed');
    }

    public function setNewPassword(PasswordReset $passwordReset): void
    {
        $user = $this->findUserByToken($passwordReset->getToken());

        $this->passwordManagementService->setNewPassword($user, $passwordReset->getPassword());

        $this->entityManager->flush();
    }

    public function findUserByEmail(string $email): User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            return $user;
        }
        throw new BadRequestHttpException('User not found by email');
    }

    public function findUserByToken(string $token): User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['passwordRequestToken' => $token]);

        if ($user instanceof User and !$this->isPasswordRequestTokenExpired($user)) {
            return $user;
        }
        throw new BadRequestHttpException('User not found by token or token expired');
    }

    public function isPasswordRequestTokenExpired(User $user): bool
    {
        return $user->getPasswordRequestedAt() instanceof DateTime and
            $user->getPasswordRequestedAt()->getTimestamp() + self::RESET_REQUEST_TTL < time();
    }
}
