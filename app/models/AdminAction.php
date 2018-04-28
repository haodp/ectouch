<?php

namespace app\models;

use think\Model;

/**
 * Class AdminAction
 * @package app\models
 * @property integer $action_id 自增ID
 * @property integer $parent_id 父级ID
 * @property string $action_code 权限名称
 * @property string $relevance 关联权限
 */
class AdminAction extends Model
{
    protected $table = 'admin_action';

    protected $pk = 'action_id';

}
