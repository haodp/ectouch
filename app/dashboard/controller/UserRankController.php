<?php

namespace app\dashboard\controller;

use app\libraries\Exchange;

/**
 * 会员等级管理
 * Class UserRankController
 * @package app\dashboard\controller
 */
class UserRankController extends InitController
{
    public function index()
    {
        $exc = new Exchange($this->ecs->table("user_rank"), $this->db, 'rank_id', 'rank_name');
        $exc_user = new Exchange($this->ecs->table("users"), $this->db, 'user_rank', 'user_rank');

        /**
         * 会员等级列表
         */
        if ($_REQUEST['act'] == 'list') {
            $ranks = [];
            $ranks = $this->db->getAll("SELECT * FROM " . $this->ecs->table('user_rank'));

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['05_user_rank_list']);
            $this->smarty->assign('action_link', ['text' => $GLOBALS['_LANG']['add_user_rank'], 'href' => 'user_rank.php?act=add']);
            $this->smarty->assign('full_page', 1);
            $this->smarty->assign('user_ranks', $ranks);

            return $this->smarty->display('user_rank.htm');
        }

        /**
         * 翻页，排序
         */
        if ($_REQUEST['act'] == 'query') {
            $ranks = [];
            $ranks = $this->db->getAll("SELECT * FROM " . $this->ecs->table('user_rank'));

            $this->smarty->assign('user_ranks', $ranks);
            return make_json_result($this->smarty->fetch('user_rank.htm'));
        }

        /**
         * 添加会员等级
         */
        if ($_REQUEST['act'] == 'add') {
            admin_priv('user_rank');

            $rank['rank_id'] = 0;
            $rank['rank_special'] = 0;
            $rank['show_price'] = 1;
            $rank['min_points'] = 0;
            $rank['max_points'] = 0;
            $rank['discount'] = 100;

            $form_action = 'insert';

            $this->smarty->assign('rank', $rank);
            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['add_user_rank']);
            $this->smarty->assign('action_link', ['text' => $GLOBALS['_LANG']['05_user_rank_list'], 'href' => 'user_rank.php?act=list']);
            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['add_user_rank']);
            $this->smarty->assign('form_action', $form_action);

            return $this->smarty->display('user_rank_info.htm');
        }

        /**
         * 增加会员等级到数据库
         */
        if ($_REQUEST['act'] == 'insert') {
            admin_priv('user_rank');

            $special_rank = isset($_POST['special_rank']) ? intval($_POST['special_rank']) : 0;
            $_POST['min_points'] = empty($_POST['min_points']) ? 0 : intval($_POST['min_points']);
            $_POST['max_points'] = empty($_POST['max_points']) ? 0 : intval($_POST['max_points']);

            // 检查是否存在重名的会员等级
            if (!$exc->is_only('rank_name', trim($_POST['rank_name']))) {
                return sys_msg(sprintf($GLOBALS['_LANG']['rank_name_exists'], trim($_POST['rank_name'])), 1);
            }

            // 非特殊会员组检查积分的上下限是否合理
            if ($_POST['min_points'] >= $_POST['max_points'] && $special_rank == 0) {
                return sys_msg($GLOBALS['_LANG']['js_languages']['integral_max_small'], 1);
            }

            // 特殊等级会员组不判断积分限制
            if ($special_rank == 0) {
                // 检查下限制有无重复
                if (!$exc->is_only('min_points', intval($_POST['min_points']))) {
                    return sys_msg(sprintf($GLOBALS['_LANG']['integral_min_exists'], intval($_POST['min_points'])));
                }
            }

            // 特殊等级会员组不判断积分限制
            if ($special_rank == 0) {
                // 检查上限有无重复
                if (!$exc->is_only('max_points', intval($_POST['max_points']))) {
                    return sys_msg(sprintf($GLOBALS['_LANG']['integral_max_exists'], intval($_POST['max_points'])));
                }
            }

            $sql = "INSERT INTO " . $this->ecs->table('user_rank') . "( " .
                "rank_name, min_points, max_points, discount, special_rank, show_price" .
                ") VALUES (" .
                "'$_POST[rank_name]', '" . intval($_POST['min_points']) . "', '" . intval($_POST['max_points']) . "', " .
                "'$_POST[discount]', '$special_rank', '" . intval($_POST['show_price']) . "')";
            $this->db->query($sql);

            // 管理员日志
            admin_log(trim($_POST['rank_name']), 'add', 'user_rank');
            clear_cache_files();

            $lnk[] = ['text' => $GLOBALS['_LANG']['back_list'], 'href' => 'user_rank.php?act=list'];
            $lnk[] = ['text' => $GLOBALS['_LANG']['add_continue'], 'href' => 'user_rank.php?act=add'];
            return sys_msg($GLOBALS['_LANG']['add_rank_success'], 0, $lnk);
        }

        /**
         * 删除会员等级
         */
        if ($_REQUEST['act'] == 'remove') {
            check_authz_json('user_rank');

            $rank_id = intval($_GET['id']);

            if ($exc->drop($rank_id)) {
                // 更新会员表的等级字段
                $exc_user->edit("user_rank = 0", $rank_id);

                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'remove', 'user_rank');
                clear_cache_files();
            }

            $url = 'user_rank.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            $this->redirect($url);
        }
        /*
         *  编辑会员等级名称
         */
        if ($_REQUEST['act'] == 'edit_name') {
            $id = intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
            check_authz_json('user_rank');
            if ($exc->is_only('rank_name', $val, $id)) {
                if ($exc->edit("rank_name = '$val'", $id)) {
                    // 管理员日志
                    admin_log($val, 'edit', 'user_rank');
                    clear_cache_files();
                    return make_json_result(stripcslashes($val));
                } else {
                    return make_json_error($this->db->error());
                }
            } else {
                return make_json_error(sprintf($GLOBALS['_LANG']['rank_name_exists'], htmlspecialchars($val)));
            }
        }

        /*
         *  ajax编辑积分下限
         */
        if ($_REQUEST['act'] == 'edit_min_points') {
            check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            $rank = $this->db->getRow("SELECT max_points, special_rank FROM " . $this->ecs->table('user_rank') . " WHERE rank_id = '$rank_id'");
            if ($val >= $rank['max_points'] && $rank['special_rank'] == 0) {
                return make_json_error($GLOBALS['_LANG']['js_languages']['integral_max_small']);
            }

            if ($rank['special_rank'] == 0 && !$exc->is_only('min_points', $val, $rank_id)) {
                return make_json_error(sprintf($GLOBALS['_LANG']['integral_min_exists'], $val));
            }

            if ($exc->edit("min_points = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'edit', 'user_rank');
                return make_json_result($val);
            } else {
                return make_json_error($this->db->error());
            }
        }

        /*
         *  ajax修改积分上限
         */
        if ($_REQUEST['act'] == 'edit_max_points') {
            check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            $rank = $this->db->getRow("SELECT min_points, special_rank FROM " . $this->ecs->table('user_rank') . " WHERE rank_id = '$rank_id'");

            if ($val <= $rank['min_points'] && $rank['special_rank'] == 0) {
                return make_json_error($GLOBALS['_LANG']['js_languages']['integral_max_small']);
            }

            if ($rank['special_rank'] == 0 && !$exc->is_only('max_points', $val, $rank_id)) {
                return make_json_error(sprintf($GLOBALS['_LANG']['integral_max_exists'], $val));
            }
            if ($exc->edit("max_points = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'edit', 'user_rank');
                return make_json_result($val);
            } else {
                return make_json_error($this->db->error());
            }
        }

        /*
         *  修改折扣率
         */
        if ($_REQUEST['act'] == 'edit_discount') {
            check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            if ($val < 1 || $val > 100) {
                return make_json_error($GLOBALS['_LANG']['js_languages']['discount_invalid']);
            }

            if ($exc->edit("discount = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'edit', 'user_rank');
                clear_cache_files();
                return make_json_result($val);
            } else {
                return make_json_error($val);
            }
        }

        /**
         * 切换是否是特殊会员组
         */
        if ($_REQUEST['act'] == 'toggle_special') {
            check_authz_json('user_rank');

            $rank_id = intval($_POST['id']);
            $is_special = intval($_POST['val']);

            if ($exc->edit("special_rank = '$is_special'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'edit', 'user_rank');
                return make_json_result($is_special);
            } else {
                return make_json_error($this->db->error());
            }
        }
        /**
         * 切换是否显示价格
         */
        if ($_REQUEST['act'] == 'toggle_showprice') {
            check_authz_json('user_rank');

            $rank_id = intval($_POST['id']);
            $is_show = intval($_POST['val']);

            if ($exc->edit("show_price = '$is_show'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                admin_log(addslashes($rank_name), 'edit', 'user_rank');
                clear_cache_files();
                return make_json_result($is_show);
            } else {
                return make_json_error($this->db->error());
            }
        }
    }
}
