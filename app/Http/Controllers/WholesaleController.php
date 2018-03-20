<?php

namespace App\Http\Controllers;


use App\Libraries\Sms;

/**
 * 批发
 * Class WholesaleController
 * @package App\Http\Controllers
 */
class WholesaleController extends BaseController
{
    public function actionIndex()
    {
        // 如果没登录，提示登录
        if (session('user_rank') <= 0) {
            return show_message($GLOBALS['_LANG']['ws_user_rank'], $GLOBALS['_LANG']['ws_return_home'], 'index.php');
        }

        $_REQUEST['act'] = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'list';

        /**
         * 批发活动列表
         */
        if ($_REQUEST['act'] == 'list') {
            $search_category = empty($_REQUEST['search_category']) ? 0 : intval($_REQUEST['search_category']);
            $search_keywords = isset($_REQUEST['search_keywords']) ? trim($_REQUEST['search_keywords']) : '';
            $param = []; // 翻页链接所带参数列表

            // 查询条件：当前用户的会员等级（搜索关键字）
            $where = " WHERE g.goods_id = w.goods_id
               AND w.enabled = 1
               AND CONCAT(',', w.rank_ids, ',') LIKE '" . '%,' . session('user_rank') . ',%' . "' ";

            // 搜索
            // 搜索类别
            if ($search_category) {
                $where .= " AND g.cat_id = '$search_category' ";
                $param['search_category'] = $search_category;
                $this->smarty->assign('search_category', $search_category);
            }
            // 搜索商品名称和关键字
            if ($search_keywords) {
                $where .= " AND (g.keywords LIKE '%$search_keywords%'
                    OR g.goods_name LIKE '%$search_keywords%') ";
                $param['search_keywords'] = $search_keywords;
                $this->smarty->assign('search_keywords', $search_keywords);
            }

            // 取得批发商品总数
            $sql = "SELECT COUNT(*) FROM " . $this->ecs->table('wholesale') . " AS w, " . $this->ecs->table('goods') . " AS g " . $where;
            $count = $this->db->getOne($sql);

            if ($count > 0) {
                $default_display_type = $GLOBALS['_CFG']['show_order_type'] == '0' ? 'list' : 'text';
                $display = request()->cookie('display');
                $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'grid', 'text'])) ? trim($_REQUEST['display']) : ($display ? $display : $default_display_type);
                $display = in_array($display, ['list', 'grid', 'text']) ? $display : 'text';
                \Cookie::queue('display', $display, 1440 * 7);

                // 取得每页记录数
                $size = isset($GLOBALS['_CFG']['page_size']) && intval($GLOBALS['_CFG']['page_size']) > 0 ? intval($GLOBALS['_CFG']['page_size']) : 10;

                // 计算总页数
                $page_count = ceil($count / $size);

                // 取得当前页
                $page = isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
                $page = $page > $page_count ? $page_count : $page;

                // 取得当前页的批发商品
                $wholesale_list = $this->wholesale_list($size, $page, $where);
                $this->smarty->assign('wholesale_list', $wholesale_list);

                $param['act'] = 'list';
                $pager = get_pager('wholesale.php', array_reverse($param, true), $count, $page, $size);
                $pager['display'] = $display;
                $this->smarty->assign('pager', $pager);

                // 批发商品购物车
                $this->smarty->assign('cart_goods', session('wholesale_goods', []));
            }

            // 模板赋值
            assign_template();
            $position = assign_ur_here();
            $this->smarty->assign('page_title', $position['title']);    // 页面标题
            $this->smarty->assign('ur_here', $position['ur_here']);  // 当前位置
            $this->smarty->assign('categories', get_categories_tree()); // 分类树
            $this->smarty->assign('helps', get_shop_help());       // 网店帮助
            $this->smarty->assign('top_goods', get_top10());           // 销售排行

            assign_dynamic('wholesale');

            // 显示模板
            return $this->smarty->display('wholesale_list.dwt');
        }

        /**
         * 下载价格单
         */
        if ($_REQUEST['act'] == 'price_list') {
            $data = $GLOBALS['_LANG']['goods_name'] . "\t" . $GLOBALS['_LANG']['goods_attr'] . "\t" . $GLOBALS['_LANG']['number'] . "\t" . $GLOBALS['_LANG']['ws_price'] . "\t\n";
            $sql = "SELECT * FROM " . $this->ecs->table('wholesale') .
                "WHERE enabled = 1 AND CONCAT(',', rank_ids, ',') LIKE '" . '%,' . session('user_rank') . ',%' . "'";
            $res = $this->db->query($sql);
            foreach ($res as $row) {
                $price_list = unserialize($row['prices']);
                foreach ($price_list as $attr_price) {
                    if ($attr_price['attr']) {
                        $sql = "SELECT attr_value FROM " . $this->ecs->table('goods_attr') .
                            " WHERE goods_attr_id " . db_create_in($attr_price['attr']);
                        $goods_attr = join(',', $this->db->getCol($sql));
                    } else {
                        $goods_attr = '';
                    }
                    foreach ($attr_price['qp_list'] as $qp) {
                        $data .= $row['goods_name'] . "\t" . $goods_attr . "\t" . $qp['quantity'] . "\t" . $qp['price'] . "\t\n";
                    }
                }
            }

            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=price_list.xls");
            if (CHARSET == 'utf-8') {
                echo ecs_iconv('UTF8', 'GB2312', $data);
            } else {
                echo $data;
            }
        }

        /**
         * 加入购物车
         */
        if ($_REQUEST['act'] == 'add_to_cart') {
            // 取得参数
            $act_id = intval($_POST['act_id']);
            $goods_number = $_POST['goods_number'][$act_id];
            $attr_id = isset($_POST['attr_id']) ? $_POST['attr_id'] : [];
            if (isset($attr_id[$act_id])) {
                $goods_attr = $attr_id[$act_id];
            }

            // 用户提交必须全部通过检查，才能视为完成操作

            // 检查数量
            if (empty($goods_number) || (is_array($goods_number) && array_sum($goods_number) <= 0)) {
                return show_message($GLOBALS['_LANG']['ws_invalid_goods_number']);
            }

            // 确定购买商品列表
            $goods_list = [];
            if (is_array($goods_number)) {
                foreach ($goods_number as $key => $value) {
                    if (!$value) {
                        unset($goods_number[$key], $goods_attr[$key]);
                        continue;
                    }

                    $goods_list[] = ['number' => $goods_number[$key], 'goods_attr' => $goods_attr[$key]];
                }
            } else {
                $goods_list[0] = ['number' => $goods_number, 'goods_attr' => ''];
            }

            // 取批发相关数据
            $wholesale = wholesale_info($act_id);

            // 检查session中该商品，该属性是否存在
            if (session()->has('wholesale_goods')) {
                foreach (session('wholesale_goods') as $goods) {
                    if ($goods['goods_id'] == $wholesale['goods_id']) {
                        if (empty($goods_attr)) {
                            return show_message($GLOBALS['_LANG']['ws_goods_attr_exists']);
                        } elseif (in_array($goods['goods_attr_id'], $goods_attr)) {
                            return show_message($GLOBALS['_LANG']['ws_goods_attr_exists']);
                        }
                    }
                }
            }

            // 获取购买商品的批发方案的价格阶梯 （一个方案多个属性组合、一个属性组合、一个属性、无属性）
            $attr_matching = false;
            foreach ($wholesale['price_list'] as $attr_price) {
                // 没有属性
                if (empty($attr_price['attr'])) {
                    $attr_matching = true;
                    $goods_list[0]['qp_list'] = $attr_price['qp_list'];
                    break;
                } // 有属性
                elseif (($key = $this->is_attr_matching($goods_list, $attr_price['attr'])) !== false) {
                    $attr_matching = true;
                    $goods_list[$key]['qp_list'] = $attr_price['qp_list'];
                }
            }
            if (!$attr_matching) {
                return show_message($GLOBALS['_LANG']['ws_attr_not_matching']);
            }

            // 检查数量是否达到最低要求
            foreach ($goods_list as $goods_key => $goods) {
                if ($goods['number'] < $goods['qp_list'][0]['quantity']) {
                    return show_message($GLOBALS['_LANG']['ws_goods_number_not_enough']);
                } else {
                    $goods_price = 0;
                    foreach ($goods['qp_list'] as $qp) {
                        if ($goods['number'] >= $qp['quantity']) {
                            $goods_list[$goods_key]['goods_price'] = $qp['price'];
                        } else {
                            break;
                        }
                    }
                }
            }

            // 写入session
            foreach ($goods_list as $goods_key => $goods) {
                // 属性名称
                $goods_attr_name = '';
                if (!empty($goods['goods_attr'])) {
                    foreach ($goods['goods_attr'] as $key => $attr) {
                        $attr['attr_name'] = htmlspecialchars($attr['attr_name']);
                        $goods['goods_attr'][$key]['attr_name'] = $attr['attr_name'];
                        $attr['attr_val'] = htmlspecialchars($attr['attr_val']);
                        $goods['goods_attr'][$key]['attr_name'] = $attr['attr_name'];
                        $goods_attr_name .= $attr['attr_name'] . '：' . $attr['attr_val'] . '&nbsp;';
                    }
                }

                // 总价
                $total = $goods['number'] * $goods['goods_price'];

                $_SESSION['wholesale_goods'][] = [
                    'goods_id' => $wholesale['goods_id'],
                    'goods_name' => $wholesale['goods_name'],
                    'goods_attr_id' => $goods['goods_attr'],
                    'goods_attr' => $goods_attr_name,
                    'goods_number' => $goods['number'],
                    'goods_price' => $goods['goods_price'],
                    'subtotal' => $total,
                    'formated_goods_price' => price_format($goods['goods_price'], false),
                    'formated_subtotal' => price_format($total, false),
                    'goods_url' => build_uri('goods', ['gid' => $wholesale['goods_id']], $wholesale['goods_name']),
                ];
            }

            unset($goods_attr, $attr_id, $goods_list, $wholesale, $goods_attr_name);

            // 刷新页面
            return redirect("wholesale.php");
        }

        /**
         * 从购物车删除
         */
        if ($_REQUEST['act'] == 'drop_goods') {
            $key = intval($_REQUEST['key']);
            if (session()->has('wholesale_goods.' . $key)) {
                session(['wholesale_goods.' . $key => null]);
            }

            // 刷新页面
            return redirect("wholesale.php");
        }

        /**
         * 提交订单
         */
        if ($_REQUEST['act'] == 'submit_order') {
            load_helper('order');

            // 检查购物车中是否有商品
            if (count(session('wholesale_goods')) == 0) {
                return show_message($GLOBALS['_LANG']['no_goods_in_cart']);
            }

            // 检查备注信息
            if (empty($_POST['remark'])) {
                return show_message($GLOBALS['_LANG']['ws_remark']);
            }

            // 计算商品总额
            $goods_amount = 0;
            foreach (session('wholesale_goods') as $goods) {
                $goods_amount += $goods['subtotal'];
            }

            $order = [
                'postscript' => htmlspecialchars($_POST['remark']),
                'user_id' => session('user_id'),
                'add_time' => gmtime(),
                'order_status' => OS_UNCONFIRMED,
                'shipping_status' => SS_UNSHIPPED,
                'pay_status' => PS_UNPAYED,
                'goods_amount' => $goods_amount,
                'order_amount' => $goods_amount,
            ];

            // 插入订单表
            $error_no = 0;
            do {
                $order['order_sn'] = get_order_sn(); //获取新订单号
                $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');

                $error_no = $GLOBALS['db']->errno();

                if ($error_no > 0 && $error_no != 1062) {
                    die($GLOBALS['db']->errorMsg());
                }
            } while ($error_no == 1062); //如果是订单号重复则重新提交数据

            $new_order_id = $this->db->insert_id();
            $order['order_id'] = $new_order_id;

            // 插入订单商品
            foreach (session('wholesale_goods') as $goods) {
                //如果存在货品
                $product_id = 0;
                if (!empty($goods['goods_attr_id'])) {
                    $goods_attr_id = [];
                    foreach ($goods['goods_attr_id'] as $value) {
                        $goods_attr_id[$value['attr_id']] = $value['attr_val_id'];
                    }

                    ksort($goods_attr_id);
                    $goods_attr = implode('|', $goods_attr_id);

                    $sql = "SELECT product_id FROM " . $this->ecs->table('products') . " WHERE goods_attr = '$goods_attr' AND goods_id = '" . $goods['goods_id'] . "'";
                    $product_id = $this->db->getOne($sql);
                }

                $sql = "INSERT INTO " . $this->ecs->table('order_goods') . "( " .
                    "order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, " .
                    "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift) " .
                    " SELECT '$new_order_id', goods_id, goods_name, goods_sn, '$product_id','$goods[goods_number]', market_price, " .
                    "'$goods[goods_price]', '$goods[goods_attr]', is_real, extension_code, 0, 0 " .
                    " FROM " . $this->ecs->table('goods') .
                    " WHERE goods_id = '$goods[goods_id]'";
                $this->db->query($sql);
            }

            // 给商家发邮件
            if ($GLOBALS['_CFG']['service_email'] != '') {
                $tpl = get_mail_template('remind_of_new_order');
                $this->smarty->assign('order', $order);
                $this->smarty->assign('shop_name', $GLOBALS['_CFG']['shop_name']);
                $this->smarty->assign('send_date', date($GLOBALS['_CFG']['time_format']));
                $content = $this->smarty->fetch('str:' . $tpl['template_content']);
                send_mail($GLOBALS['_CFG']['shop_name'], $GLOBALS['_CFG']['service_email'], $tpl['template_subject'], $content, $tpl['is_html']);
            }

            // 如果需要，发短信
            if ($GLOBALS['_CFG']['sms_order_placed'] == '1' && $GLOBALS['_CFG']['sms_shop_mobile'] != '') {
                $sms = new Sms();
                $msg = $GLOBALS['_LANG']['order_placed_sms'];
                $sms->send($GLOBALS['_CFG']['sms_shop_mobile'], sprintf($msg, $order['consignee'], $order['tel']), '', 13, 1);
            }

            // 清空购物车
            session(['wholesale_goods' => null]);

            // 提示
            return show_message(sprintf($GLOBALS['_LANG']['ws_order_submitted'], $order['order_sn']), $GLOBALS['_LANG']['ws_return_home'], 'index.php');
        }
    }

    /**
     * 取得某页的批发商品
     * @param   int $size 每页记录数
     * @param   int $page 当前页
     * @param   string $where 查询条件
     * @return  array
     */
    private function wholesale_list($size, $page, $where)
    {
        $list = [];
        $sql = "SELECT w.*, g.goods_thumb, g.goods_name as goods_name " .
            "FROM " . $GLOBALS['ecs']->table('wholesale') . " AS w, " .
            $GLOBALS['ecs']->table('goods') . " AS g " . $where .
            " AND w.goods_id = g.goods_id ";
        $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
        foreach ($res as $row) {
            if (empty($row['goods_thumb'])) {
                $row['goods_thumb'] = $GLOBALS['_CFG']['no_picture'];
            }
            $row['goods_url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $properties = get_goods_properties($row['goods_id']);
            $row['goods_attr'] = $properties['pro'];

            $price_ladder = $this->get_price_ladder($row['goods_id']);
            $row['price_ladder'] = $price_ladder;

            $list[] = $row;
        }

        return $list;
    }

    /**
     * 商品价格阶梯
     * @param   int $goods_id 商品ID
     * @return  array
     */
    private function get_price_ladder($goods_id)
    {
        // 显示商品规格
        $goods_attr_list = array_values(get_goods_attr($goods_id));
        $sql = "SELECT prices FROM " . $GLOBALS['ecs']->table('wholesale') .
            "WHERE goods_id = " . $goods_id;
        $row = $GLOBALS['db']->getRow($sql);

        $arr = [];
        $_arr = unserialize($row['prices']);
        if (is_array($_arr)) {
            foreach (unserialize($row['prices']) as $key => $val) {
                // 显示属性
                if (!empty($val['attr'])) {
                    foreach ($val['attr'] as $attr_key => $attr_val) {
                        // 获取当前属性 $attr_key 的信息
                        $goods_attr = [];
                        foreach ($goods_attr_list as $goods_attr_val) {
                            if ($goods_attr_val['attr_id'] == $attr_key) {
                                $goods_attr = $goods_attr_val;
                                break;
                            }
                        }

                        // 重写商品规格的价格阶梯信息
                        if (!empty($goods_attr)) {
                            $arr[$key]['attr'][] = [
                                'attr_id' => $goods_attr['attr_id'],
                                'attr_name' => $goods_attr['attr_name'],
                                'attr_val' => (isset($goods_attr['goods_attr_list'][$attr_val]) ? $goods_attr['goods_attr_list'][$attr_val] : ''),
                                'attr_val_id' => $attr_val
                            ];
                        }
                    }
                }

                // 显示数量与价格
                foreach ($val['qp_list'] as $index => $qp) {
                    $arr[$key]['qp_list'][$qp['quantity']] = price_format($qp['price']);
                }
            }
        }

        return $arr;
    }

    /**
     * 商品属性是否匹配
     * @param   array $goods_list 用户选择的商品
     * @param   array $reference 参照的商品属性
     * @return  bool
     */
    private function is_attr_matching(&$goods_list, $reference)
    {
        foreach ($goods_list as $key => $goods) {
            // 需要相同的元素个数
            if (count($goods['goods_attr']) != count($reference)) {
                break;
            }

            // 判断用户提交与批发属性是否相同
            $is_check = true;
            if (is_array($goods['goods_attr'])) {
                foreach ($goods['goods_attr'] as $attr) {
                    if (!(array_key_exists($attr['attr_id'], $reference) && $attr['attr_val_id'] == $reference[$attr['attr_id']])) {
                        $is_check = false;
                        break;
                    }
                }
            }
            if ($is_check) {
                return $key;
                break;
            }
        }

        return false;
    }
}