<?php
use app\models\User;
use yii\helpers\Url;

class GameFormCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(User::findIdentity(3));
        $I->amOnPage(Url::to(['/games/create']));
    }

    public function openGameCreatePage(\FunctionalTester $I)
    {
        $I->see('Add Game', 'h1');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm('#game-form', []);
        $I->expectTo('see validations errors');
        $I->see('Add Game', 'h1');
        $I->see('Name cannot be blank');
    }

    public function submitFormWithIncorrectReleased(\FunctionalTester $I)
    {
        $I->submitForm('#game-form', [
            'Game[name]' => 'tester',
            'Game[released]' => '1234567812345678',
        ]);
        $I->expectTo('see that released format is wrong');
        $I->dontSee('Name cannot be blank', '.help-block');
        $I->see('The format of Released is invalid.');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#game-form', [
            'Game[name]' => 'tester',
            'Game[genre]' => 'RPG',
            'Game[released]' => '2017-08-08',
            'Game[developers]' => 'Tester Inc',
            'Game[platforms]' => [
                true,
                false,
                false,
                false,
                false,
                false,
                false,
                false,
            ],
        ]);
        $I->dontSeeElement('#game-form');
    }
}
