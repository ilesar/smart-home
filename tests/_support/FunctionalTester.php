<?php

namespace App\Tests;

use App\Entity\User;
use Codeception\Actor;
use Codeception\Lib\Friend;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

/**
 * Inherited Methods.
 *
 * @method void   wantToTest($text)
 * @method void   wantTo($text)
 * @method void   execute($callable)
 * @method void   expectTo($prediction)
 * @method void   expect($prediction)
 * @method void   amGoingTo($argumentation)
 * @method void   am($role)
 * @method void   lookForwardTo($achieveValue)
 * @method void   comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends Actor
{
    use _generated\FunctionalTesterActions;

    public function amLoggedAsAdmin(): void
    {
        /** @var User $user */
        $user = $this->grabUserByEmail('ivan.lesar.pmf+smarthome@gmail.com');
        $token = $this->grabJWTManager()->create($user);
        $this->amBearerAuthenticated($token);
    }

    public function amLoggedAs(string $email): void
    {
        /** @var User $user */
        $user = $this->grabUserByEmail($email);
        $token = $this->grabJWTManager()->create($user);
        $this->amBearerAuthenticated($token);
    }

    public function grabUserByEmail(string $email)
    {
        return $this->grabEntityFromRepository(User::class, [
            'email' => $email,
        ]);
    }

    public function grabJWTManager(): JWTManager
    {
        return $this->grabService('lexik_jwt_authentication.jwt_manager');
    }

    public function grabResourceId(): string
    {
        $json = json_decode($this->grabResponse());

        return $json->data->id;
    }

    public function grabRelationshipId(string $relationName): string
    {
        $json = json_decode($this->grabResponse());

        return $json->data->relationships->$relationName->data->id;
    }

    /*
    * Define custom actions here
    */
}
