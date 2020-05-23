<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use Codeception\Util\HttpCode;
use common\fixtures\OrderFixture;

class ApiPaysCest
{
    public function _fixtures()
    {
        return [
            'fixture' => OrderFixture::class,
        ];
    }

    public function index(FunctionalTester $I)
    {
        $I->amLoggedInAs(['username' => 'root']);
        $I->sendAjaxGetRequest('api/pays');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(
            [
                'result' => 'success',
                'count'  => 8,
                'data'   => [
                    0 => [
                        'id'     => 1,
                        'cost'   => 1200,
                        'opLink' => 'order',
                    ],
                    7 => [
                        'id'     => 8,
                        'cost'   => -700,
                        'opLink' => 'salary',
                    ],
                ],
            ]
        );
    }

    public function filterByTypePlus(FunctionalTester $I)
    {
        $I->amLoggedInAs(['username' => 'root']);
        $I->sendAjaxGetRequest('api/pays', ['type' => 'true']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(
            [
                'result' => 'success',
                'count'  => 7,
                'data'   => [
                    0 => [
                        'id'     => 1,
                        'cost'   => 1200,
                        'opLink' => 'order',
                    ],
                ],
            ]
        );
    }

    public function filterByTypeMinus(FunctionalTester $I)
    {
        $I->amLoggedInAs(['username' => 'root']);
        $I->sendAjaxGetRequest('api/pays', ['type' => 'false']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(
            [
                'result' => 'success',
                'count'  => 1,
                'data'   => [
                    0 => [
                        'id'     => 8,
                        'cost'   => -700,
                        'opLink' => 'salary',
                    ],
                ],
            ]
        );
    }
}
