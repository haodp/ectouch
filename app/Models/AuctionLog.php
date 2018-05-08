<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuctionLog
 * @package App\Models
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
