<?php

namespace App\Admin\Controller;

/**
 * 供货商管理
 * Class SuppliersController
 * @package App\Admin\Controller
 */
class SuppliersController extends BaseController
{
    public function actionIndex()
    {
        define('SUPPLIERS_ACTION_LIST', 'delivery_view,back_view');

        /**
         * 供货商列表
         */
        if ($_REQUEST['act'] == 'list') {
            // 检查权限
            admin_priv('suppliers_manage');

            // 查询
            $result = $this->suppliers_list();

            // 模板赋值
            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['suppliers_list']); // 当前导航
            $this->smarty->assign('action_link', ['href' => 'suppliers.php?act=add', 'text' => $GLOBALS['_LANG']['add_suppliers']]);

            $this->smarty->assign('full_page', 1); // 翻页参数

            $this->smarty->assign('suppliers_list', $result['result']);
            $this->smarty->assign('filter', $result['filter']);
            $this->smarty->assign('record_count', $result['record_count']);
            $this->smarty->assign('page_count', $result['page_count']);
            $this->smarty->assign('sort_suppliers_id', '<img src="' . __TPL__ . '/images/sort_desc.gif">');

            return $this->smarty->display('suppliers_list.htm');
        }

        /**
         * 排序、分页、查询
         */
        if ($_REQUEST['act'] == 'query') {
            check_authz_json('suppliers_manage');

            $result = $this->suppliers_list();

            $this->smarty->assign('suppliers_list', $result['result']);
            $this->smarty->assign('filter', $result['filter']);
            $this->smarty->assign('record_count', $result['record_count']);
            $this->smarty->assign('page_count', $result['page_count']);

            // 排序标记
            $sort_flag = sort_flag($result['filter']);
            $this->smarty->assign($sort_flag['tag'], $sort_flag['img']);

            return make_json_result($this->smarty->fetch('suppliers_list.htm'), '',
                ['filter' => $result['filter'], 'page_count' => $result['page_count']]);
        }

        /**
         * 列表页编辑名称
         */
        if ($_REQUEST['act'] == 'edit_suppliers_name') {
            check_authz_json('suppliers_manage');

            $id = intval($_POST['id']);
            $name = json_str_iconv(trim($_POST['val']));

            // 判断名称是否重复
            $sql = "SELECT suppliers_id
            FROM " . $this->ecs->table('suppliers') . "
            WHERE suppliers_name = '$name'
            AND suppliers_id <> '$id' ";
            if ($this->db->getOne($sql)) {
                return make_json_error(sprintf($GLOBALS['_LANG']['suppliers_name_exist'], $name));
            } else {
                // 保存供货商信息
                $sql = "UPDATE " . $this->ecs->table('suppliers') . "
                SET suppliers_name = '$name'
                WHERE suppliers_id = '$id'";
                if ($result = $this->db->query($sql)) {
                    // 记日志
                    admin_log($name, 'edit', 'suppliers');

                    clear_cache_files();

                    return make_json_result(stripslashes($name));
                } else {
                    return make_json_result(sprintf($GLOBALS['_LANG']['agency_edit_fail'], $name));
                }
            }
        }

        /**
         * 删除供货商
         */
        if ($_REQUEST['act'] == 'remove') {
            check_authz_json('suppliers_manage');

            $id = intval($_REQUEST['id']);
            $sql = "SELECT *
            FROM " . $this->ecs->table('suppliers') . "
            WHERE suppliers_id = '$id'";
            $suppliers = $this->db->getRow($sql, true);

            if ($suppliers['suppliers_id']) {
                // 判断供货商是否存在订单
                $sql = "SELECT COUNT(*)
                FROM " . $this->ecs->table('order_info') . "AS O, " . $this->ecs->table('order_goods') . " AS OG, " . $this->ecs->table('goods') . " AS G
                WHERE O.order_id = OG.order_id
                AND OG.goods_id = G.goods_id
                AND G.suppliers_id = '$id'";
                $order_exists = $this->db->getOne($sql, true);
                if ($order_exists > 0) {
                    $url = 'suppliers.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
                    return $this->redirect($url);
                }

                // 判断供货商是否存在商品
                $sql = "SELECT COUNT(*)
                FROM " . $this->ecs->table('goods') . "AS G
                WHERE G.suppliers_id = '$id'";
                $goods_exists = $this->db->getOne($sql, true);
                if ($goods_exists > 0) {
                    $url = 'suppliers.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
                    return $this->redirect($url);
                }

                $sql = "DELETE FROM " . $this->ecs->table('suppliers') . "
            WHERE suppliers_id = '$id'";
                $this->db->query($sql);

                // 删除管理员、发货单关联、退货单关联和订单关联的供货商
                $table_array = ['admin_user', 'delivery_order', 'back_order'];
                foreach ($table_array as $value) {
                    $sql = "DELETE FROM " . $this->ecs->table($value) . " WHERE suppliers_id = '$id'";
                    $this->db->query($sql, 'SILENT');
                }

                // 记日志
                admin_log($suppliers['suppliers_name'], 'remove', 'suppliers');

                // 清除缓存
                clear_cache_files();
            }

            $url = 'suppliers.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
            return $this->redirect($url);
        }

        /**
         * 修改供货商状态
         */
        if ($_REQUEST['act'] == 'is_check') {
            check_authz_json('suppliers_manage');

            $id = intval($_REQUEST['id']);
            $sql = "SELECT suppliers_id, is_check
            FROM " . $this->ecs->table('suppliers') . "
            WHERE suppliers_id = '$id'";
            $suppliers = $this->db->getRow($sql, true);

            if ($suppliers['suppliers_id']) {
                $_suppliers['is_check'] = empty($suppliers['is_check']) ? 1 : 0;
                $this->db->autoExecute($this->ecs->table('suppliers'), $_suppliers, '', "suppliers_id = '$id'");
                clear_cache_files();
                return make_json_result($_suppliers['is_check']);
            }

            exit;
        }

        /**
         * 批量操作
         */
        if ($_REQUEST['act'] == 'batch') {
            // 取得要操作的记录编号
            if (empty($_POST['checkboxes'])) {
                return sys_msg($GLOBALS['_LANG']['no_record_selected']);
            } else {
                // 检查权限
                admin_priv('suppliers_manage');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['remove'])) {
                    $sql = "SELECT *
                    FROM " . $this->ecs->table('suppliers') . "
                    WHERE suppliers_id " . db_create_in($ids);
                    $suppliers = $this->db->getAll($sql);

                    foreach ($suppliers as $key => $value) {
                        // 判断供货商是否存在订单
                        $sql = "SELECT COUNT(*)
                        FROM " . $this->ecs->table('order_info') . "AS O, " . $this->ecs->table('order_goods') . " AS OG, " . $this->ecs->table('goods') . " AS G
                        WHERE O.order_id = OG.order_id
                        AND OG.goods_id = G.goods_id
                        AND G.suppliers_id = '" . $value['suppliers_id'] . "'";
                        $order_exists = $this->db->getOne($sql, true);
                        if ($order_exists > 0) {
                            unset($suppliers[$key]);
                        }

                        // 判断供货商是否存在商品
                        $sql = "SELECT COUNT(*)
                        FROM " . $this->ecs->table('goods') . "AS G
                        WHERE G.suppliers_id = '" . $value['suppliers_id'] . "'";
                        $goods_exists = $this->db->getOne($sql, true);
                        if ($goods_exists > 0) {
                            unset($suppliers[$key]);
                        }
                    }
                    if (empty($suppliers)) {
                        return sys_msg($GLOBALS['_LANG']['batch_drop_no']);
                    }


                    $sql = "DELETE FROM " . $this->ecs->table('suppliers') . "
                WHERE suppliers_id " . db_create_in($ids);
                    $this->db->query($sql);

                    // 更新管理员、发货单关联、退货单关联和订单关联的供货商
                    $table_array = ['admin_user', 'delivery_order', 'back_order'];
                    foreach ($table_array as $value) {
                        $sql = "DELETE FROM " . $this->ecs->table($value) . " WHERE suppliers_id " . db_create_in($ids) . " ";
                        $this->db->query($sql, 'SILENT');
                    }

                    // 记日志
                    $suppliers_names = '';
                    foreach ($suppliers as $value) {
                        $suppliers_names .= $value['suppliers_name'] . '|';
                    }
                    admin_log($suppliers_names, 'remove', 'suppliers');

                    // 清除缓存
                    clear_cache_files();

                    return sys_msg($GLOBALS['_LANG']['batch_drop_ok']);
                }
            }
        }

        /**
         * 添加、编辑供货商
         */
        if (in_array($_REQUEST['act'], ['add', 'edit'])) {
            // 检查权限
            admin_priv('suppliers_manage');

            if ($_REQUEST['act'] == 'add') {
                $suppliers = [];

                // 取得所有管理员，
                // 标注哪些是该供货商的('this')，哪些是空闲的('free')，哪些是别的供货商的('other')
                // 排除是办事处的管理员
                $sql = "SELECT user_id, user_name, CASE
                WHEN suppliers_id = 0 THEN 'free'
                ELSE 'other' END AS type
                FROM " . $this->ecs->table('admin_user') . "
                WHERE agency_id = 0
                AND action_list <> 'all'";
                $suppliers['admin_list'] = $this->db->getAll($sql);

                $this->smarty->assign('ur_here', $GLOBALS['_LANG']['add_suppliers']);
                $this->smarty->assign('action_link', ['href' => 'suppliers.php?act=list', 'text' => $GLOBALS['_LANG']['suppliers_list']]);

                $this->smarty->assign('form_action', 'insert');
                $this->smarty->assign('suppliers', $suppliers);


                return $this->smarty->display('suppliers_info.htm');
            }
            if ($_REQUEST['act'] == 'edit') {
                $suppliers = [];

                // 取得供货商信息
                $id = $_REQUEST['id'];
                $sql = "SELECT * FROM " . $this->ecs->table('suppliers') . " WHERE suppliers_id = '$id'";
                $suppliers = $this->db->getRow($sql);
                if (count($suppliers) <= 0) {
                    return sys_msg('suppliers does not exist');
                }

                // 取得所有管理员，
                // 标注哪些是该供货商的('this')，哪些是空闲的('free')，哪些是别的供货商的('other')
                // 排除是办事处的管理员
                $sql = "SELECT user_id, user_name, CASE
                WHEN suppliers_id = '$id' THEN 'this'
                WHEN suppliers_id = 0 THEN 'free'
                ELSE 'other' END AS type
                FROM " . $this->ecs->table('admin_user') . "
                WHERE agency_id = 0
                AND action_list <> 'all'";
                $suppliers['admin_list'] = $this->db->getAll($sql);

                $this->smarty->assign('ur_here', $GLOBALS['_LANG']['edit_suppliers']);
                $this->smarty->assign('action_link', ['href' => 'suppliers.php?act=list', 'text' => $GLOBALS['_LANG']['suppliers_list']]);

                $this->smarty->assign('form_action', 'update');
                $this->smarty->assign('suppliers', $suppliers);


                return $this->smarty->display('suppliers_info.htm');
            }
        }

        /**
         * 提交添加、编辑供货商
         */
        if (in_array($_REQUEST['act'], ['insert', 'update'])) {
            // 检查权限
            admin_priv('suppliers_manage');

            if ($_REQUEST['act'] == 'insert') {
                // 提交值
                $suppliers = ['suppliers_name' => trim($_POST['suppliers_name']),
                    'suppliers_desc' => trim($_POST['suppliers_desc']),
                    'parent_id' => 0
                ];

                // 判断名称是否重复
                $sql = "SELECT suppliers_id
                FROM " . $this->ecs->table('suppliers') . "
                WHERE suppliers_name = '" . $suppliers['suppliers_name'] . "' ";
                if ($this->db->getOne($sql)) {
                    return sys_msg($GLOBALS['_LANG']['suppliers_name_exist']);
                }

                $this->db->autoExecute($this->ecs->table('suppliers'), $suppliers, 'INSERT');
                $suppliers['suppliers_id'] = $this->db->insert_id();

                if (isset($_POST['admins'])) {
                    $sql = "UPDATE " . $this->ecs->table('admin_user') . " SET suppliers_id = '" . $suppliers['suppliers_id'] . "', action_list = '" . SUPPLIERS_ACTION_LIST . "' WHERE user_id " . db_create_in($_POST['admins']);
                    $this->db->query($sql);
                }

                // 记日志
                admin_log($suppliers['suppliers_name'], 'add', 'suppliers');

                // 清除缓存
                clear_cache_files();

                // 提示信息
                $links = [['href' => 'suppliers.php?act=add', 'text' => $GLOBALS['_LANG']['continue_add_suppliers']],
                    ['href' => 'suppliers.php?act=list', 'text' => $GLOBALS['_LANG']['back_suppliers_list']]
                ];
                return sys_msg($GLOBALS['_LANG']['add_suppliers_ok'], 0, $links);
            }

            if ($_REQUEST['act'] == 'update') {
                // 提交值
                $suppliers = ['id' => trim($_POST['id'])];

                $suppliers['new'] = ['suppliers_name' => trim($_POST['suppliers_name']),
                    'suppliers_desc' => trim($_POST['suppliers_desc'])
                ];

                // 取得供货商信息
                $sql = "SELECT * FROM " . $this->ecs->table('suppliers') . " WHERE suppliers_id = '" . $suppliers['id'] . "'";
                $suppliers['old'] = $this->db->getRow($sql);
                if (empty($suppliers['old']['suppliers_id'])) {
                    return sys_msg('suppliers does not exist');
                }

                // 判断名称是否重复
                $sql = "SELECT suppliers_id
                FROM " . $this->ecs->table('suppliers') . "
                WHERE suppliers_name = '" . $suppliers['new']['suppliers_name'] . "'
                AND suppliers_id <> '" . $suppliers['id'] . "'";
                if ($this->db->getOne($sql)) {
                    return sys_msg($GLOBALS['_LANG']['suppliers_name_exist']);
                }

                // 保存供货商信息
                $this->db->autoExecute($this->ecs->table('suppliers'), $suppliers['new'], 'UPDATE', "suppliers_id = '" . $suppliers['id'] . "'");

                // 清空供货商的管理员
                $sql = "UPDATE " . $this->ecs->table('admin_user') . " SET suppliers_id = 0, action_list = '" . SUPPLIERS_ACTION_LIST . "' WHERE suppliers_id = '" . $suppliers['id'] . "'";
                $this->db->query($sql);

                // 添加供货商的管理员
                if (isset($_POST['admins'])) {
                    $sql = "UPDATE " . $this->ecs->table('admin_user') . " SET suppliers_id = '" . $suppliers['old']['suppliers_id'] . "' WHERE user_id " . db_create_in($_POST['admins']);
                    $this->db->query($sql);
                }

                // 记日志
                admin_log($suppliers['old']['suppliers_name'], 'edit', 'suppliers');

                // 清除缓存
                clear_cache_files();

                // 提示信息
                $links[] = ['href' => 'suppliers.php?act=list', 'text' => $GLOBALS['_LANG']['back_suppliers_list']];
                return sys_msg($GLOBALS['_LANG']['edit_suppliers_ok'], 0, $links);
            }
        }
    }

    /**
     *  获取供应商列表信息
     *
     * @access  public
     * @param
     *
     * @return void
     */
    private function suppliers_list()
    {
        $result = get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

            // 过滤信息
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'suppliers_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);

            $where = 'WHERE 1 ';

            // 分页大小
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            $cp_page_size = request()->cookie('cp_page_size');
            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } elseif (!empty($cp_page_size) && intval($cp_page_size) > 0) {
                $filter['page_size'] = intval($cp_page_size);
            } else {
                $filter['page_size'] = 15;
            }

            // 记录总数
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('suppliers') . $where;
            $filter['record_count'] = $GLOBALS['db']->getOne($sql);
            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

            // 查询
            $sql = "SELECT suppliers_id, suppliers_name, suppliers_desc, is_check
                FROM " . $GLOBALS['ecs']->table("suppliers") . "
                $where
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'] . "
                LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";

            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $row = $GLOBALS['db']->getAll($sql);

        $arr = ['result' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}