<?php

namespace app\domain\pays\dtos;

class PaysRequest
{
    /**
     * @var \app\domain\common\dtos\DateTimeInterval
     */
    public $period;

    /**
     * @var array
     */
    public $status    = [];

    public $query     = '';

    public $limit     = 10;

    public $ascending = 1;

    public $page      = 0;

    public $byColumn  = 0;

    public $orderBy;

    public $date;

    public $multiSort = [];
}