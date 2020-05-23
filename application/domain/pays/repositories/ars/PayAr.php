<?php

namespace app\domain\pays\repositories\ars;

use app\domain\common\persistence\ActiveRecordMapper;
use app\domain\common\persistence\BaseAr;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%bops}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $card
 * @property string  $op_link
 * @property string  $op_date
 * @property string  $op_date_processed
 * @property integer $op_num
 * @property string  $operation
 * @property string  $cost
 * @property string  $op_type
 * @property string  $line
 * @property string  $comment
 * @property integer $created_at
 * @property integer $updated_at
 */
class PayAr
    extends BaseAr
{
    public const PAY_LINK_ADVERTISING = 'advertising';
    public const PAY_LINK_RENT = 'rent';
    public const PAY_LINK_CASH = 'cash';
    public const PAY_LINK_PROFIT = 'profit';
    public const PAY_LINK_PURCHASES = 'purchases';
    public const PAY_LINK_ORDER = 'order';
    public const PAY_LINK_SALARY = 'salary';
    public const PAY_LINK_TRANSFER = 'transfer';
    public const PAY_LINK_TRANSPORT = 'транспорт';

    const        ID = 'id';
    const        COST = 'cost';
    const        OP_DATE_PROCESSED = 'op_date_processed';
    const        OP_LINK = 'op_link';
    const        ORDER_ID = 'order_id';
    const        CARD = 'card';
    const        PAYER_ID = 'payer_id';
    const        PAYER = 'payer';

    const        MAPPING = [
        self::ID                => 'id',
        self::COST              => ['cost', ActiveRecordMapper::FLOAT],
        self::OP_DATE_PROCESSED => 'op_date_processed',
        self::OP_LINK           => 'opLink',
        self::ORDER_ID          => 'orderId',
        self::CARD              => 'card',
        self::PAYER_ID          => 'payerId',
        self::PAYER             => 'payer',
        'line'                  => 'line',
        'comment'               => 'comment',
        'created_at'            => ['created', ActiveRecordMapper::INT],
    ];

    private static $payLinks = [
        self::PAY_LINK_ADVERTISING => 'Реклама',
        self::PAY_LINK_RENT        => 'Аренда',
        self::PAY_LINK_PROFIT      => 'Прибыль',
        self::PAY_LINK_CASH        => 'Наличные',
        self::PAY_LINK_PURCHASES   => 'Покупки',
        self::PAY_LINK_TRANSPORT   => 'Транспорт',
        self::PAY_LINK_ORDER       => 'Заказ',
        self::PAY_LINK_SALARY      => 'Зарплата',
        self::PAY_LINK_TRANSFER    => 'Перевод',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bops}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('backend', 'ID'),
            'cost'              => Yii::t('backend', 'Сумма'),
            'card'              => Yii::t('backend', 'Карта'),
            'op_link'           => Yii::t('backend', 'Тип'),
            'op_date_processed' => Yii::t('backend', 'Дата операции'),
            'order_id'          => Yii::t('backend', 'Заказ'),
            'comment'           => Yii::t('backend', 'Коментарий'),
            'created_at'        => Yii::t('backend', 'Дата создания'),
            'updated_at'        => Yii::t('backend', 'Дата обновления'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card', 'op_date_processed', 'cost'], 'required'],
            [['card', 'op_num', 'op_date_processed', 'cost', 'op_link', 'comment'], 'safe'],
            [['created_at', 'updated_at', 'op_num'], 'integer'],
            [['line', 'comment', 'card',], 'string'],
        ];
    }

    public function scenarios()
    {
        return [
            'default' => [
                'card',
                'op_link',
                'op_num',
                'op_date',
                'op_date_processed',
                'cost',
                'comment',
            ],
        ];
    }

    /**
     * @return array
     */
    final public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            TimestampBehavior::class,
            AttributeTypecastBehavior::class,
        ]);
    }
}
