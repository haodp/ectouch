<?php

namespace app\models;

use think\Model;

/**
 * Class AuctionLog
 * @package app\models
 * @property $act_id
 * @property $bid_user
 * @property $bid_price
 * @property $bid_time
 */
class AuctionLog extends Model
{
    protected $table = 'auction_log';

    protected $pk = 'log_id';

}
