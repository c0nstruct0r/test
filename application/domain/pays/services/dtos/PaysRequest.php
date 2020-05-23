<?php

namespace app\domain\pays\services\dtos;

class PaysRequest
{
    /** @var \DateInterval */
    public $dateInterval;

    /** @var integer */
    public $page;

    /** @var string */
    public $orderBy;

    /** @var string */
    public $query;

    /** @var integer */
    public $byColumn;

    /** @var integer */
    public $limit;

    /** @var bool */
    public $ascending;

    /** @var bool */
    public $isLinked;

    /** @var bool */
    public $type;
}