<?php

declare(strict_types=1);

namespace App\Validator;

use App\Model\LoginResource;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\Constraint;

class LoginValidator extends BaseValidator
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(UserProviderInterface $userProvider, EncoderFactoryInterface $encoderFactory)
    {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param LoginResource $login
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($login, Constraint $constraint)
    {
        try {
            $user = $this->userProvider->loadUserByUsername($login->getUsername());
        } catch (UsernameNotFoundException $exception) {
            $this->createUsernameIsCorrectConstraint();
            $this->createPasswordIsCorrectConstraint();

            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $userSalt = $user->getSalt() ?? '';

        if (null !== $user->getPassword() and !$encoder->isPasswordValid($user->getPassword(), $login->getPassword(), $userSalt)) {
            $this->createUsernameIsCorrectConstraint();
            $this->createPasswordIsCorrectConstraint();

            return;
        }
    }

    private function createPasswordIsCorrectConstraint(): void
    {
        $fieldName = 'password';
        $message = 'Invalid credentials.';

        $this->createConstraint($fieldName, $message);
    }

    private function createUsernameIsCorrectConstraint(): void
    {
        $fieldName = 'email';
        $message = 'Invalid credentials.';

        $this->createConstraint($fieldName, $message);
    }
}
