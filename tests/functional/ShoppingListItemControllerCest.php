<?php namespace App\Tests;
use Codeception\Util\HttpCode;

class ShoppingListItemControllerCest
{
    public function tryToGetShoppingItems(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/shopping/list/items');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateShoppingListItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'attributes' => [
                    'quantity' => 5,
                ],
                'relationships' => [
                    'groceryItem' => [
                        'data' => [
                            'type' => 'grocery_items',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/shopping/list/items/', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');

        $I->sendGET('/shopping/list/items/'.$responseData[0]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateShoppingListItemWithoutGroceryItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'attributes' => [
                    'quantity' => 10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/shopping/list/items/', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToEditShoppingListItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'attributes' => [
                    'quantity' => 5,
                ],
                'relationships' => [
                    'groceryItem' => [
                        'data' => [
                            'type' => 'grocery_items',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/shopping/list/items/', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');
        $createdId = $responseData[0];

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'id' => (string) $createdId,
                'attributes' => [
                    'quantity' => 4,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/shopping/list/items/'.$createdId, $params);
        $resp = $I->grabResponse();
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendGET('/shopping/list/items/'.$createdId);
        $responseData = $I->grabDataFromResponseByJsonPath('$.data..quantity');
        $I->assertEquals(4, $responseData[0]);
    }

    public function tryToEditQuantityAsNegativeNumber(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'attributes' => [
                    'quantity' => 5,
                ],
                'relationships' => [
                    'groceryItem' => [
                        'data' => [
                            'type' => 'grocery_items',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/shopping/list/items/', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');
        $createdId = $responseData[0];

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'id' => (string) $createdId,
                'attributes' => [
                    'quantity' => -100,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/shopping/list/items/'.$createdId, $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToDeleteShoppingListItem(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'shopping_list_items',
                'attributes' => [
                    'quantity' => 5,
                ],
                'relationships' => [
                    'groceryItem' => [
                        'data' => [
                            'type' => 'grocery_items',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/shopping/list/items/', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');
        $createdId = $responseData[0];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/shopping/list/items/'.$createdId);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
