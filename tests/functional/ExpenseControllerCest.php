<?php namespace App\Tests;
use Codeception\Util\HttpCode;

class ExpenseControllerCest
{
    public function tryToGetExpenses(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/expenses/');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateExpense(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'expenses',
                'attributes' => [
                    'dueDate' => '2020-03-30 11:00:00',
                ],
                'relationships' => [
                    'recurringPayment' => [
                        'data' => [
                            'type' => 'recurring_payments',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/expenses/', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');

        $I->sendGET('/expenses/'.$responseData[0]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateExpenseWithoutRecurringPayment(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'expenses',
                'attributes' => [
                    'dueDate' => '2020-03-30 11:00:00',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/expenses/', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToCreateExpenseWithoutDueDate(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'expenses',
                'attributes' => [
                ],
                'relationships' => [
                    'recurringPayment' => [
                        'data' => [
                            'type' => 'recurring_payments',
                            'id' => '1',
                        ],
                    ],
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/expenses/', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToEditExpense(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'expenses',
                'id' => '1',
                'attributes' => [
                    'dueDate' => '2020-03-30 11:00:00',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/expenses/1', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToEditPriceAsNegativeNumber(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'recurring_payments',
                'id' => '1',
                'attributes' => [
                    'price' => -100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/expenses/1', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToDeleteRecurringPayment(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/expenses/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function tryToDeleteRecurringPaymentWithAddedExpenses(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/expenses/2');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
}
