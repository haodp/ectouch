<?php

namespace App\Services;


class PackageService
{

    /**
     * 获取指定id package 的信息
     *
     * @access  public
     * @param   int $id package_id
     *
     * @return array       array(package_id, package_name, goods_id,start_time, end_time, min_price, integral)
     */
    function get_package_info($id)
    {
        $id = is_numeric($id) ? intval($id) : 0;
        $now = gmtime();

        $sql = "SELECT act_id AS id,  act_name AS package_name, goods_id , goods_name, start_time, end_time, act_desc, ext_info" .
            " FROM " . $GLOBALS['ecs']->table('goods_activity') .
            " WHERE act_id='$id' AND act_type = " . GAT_PACKAGE;

        $package = $GLOBALS['db']->getRow($sql);

        // 将时间转成可阅读格式
        if ($package['start_time'] <= $now && $package['end_time'] >= $now) {
            $package['is_on_sale'] = "1";
        } else {
            $package['is_on_sale'] = "0";
        }
        $package['start_time'] = local_date('Y-m-d H:i', $package['start_time']);
        $package['end_time'] = local_date('Y-m-d H:i', $package['end_time']);
        $row = unserialize($package['ext_info']);
        unset($package['ext_info']);
        if ($row) {
            foreach ($row as $key => $val) {
                $package[$key] = $val;
            }
        }

        $sql = "SELECT pg.package_id, pg.goods_id, pg.goods_number, pg.admin_id, " .
            " g.goods_sn, g.goods_name, g.market_price, g.goods_thumb, g.is_real, " .
            " IFNULL(mp.user_price, g.shop_price * '" . session('discount') . "') AS rank_price " .
            " FROM " . $GLOBALS['ecs']->table('package_goods') . " AS pg " .
            "   LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " AS g " .
            "   ON g.goods_id = pg.goods_id " .
            " LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp " .
            "ON mp.goods_id = g.goods_id AND mp.user_rank = '" . session('user_rank') . "' " .
            " WHERE pg.package_id = " . $id . " " .
            " ORDER BY pg.package_id, pg.goods_id";

        $goods_res = $GLOBALS['db']->getAll($sql);

        $market_price = 0;
        $real_goods_count = 0;
        $virtual_goods_count = 0;

        foreach ($goods_res as $key => $val) {
            $goods_res[$key]['goods_thumb'] = get_image_path($val['goods_thumb']);
            $goods_res[$key]['market_price_format'] = price_format($val['market_price']);
            $goods_res[$key]['rank_price_format'] = price_format($val['rank_price']);
            $market_price += $val['market_price'] * $val['goods_number'];
            // 统计实体商品和虚拟商品的个数
            if ($val['is_real']) {
                $real_goods_count++;
            } else {
                $virtual_goods_count++;
            }
        }

        if ($real_goods_count > 0) {
            $package['is_real'] = 1;
        } else {
            $package['is_real'] = 0;
        }

        $package['goods_list'] = $goods_res;
        $package['market_package'] = $market_price;
        $package['market_package_format'] = price_format($market_price);
        $package['package_price_format'] = price_format($package['package_price']);

        return $package;
    }

    /**
     * 获得指定礼包的商品
     *
     * @access  public
     * @param   integer $package_id
     * @return  array
     */
    function get_package_goods($package_id)
    {
        $sql = "SELECT pg.goods_id, g.goods_name, pg.goods_number, p.goods_attr, p.product_number, p.product_id
            FROM " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " AS g ON pg.goods_id = g.goods_id
                LEFT JOIN " . $GLOBALS['ecs']->table('products') . " AS p ON pg.product_id = p.product_id
            WHERE pg.package_id = '$package_id'";
        if ($package_id == 0) {
            $sql .= " AND pg.admin_id = '" . session('admin_id') . "'";
        }
        $resource = $GLOBALS['db']->query($sql);
        if (!$resource) {
            return [];
        }

        $row = [];

        // 生成结果数组 取存在货品的商品id 组合商品id与货品id
        $good_product_str = '';
        foreach ($resource as $_row) {
            if ($_row['product_id'] > 0) {
                // 取存商品id
                $good_product_str .= ',' . $_row['goods_id'];

                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'] . '_' . $_row['product_id'];
            } else {
                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'];
            }

            //生成结果数组
            $row[] = $_row;
        }
        $good_product_str = trim($good_product_str, ',');

        // 释放空间
        unset($resource, $_row, $sql);

        // 取商品属性
        if ($good_product_str != '') {
            $sql = "SELECT goods_attr_id, attr_value FROM " . $GLOBALS['ecs']->table('goods_attr') . " WHERE goods_id IN ($good_product_str)";
            $result_goods_attr = $GLOBALS['db']->getAll($sql);

            $_goods_attr = [];
            foreach ($result_goods_attr as $value) {
                $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
            }
        }

        // 过滤货品
        $format[0] = '%s[%s]--[%d]';
        $format[1] = '%s--[%d]';
        foreach ($row as $key => $value) {
            if ($value['goods_attr'] != '') {
                $goods_attr_array = explode('|', $value['goods_attr']);

                $goods_attr = [];
                foreach ($goods_attr_array as $_attr) {
                    $goods_attr[] = $_goods_attr[$_attr];
                }

                $row[$key]['goods_name'] = sprintf($format[0], $value['goods_name'], implode('，', $goods_attr), $value['goods_number']);
            } else {
                $row[$key]['goods_name'] = sprintf($format[1], $value['goods_name'], $value['goods_number']);
            }
        }

        return $row;
    }


    /**
     * 检查礼包内商品的库存
     * @return  boolen
     */
    function judge_package_stock($package_id, $package_num = 1)
    {
        $sql = "SELECT goods_id, product_id, goods_number
            FROM " . $GLOBALS['ecs']->table('package_goods') . "
            WHERE package_id = '" . $package_id . "'";
        $row = $GLOBALS['db']->getAll($sql);
        if (empty($row)) {
            return true;
        }

        // 分离货品与商品
        $goods = ['product_ids' => '', 'goods_ids' => ''];
        foreach ($row as $value) {
            if ($value['product_id'] > 0) {
                $goods['product_ids'] .= ',' . $value['product_id'];
                continue;
            }

            $goods['goods_ids'] .= ',' . $value['goods_id'];
        }

        // 检查货品库存
        if ($goods['product_ids'] != '') {
            $sql = "SELECT p.product_id
                FROM " . $GLOBALS['ecs']->table('products') . " AS p, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                WHERE pg.product_id = p.product_id
                AND pg.package_id = '$package_id'
                AND pg.goods_number * $package_num > p.product_number
                AND p.product_id IN (" . trim($goods['product_ids'], ',') . ")";
            $row = $GLOBALS['db']->getAll($sql);

            if (!empty($row)) {
                return true;
            }
        }

        // 检查商品库存
        if ($goods['goods_ids'] != '') {
            $sql = "SELECT g.goods_id
                FROM " . $GLOBALS['ecs']->table('goods') . "AS g, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                WHERE pg.goods_id = g.goods_id
                AND pg.goods_number * $package_num > g.goods_number
                AND pg.package_id = '" . $package_id . "'
                AND pg.goods_id IN (" . trim($goods['goods_ids'], ',') . ")";
            $row = $GLOBALS['db']->getAll($sql);

            if (!empty($row)) {
                return true;
            }
        }

        return false;
    }

}