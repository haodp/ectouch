<?php

namespace App\Shop\Controller;

/**
 * 夺宝奇兵
 * Class SnatchController
 * @package App\Shop\Controller
 */
class SnatchController extends InitController
{
    public function actionIndex()
    {
        $_REQUEST['act'] = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'main';

        // 设置活动的SESSION
        if (empty($_REQUEST['id'])) {
            $id = $this->get_last_snatch();
            if ($id) {
                $page = build_uri('snatch', ['sid' => $id]);
                return redirect("$page");
            } else {
                // 当前没有任何可默认的活动
                $id = 0;
            }
        } else {
            $id = intval($_REQUEST['id']);
        }

        // 显示页面部分
        if ($_REQUEST['act'] == 'main') {
            $goods = $this->get_snatch($id);
            if ($goods) {
                $position = assign_ur_here(0, $goods['snatch_name']);
                $myprice = $this->get_myprice($id);
                if ($goods['is_end']) {
                    //如果活动已经结束,获取活动结果
                    $this->smarty->assign('result', get_snatch_result($id));
                }
                $this->smarty->assign('id', $id);
                $this->smarty->assign('snatch_goods', $goods); // 竞价商品
                $this->smarty->assign('myprice', $this->get_myprice($id));
                if ($goods['product_id'] > 0) {
                    $goods_specifications = get_specifications_list($goods['goods_id']);

                    $good_products = get_good_products($goods['goods_id'], 'AND product_id = ' . $goods['product_id']);

                    $_good_products = explode('|', $good_products[0]['goods_attr']);
                    $products_info = '';
                    foreach ($_good_products as $value) {
                        $products_info .= ' ' . $goods_specifications[$value]['attr_name'] . '：' . $goods_specifications[$value]['attr_value'];
                    }
                    $this->smarty->assign('products_info', $products_info);
                    unset($goods_specifications, $good_products, $_good_products, $products_info);
                }
            } else {
                return show_message($GLOBALS['_LANG']['now_not_snatch']);
            }

            // 调查
            $vote = get_vote();
            if (!empty($vote)) {
                $this->smarty->assign('vote_id', $vote['id']);
                $this->smarty->assign('vote', $vote['content']);
            }

            assign_template();
            assign_dynamic('snatch');
            $this->smarty->assign('page_title', $position['title']);
            $this->smarty->assign('ur_here', $position['ur_here']);
            $this->smarty->assign('categories', get_categories_tree()); // 分类树
            $this->smarty->assign('helps', get_shop_help());       // 网店帮助
            $this->smarty->assign('snatch_list', $this->get_snatch_list());     //所有有效的夺宝奇兵列表
            $this->smarty->assign('price_list', $this->get_price_list($id));
            $this->smarty->assign('promotion_info', get_promotion_info());
            $this->smarty->assign('feed_url', ($GLOBALS['_CFG']['rewrite'] == 1) ? "feed-typesnatch.xml" : 'feed.php?type=snatch'); // RSS URL

            return $this->smarty->display('snatch.dwt');
        }

        /**
         * 最新出价列表
         */
        if ($_REQUEST['act'] == 'new_price_list') {
            $this->smarty->assign('price_list', $this->get_price_list($id));

            return $this->smarty->display('library/snatch_price.lbi');
        }

        /**
         * 用户出价处理
         */
        if ($_REQUEST['act'] == 'bid') {
            // include_once(ROOT_PATH . 'includes/cls_json.php');
            $json = new Json();
            $result = ['error' => 0, 'content' => ''];

            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
            $price = round($price, 2);

            // 测试是否登陆
            if (empty(session('user_id'))) {
                $result['error'] = 1;
                $result['content'] = $GLOBALS['_LANG']['not_login'];
                die($json->encode($result));
            }

            // 获取活动基本信息用于校验
            $sql = 'SELECT act_name AS snatch_name, end_time, ext_info FROM ' . $GLOBALS['ecs']->table('goods_activity') . " WHERE act_id ='$id'";
            $row = $this->db->getRow($sql, 'SILENT');

            if ($row) {
                $info = unserialize($row['ext_info']);
                if ($info) {
                    foreach ($info as $key => $val) {
                        $row[$key] = $val;
                    }
                }
            }

            if (empty($row)) {
                $result['error'] = 1;
                $result['content'] = $this->db->error();
                die($json->encode($result));
            }

            if ($row['end_time'] < gmtime()) {
                $result['error'] = 1;
                $result['content'] = $GLOBALS['_LANG']['snatch_is_end'];
                die($json->encode($result));
            }

            // 检查出价是否合理
            if ($price < $row['start_price'] || $price > $row['end_price']) {
                $result['error'] = 1;
                $result['content'] = sprintf($GLOBALS['_LANG']['not_in_range'], $row['start_price'], $row['end_price']);
                die($json->encode($result));
            }

            // 检查用户是否已经出同一价格
            $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('snatch_log') . " WHERE snatch_id = '$id' AND user_id = '" . session('user_id') . "' AND bid_price = '$price'";
            if ($GLOBALS['db']->getOne($sql) > 0) {
                $result['error'] = 1;
                $result['content'] = sprintf($GLOBALS['_LANG']['also_bid'], price_format($price, false));
                die($json->encode($result));
            }

            // 检查用户积分是否足够
            $sql = 'SELECT pay_points FROM ' . $this->ecs->table('users') . " WHERE user_id = '" . session('user_id') . "'";
            $pay_points = $this->db->getOne($sql);
            if ($row['cost_points'] > $pay_points) {
                $result['error'] = 1;
                $result['content'] = $GLOBALS['_LANG']['lack_pay_points'];
                die($json->encode($result));
            }

            log_account_change(session('user_id'), 0, 0, 0, 0 - $row['cost_points'], sprintf($GLOBALS['_LANG']['snatch_log'], $row['snatch_name'])); //扣除用户积分
            $sql = 'INSERT INTO ' . $this->ecs->table('snatch_log') . '(snatch_id, user_id, bid_price, bid_time) VALUES' .
                "('$id', '" . session('user_id') . "', '" . $price . "', " . gmtime() . ")";
            $this->db->query($sql);

            $this->smarty->assign('myprice', $this->get_myprice($id));
            $this->smarty->assign('id', $id);
            $result['content'] = $this->smarty->fetch('library/snatch.lbi');
            die($json->encode($result));
        }

        /**
         * 购买商品
         */
        if ($_REQUEST['act'] == 'buy') {
            if (empty($id)) {
                return redirect('/');
            }

            if (empty(session('user_id'))) {
                return show_message($GLOBALS['_LANG']['not_login']);
            }

            $snatch = $this->get_snatch($id);


            if (empty($snatch)) {
                return redirect('/');
            }

            // 未结束，不能购买
            if (empty($snatch['is_end'])) {
                $page = build_uri('snatch', ['sid' => $id]);
                return redirect("$page");
            }

            $result = get_snatch_result($id);

            if (session('user_id') != $result['user_id']) {
                return show_message($GLOBALS['_LANG']['not_for_you']);
            }

            //检查是否已经购买过
            if ($result['order_count'] > 0) {
                return show_message($GLOBALS['_LANG']['order_placed']);
            }

            // 处理规格属性
            $goods_attr = '';
            $goods_attr_id = '';
            if ($snatch['product_id'] > 0) {
                $product_info = get_good_products($snatch['goods_id'], 'AND product_id = ' . $snatch['product_id']);

                $goods_attr_id = str_replace('|', ',', $product_info[0]['goods_attr']);

                $attr_list = [];
                $sql = "SELECT a.attr_name, g.attr_value " .
                    "FROM " . $this->ecs->table('goods_attr') . " AS g, " .
                    $this->ecs->table('attribute') . " AS a " .
                    "WHERE g.attr_id = a.attr_id " .
                    "AND g.goods_attr_id " . db_create_in($goods_attr_id);
                $res = $this->db->query($sql);
                foreach ($res as $row) {
                    $attr_list[] = $row['attr_name'] . ': ' . $row['attr_value'];
                }
                $goods_attr = join('', $attr_list);
            } else {
                $snatch['product_id'] = 0;
            }

            // 清空购物车中所有商品
            load_helper('order');
            clear_cart(CART_SNATCH_GOODS);

            // 加入购物车
            $cart = [
                'user_id' => session('user_id'),
                'session_id' => SESS_ID,
                'goods_id' => $snatch['goods_id'],
                'product_id' => $snatch['product_id'],
                'goods_sn' => addslashes($snatch['goods_sn']),
                'goods_name' => addslashes($snatch['goods_name']),
                'market_price' => $snatch['market_price'],
                'goods_price' => $result['buy_price'],
                'goods_number' => 1,
                'goods_attr' => $goods_attr,
                'goods_attr_id' => $goods_attr_id,
                'is_real' => $snatch['is_real'],
                'extension_code' => addslashes($snatch['extension_code']),
                'parent_id' => 0,
                'rec_type' => CART_SNATCH_GOODS,
                'is_gift' => 0
            ];

            $this->db->autoExecute($this->ecs->table('cart'), $cart, 'INSERT');

            // 记录购物流程类型：夺宝奇兵
            session(['flow_type' => CART_SNATCH_GOODS]);
            session(['extension_code' => 'snatch']);
            session(['extension_id' => $id]);

            // 进入收货人页面
            return redirect("flow.php?step=consignee");
        }
    }

    /**
     * 取得用户对当前活动的所出过的价格
     *
     * @access  public
     * @param
     *
     * @return void
     */
    private function get_myprice($id)
    {
        $my_only_price = [];
        $my_price = [];
        $pay_points = 0;
        $bid_price = [];
        if (!empty(session('user_id'))) {
            // 取得用户所有价格
            $sql = 'SELECT bid_price FROM ' . $GLOBALS['ecs']->table('snatch_log') . " WHERE snatch_id = '$id' AND user_id = '" . session('user_id') . "' ORDER BY bid_time DESC";
            $my_price = $GLOBALS['db']->getCol($sql);

            if ($my_price) {
                // 取得用户唯一价格
                $sql = 'SELECT bid_price , count(*) AS num FROM ' . $GLOBALS['ecs']->table('snatch_log') . "  WHERE snatch_id ='$id' AND bid_price " . db_create_in(join(',', $my_price)) . ' GROUP BY bid_price HAVING num = 1';
                $my_only_price = $GLOBALS['db']->getCol($sql);
            }

            for ($i = 0, $count = count($my_price); $i < $count; $i++) {
                $bid_price[] = ['price' => price_format($my_price[$i], false),
                    'is_only' => in_array($my_price[$i], $my_only_price)
                ];
            }

            $sql = 'SELECT pay_points FROM ' . $GLOBALS['ecs']->table('users') . " WHERE user_id = '" . session('user_id') . "'";
            $pay_points = $GLOBALS['db']->getOne($sql);
            $pay_points = $pay_points . $GLOBALS['_CFG']['integral_name'];
        }

        // 活动结束时间
        $sql = 'SELECT end_time FROM ' . $GLOBALS['ecs']->table('goods_activity') .
            " WHERE act_id = '$id' AND act_type=" . GAT_SNATCH;
        $end_time = $GLOBALS['db']->getOne($sql);
        $my_price = [
            'pay_points' => $pay_points,
            'bid_price' => $bid_price,
            'is_end' => gmtime() > $end_time
        ];

        return $my_price;
    }

    /**
     * 取得当前活动的前n个出价
     *
     * @access  public
     * @param   int $num 列表个数(取前5个)
     *
     * @return void
     */
    private function get_price_list($id, $num = 5)
    {
        $sql = 'SELECT t1.log_id, t1.bid_price, t2.user_name FROM ' . $GLOBALS['ecs']->table('snatch_log') . ' AS t1, ' . $GLOBALS['ecs']->table('users') . " AS t2 WHERE snatch_id = '$id' AND t1.user_id = t2.user_id ORDER BY t1.log_id DESC LIMIT $num";
        $res = $GLOBALS['db']->query($sql);
        $price_list = [];
        foreach ($res as $row) {
            $price_list[] = ['bid_price' => price_format($row['bid_price'], false), 'user_name' => $row['user_name']];
        }
        return $price_list;
    }

    /**
     * 取的最近的几次活动。
     *
     * @access  public
     * @param
     *
     * @return void
     */
    private function get_snatch_list($num = 10)
    {
        $now = gmtime();
        $sql = 'SELECT act_id AS snatch_id, act_name AS snatch_name, end_time ' .
            ' FROM ' . $GLOBALS['ecs']->table('goods_activity') .
            " WHERE start_time <= '$now' AND act_type=" . GAT_SNATCH .
            " ORDER BY end_time DESC LIMIT $num";
        $snatch_list = [];
        $overtime = 0;
        $res = $GLOBALS['db']->query($sql);
        foreach ($res as $row) {
            $overtime = $row['end_time'] > $now ? 0 : 1;
            $snatch_list[] = [
                'snatch_id' => $row['snatch_id'],
                'snatch_name' => $row['snatch_name'],
                'overtime' => $overtime,
                'url' => build_uri('snatch', ['sid' => $row['snatch_id']])
            ];
        }
        return $snatch_list;
    }

    /**
     * 取得当前活动信息
     *
     * @access  public
     *
     * @return 活动名称
     */
    private function get_snatch($id)
    {
        $sql = "SELECT g.goods_id, g.goods_sn, g.is_real, g.goods_name, g.extension_code, g.market_price, g.shop_price AS org_price, product_id, " .
            "IFNULL(mp.user_price, g.shop_price * '". session('discount') ."') AS shop_price, " .
            "g.promote_price, g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb, " .
            "ga.act_name AS snatch_name, ga.start_time, ga.end_time, ga.ext_info, ga.act_desc AS `desc` " .
            "FROM " . $GLOBALS['ecs']->table('goods_activity') . " AS ga " .
            "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " AS g " .
            "ON g.goods_id = ga.goods_id " .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp " .
            "ON mp.goods_id = g.goods_id AND mp.user_rank = '". session('user_rank') ."' " .
            "WHERE ga.act_id = '$id' AND g.is_delete = 0";

        $goods = $GLOBALS['db']->getRow($sql);

        if ($goods) {
            $promote_price = bargain_price($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
            $goods['formated_market_price'] = price_format($goods['market_price']);
            $goods['formated_shop_price'] = price_format($goods['shop_price']);
            $goods['formated_promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
            $goods['goods_thumb'] = get_image_path($goods['goods_thumb']);
            $goods['url'] = build_uri('goods', ['gid' => $goods['goods_id']], $goods['goods_name']);
            $goods['start_time'] = local_date($GLOBALS['_CFG']['time_format'], $goods['start_time']);

            $info = unserialize($goods['ext_info']);
            if ($info) {
                foreach ($info as $key => $val) {
                    $goods[$key] = $val;
                }
                $goods['is_end'] = gmtime() > $goods['end_time'];
                $goods['formated_start_price'] = price_format($goods['start_price']);
                $goods['formated_end_price'] = price_format($goods['end_price']);
                $goods['formated_max_price'] = price_format($goods['max_price']);
            }
            // 将结束日期格式化为格林威治标准时间时间戳
            $goods['gmt_end_time'] = $goods['end_time'];
            $goods['end_time'] = local_date($GLOBALS['_CFG']['time_format'], $goods['end_time']);
            $goods['snatch_time'] = sprintf($GLOBALS['_LANG']['snatch_start_time'], $goods['start_time'], $goods['end_time']);

            return $goods;
        } else {
            return false;
        }
    }

    /**
     * 获取最近要到期的活动id，没有则返回 0
     *
     * @access  public
     * @param
     *
     * @return void
     */
    private function get_last_snatch()
    {
        $now = gmtime();
        $sql = 'SELECT act_id FROM ' . $GLOBALS['ecs']->table('goods_activity') .
            " WHERE  start_time < '$now' AND end_time > '$now' AND act_type = " . GAT_SNATCH .
            " ORDER BY end_time ASC LIMIT 1";
        return $GLOBALS['db']->getOne($sql);
    }
}