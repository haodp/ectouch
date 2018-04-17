<?php

namespace app\http\controllers;

/**
 * 生成商品列表
 * Class GoodsScriptController
 * @package app\http\controllers
 */
class GoodsScriptController extends InitController
{
    public function actionIndex()
    {
        $charset = empty($_GET['charset']) ? CHARSET : $_GET['charset'];
        $type = empty($_GET['type']) ? '' : 'collection';
        if (strtolower($charset) == 'gb2312') {
            $charset = 'gbk';
        }
        header('content-type: application/x-javascript; charset=' . ($charset == 'UTF8' ? 'utf-8' : $charset));

        /**
         * 判断是否存在缓存，如果存在则调用缓存，反之读取相应内容
         */
        $cache_id = sprintf('%X', crc32($_SERVER['QUERY_STRING']));

        $tpl = storage_path('app/public/' . DATA_DIR . '/goods_script.html');
        if (!$this->smarty->is_cached($tpl, $cache_id)) {
            $time = gmtime();
            $sql = '';
            // 根据参数生成查询语句
            if ($type == '') {
                $sitename = !empty($_GET['sitename']) ? $_GET['sitename'] : '';
                $_from = (!empty($_GET['charset']) && $_GET['charset'] != 'UTF8') ? urlencode(ecs_iconv('UTF-8', 'GBK', $sitename)) : urlencode(@$sitename);
                $goods_url = $this->ecs->url() . 'affiche.php?ad_id=-1&amp;from=' . $_from . '&amp;goods_id=';

                $sql = 'SELECT goods_id, goods_name, market_price, goods_thumb, RAND() AS rnd, ' .
                    "IF(is_promote = 1 AND '$time' >= promote_start_date AND " .
                    "'$time' <= promote_end_date, promote_price, shop_price) AS goods_price " .
                    'FROM ' . $this->ecs->table('goods') . ' AS g ' .
                    "WHERE is_delete = '0' AND is_on_sale = '1' AND is_alone_sale = '1' ";
                if (!empty($_GET['cat_id'])) {
                    $sql .= ' AND ' . get_children(intval($_GET['cat_id']));
                }
                if (!empty($_GET['brand_id'])) {
                    $sql .= " AND brand_id = '" . intval($_GET['brand_id']) . "'";
                }
                if (!empty($_GET['intro_type'])) {
                    $_GET['intro_type'] = trim($_GET['intro_type']);

                    if ($_GET['intro_type'] == 'is_best' || $_GET['intro_type'] == 'is_new' || $_GET['intro_type'] == 'is_hot' || $_GET['intro_type'] == 'is_promote' || $_GET['intro_type'] == 'is_random') {
                        if ($_GET['intro_type'] == 'is_random') {
                            $sql .= ' ORDER BY rnd';
                        } else {
                            if ($_GET['intro_type'] == 'is_promote') {
                                $sql .= " AND promote_start_date <= '$time' AND promote_end_date >= '$time'";
                            }
                            $sql .= " AND " . $_GET['intro_type'] . " = 1 ORDER BY add_time DESC";
                        }
                    }
                }
            } elseif ($type == 'collection') {
                $uid = (int)$_GET['u'];
                $goods_url = $this->ecs->url() . "goods.php?u=$uid&id=";
                $sql = "SELECT g.goods_id, g.goods_name, g.market_price, g.goods_thumb, IF(g.is_promote = 1 AND '$time' >= g.promote_start_date AND " .
                    "'$time' <= g.promote_end_date, g.promote_price, g.shop_price) AS goods_price FROM " . $this->ecs->table('goods') . " g LEFT JOIN " . $this->ecs->table('collect_goods') . " c ON g.goods_id = c.goods_id " .
                    " WHERE c.user_id = '$uid'";
            }
            $sql .= " LIMIT " . (!empty($_GET['goods_num']) ? intval($_GET['goods_num']) : 10);
            $res = $this->db->query($sql);

            $goods_list = [];
            foreach ($res as $goods) {
                // 转换编码
                $goods['goods_price'] = price_format($goods['goods_price']);
                if ($charset != CHARSET) {
                    if (CHARSET == 'gbk') {
                        $tmp_goods_name = htmlentities($goods['goods_name'], ENT_QUOTES, 'gb2312');
                    } else {
                        $tmp_goods_name = htmlentities($goods['goods_name'], ENT_QUOTES, CHARSET);
                    }
                    $goods['goods_name'] = ecs_iconv(CHARSET, $charset, $tmp_goods_name);
                    $goods['goods_price'] = ecs_iconv(CHARSET, $charset, $goods['goods_price']);
                }
                $goods['goods_name'] = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($goods['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $goods['goods_name'];
                $goods['goods_thumb'] = get_image_path($goods['goods_thumb']);
                $goods_list[] = $goods;
            }

            // 排列方式
            $arrange = empty($_GET['arrange']) || !in_array($_GET['arrange'], ['h', 'v']) ? 'h' : $_GET['arrange'];

            // 排列显示条目个数
            $goods_num = !empty($_GET['goods_num']) ? intval($_GET['goods_num']) : 10;
            $rows_num = !empty($_GET['rows_num']) ? intval($_GET['rows_num']) : '1';
            if ($arrange == 'h') {
                $goods_items = array_chunk($goods_list, $rows_num);
            } else {
                $columns_num = ceil($goods_num / $rows_num);
                $goods_items = array_chunk($goods_list, $columns_num);
            }
            $this->smarty->assign('goods_list', $goods_items);

            // 是否需要图片
            $need_image = empty($_GET['need_image']) || $_GET['need_image'] == 'true' ? 1 : 0;
            $this->smarty->assign('need_image', $need_image);

            // 图片大小
            $this->smarty->assign('thumb_width', intval($GLOBALS['_CFG']['thumb_width']));
            $this->smarty->assign('thumb_height', intval($GLOBALS['_CFG']['thumb_height']));

            // 网站根目录
            $this->smarty->assign('url', $this->ecs->url());

            // 商品页面连接
            $this->smarty->assign('goods_url', $goods_url);
        }
        $output = $this->smarty->fetch($tpl, $cache_id);
        $output = str_replace("\r", '', $output);
        $output = str_replace("\n", '', $output);

        echo "document.write('$output');";
    }
}