<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\EmailRequest;
use App\Model\PasswordReset;
use App\Security\UserManagementService;
use Paknahad\JsonApiBundle\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/passwords")
 */
class PasswordController extends Controller
{
    /**
     * @Route("/request-reset", name="passwords_request_reset", methods="POST")
     */
    public function requestReset(ValidatorInterface $validator, UserManagementService $userManagementService): ResponseInterface
    {
        $emailRequest = new EmailRequest();
        $emailRequest->setAttributes($this->jsonApi()->getRequest()->getResourceAttributes());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($emailRequest);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $userManagementService->requestNewPassword($emailRequest->getEmail());

        return $this->jsonApi()->respond()->genericSuccess(200);
    }

    /**
     * @Route("/reset", name="passwords_reset", methods="POST")
     */
    public function reset(ValidatorInterface $validator, UserManagementService $userManagementService): ResponseInterface
    {
        $passwordReset = new PasswordReset();
        $passwordReset->setAttributes($this->jsonApi()->getRequest()->getResourceAttributes());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($passwordReset);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $userManagementService->setNewPassword($passwordReset);

        return $this->jsonApi()->respond()->genericSuccess(200);
    }
}
