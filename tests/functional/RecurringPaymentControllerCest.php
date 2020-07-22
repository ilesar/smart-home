<?php namespace App\Tests;
use Codeception\Util\HttpCode;

class RecurringPaymentControllerCest
{
    public function tryToGetRecurringPayments(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/recurring/payments');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateRecurringPayment(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'recurring_payments',
                'attributes' => [
                    'name' => 'Test name',
                    'price' => 100.10,
                    'activationTime' => '2020-07-23 21:30:32',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/recurring/payments', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $responseData = $I->grabDataFromResponseByJsonPath('$.data.id');

        $I->sendGET('/recurring/payments/'.$responseData[0]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToCreateRecurringPaymentWithoutName(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'recurring_payments',
                'attributes' => [
                    'price' => 100.10,
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/recurring/payments', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToCreateRecurringPaymentWithoutPrice(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'recurring_payments',
                'attributes' => [
                    'name' => 'Test name',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/recurring/payments', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToEditRecurringPaymentWithNoExpenses(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $params = [
            'data' => [
                'type' => 'recurring_payments',
                'id' => '1',
                'attributes' => [
                    'name' => 'Test name',
                ],
            ],
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPATCH('/recurring/payments/1', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendGET('/recurring/payments/1');
        $responseData = $I->grabDataFromResponseByJsonPath('$.data..name');
        $I->assertEquals('Test name', $responseData[0]);
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
        $I->sendPATCH('/recurring/payments/1', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function tryToDeleteRecurringPayment(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/recurring/payments/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function tryToDeleteRecurringPaymentWithAddedExpenses(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendDELETE('/recurring/payments/2');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
