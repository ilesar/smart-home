<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\JsonApi\Document\JWT\JWTDocument;
use App\JsonApi\Transformer\JwtResourceTransformer;
use App\Model\LoginResource;
use App\Security\JsonWebTokenGenerator;
use Paknahad\JsonApiBundle\Controller\Controller;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * @Route("/auth")
 */
class LoginController extends Controller
{
    /**
     * @var JsonWebTokenGenerator
     */
    private $tokenGenerator;

    public function __construct(JsonApi $jsonApi, JsonWebTokenGenerator $tokenGenerator)
    {
        parent::__construct($jsonApi);
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @Route("/login", name="api_login", methods="POST")
     */
    public function login(JwtResourceTransformer $jwtResourceTransformer, ValidatorInterface $validator): ResponseInterface
    {
        $login = new LoginResource();
        $login->setAttributes($this->jsonApi()->getRequest()->getResourceAttributes());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($login);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }
        $jsonWebToken = $this->tokenGenerator->generateLoginTokenForUsername($login->getUsername());

        return $this->jsonApi()->respond()->ok(
            new JWTDocument($jwtResourceTransformer),
            $jsonWebToken
        );
    }

    /**
     * @Route("/refresh-token", name="api_auth_refresh_token", methods="POST")
     */
    public function refreshToken(JwtResourceTransformer $jwtResourceTransformer): ResponseInterface
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        $jsonWebToken = $this->tokenGenerator->generateTokenForUser($user);

        return $this->jsonApi()->respond()->ok(
            new JWTDocument($jwtResourceTransformer),
            $jsonWebToken
        );
    }
}
