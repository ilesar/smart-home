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

    public function tryToGetSingleExpense(FunctionalTester $I)
    {
        $I->amLoggedAsAdmin();

        $I->sendGET('/expenses/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
