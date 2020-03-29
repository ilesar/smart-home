<?php namespace App\Tests;
use Codeception\Util\HttpCode;

class GroceryItemControllerCest
{
    // tests
    public function tryToGetGroceryItems(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/grocery/items');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
