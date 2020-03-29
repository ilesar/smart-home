<?php

namespace App\JsonApi\Hydrator\User;

use App\Entity\User;

/**
 * Create User Hydrator.
 */
class CreateUserHydrator extends AbstractUserHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($user): array
    {
        return [
            'email' => function (User $user, $attribute, $data, $attributeName) {
                $user->setEmail($attribute);
            },
            'roles' => function (User $user, $attribute, $data, $attributeName) {
                $user->setRoles($attribute);
            },
            'password' => function (User $user, $attribute, $data, $attributeName) {
                $user->setPassword($attribute);
            },
            'isEnabled' => function (User $user, $attribute, $data, $attributeName) {
                $user->setIsEnabled($attribute);
            },
            'passwordRequestToken' => function (User $user, $attribute, $data, $attributeName) {
                $user->setPasswordRequestToken($attribute);
            },
            'passwordRequestedAt' => function (User $user, $attribute, $data, $attributeName) {
                $user->setPasswordRequestedAt(new \DateTime($attribute));
            },
        ];
    }
}
