<?php

namespace app\domain\pays\models;

class Pay
{
    /* @var int $id */
    public $id;

    /* @var float $cost */
    public $cost;

    /* @var \DateTime $op_date_processed */
    public $op_date_processed;

    /* @var string $opLink */
    public $opLink;

    /* @var int $orderId */
    public $orderId;

    /* @var int $payerId */
    public $payerId;

    /* @var string $payer */
    public $payer;

    /* @var string $line */
    public $line;

    /* @var string $comment */
    public $comment;

    /* @var \DateTime $created */
    public $created;

}