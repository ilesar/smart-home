<?php namespace App\Tests;
use Codeception\Util\HttpCode;

class GroceryItemControllerCest
{
    public function tryToGetGroceryItems(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/grocery/items');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateGroceryItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'attributes' => [
                    'name' => 'Test name',
                    'source' => 'test',
                    'price' => 100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/grocery/items', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');

        $I->sendGET('/grocery/items/'.$responseData[0]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateGroceryItemWithoutName(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'attributes' => [
                    'source' => 'test',
                    'price' => 100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/grocery/items', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToCreateGroceryItemWithoutPrice(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'attributes' => [
                    'name' => 'Test name',
                    'source' => 'test',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/grocery/items', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToCreateGroceryItemWithoutSource(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'attributes' => [
                    'name' => 'Test name',
                    'price' => 100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/grocery/items', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToEditGroceryItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'id' => '1',
                'attributes' => [
                    'description' => 'Test description',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/grocery/items/1', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendGET('/grocery/items/1');
        $responseData = $I->grabDataFromResponseByJsonPath('$.data..description');
        $I->assertEquals('Test description', $responseData[0]);
    }

    public function tryToEditPriceAsNegativeNumber(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'grocery_items',
                'id' => '1',
                'attributes' => [
                    'price' => -100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/grocery/items/1', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToDeleteGroceryItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/grocery/items/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
