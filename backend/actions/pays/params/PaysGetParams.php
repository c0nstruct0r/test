<?php

namespace backend\actions\pays\params;

use app\domain\common\controllers\Params;
use app\domain\common\utils\DateTimes;
use app\domain\common\utils\Strings;
use app\domain\pays\repositories\ars\PayAr;
use app\domain\pays\services\dtos\PaysRequest;

class PaysGetParams
    extends Params
{
    public $query = '';

    public $limit = 10;

    public $ascending = 1;

    public $page = 0;

    public $byColumn = 0;

    public $orderBy;
    public $isLinked;
    public $type;

    public $date;

    private $multiSort = [];

    public static function fromGetToOrdersRequest(array $get): PaysRequest
    {
        $param = new self();
        $param->load($get);

        $paysRequest = new PaysRequest();
        $paysRequest->query = $param->query;
        $paysRequest->limit = $param->limit;
        $paysRequest->ascending = is_null($param->orderBy) ? false : (bool)$param->ascending;
        $paysRequest->page = $param->page;
        $paysRequest->byColumn = $param->byColumn;
        $paysRequest->isLinked = Strings::fromTrueFalseBool($param->isLinked);
        $paysRequest->type = Strings::fromTrueFalseBool($param->type);
        $paysRequest->orderBy = $param->orderBy ?? PayAr::OP_DATE_PROCESSED;
        $paysRequest->date = DateTimes::fromString($param->date);
        $paysRequest->multiSort = $param->multiSort;

        return $paysRequest;
    }

    /**
     * @param string|null $status
     *
     * @return array
     */
    private static function getStatuses(?string $status): array
    {
        $result = is_array(explode(',', $status)) ? explode(',', $status) : $status;
        $result = array_diff($result, ['']);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['report', 'isLinked'], 'boolean'],
            [['clientId', 'limit', 'ascending', 'page'], 'integer'],
            [
                ['date', 'isPaid', 'multiSort', 'orderBy', 'payType', 'payId', 'query', 'status', 'withoutClient',
                 'type'
                ],
                'string',
            ],
        ];
    }
}
