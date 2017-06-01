<?php
use yii\helpers\Url;

class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(Url::to(['/user/security/login']));
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Sign in', 'h3');
    }

    // // demonstrates `amLoggedInAs` method
    // public function internalLoginById(\FunctionalTester $I)
    // {
    //     $I->amLoggedInAs(1);
    //     $I->amOnPage('/');
    //     $I->see('Logout');
    // }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Login cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'login-form[login]' => 'admin',
            'login-form[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Invalid login or password');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'login-form[login]' => 'Jomari',
            'login-form[password]' => 'jomari',
        ]);
        $I->see('Logout');
        $I->dontSeeElement('form#login-form');
    }
}
