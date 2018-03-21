<?php

namespace App\Admin\Controller;

/**
 * Class AttentionListController
 * @package App\Admin\Controller
 */
class AttentionListController extends BaseController
{
    public function actionIndex()
    {
        admin_priv('attention_list');

        /**
         * 列表
         */
        if ($_REQUEST['act'] == 'list') {
            $goodsdb = $this->get_attention();
            $this->smarty->assign('full_page', 1);
            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['attention_list']);
            $this->smarty->assign('goodsdb', $goodsdb['goodsdb']);
            $this->smarty->assign('filter', $goodsdb['filter']);
            $this->smarty->assign('cfg_lang', $GLOBALS['_CFG']['lang']);
            $this->smarty->assign('record_count', $goodsdb['record_count']);
            $this->smarty->assign('page_count', $goodsdb['page_count']);

            return $this->smarty->display('attention_list.htm');
        }

        /**
         * 查询
         */
        if ($_REQUEST['act'] == 'query') {
            $goodsdb = $this->get_attention();
            $this->smarty->assign('goodsdb', $goodsdb['goodsdb']);
            $this->smarty->assign('filter', $goodsdb['filter']);
            $this->smarty->assign('record_count', $goodsdb['record_count']);
            $this->smarty->assign('page_count', $goodsdb['page_count']);
            return make_json_result($this->smarty->fetch('attention_list.htm'), '',
                ['filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']]);
        }

        /**
         * 添加
         */
        if ($_REQUEST['act'] == 'addtolist') {
            $id = intval($_REQUEST['id']);
            $pri = (intval($_REQUEST['pri']) == 1) ? 1 : 0;
            $start = empty($_GET['start']) ? 0 : (int)$_GET['start'];

            $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('goods') . " g"
                . " LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c"
                . " ON g.goods_id = c.goods_id"
                . " LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u"
                . " ON c.user_id = u.user_id" .
                " WHERE c.is_attention = 1 AND g.is_delete = 0 AND c.goods_id = '$id'";

            $count = $this->db->getOne($sql);

            if ($count > $start) {
                $sql = "SELECT u.user_name, u.email, g.goods_name, g.goods_id FROM " . $GLOBALS['ecs']->table('goods') . " g LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c ON g.goods_id = c.goods_id LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u ON c.user_id = u.user_id" .
                    " WHERE c.is_attention = 1 AND g.is_delete = 0 AND c.goods_id = '$id' LIMIT $start,100";
                $query = $this->db->query($sql);
                $add = '';
                $template = $this->db->getRow("SELECT * FROM " . $this->ecs->table('mail_templates') . " WHERE  template_code = 'attention_list' AND type = 'template'");

                $i = 0;
                foreach ($query as $rt) {
                    $time = time();
                    $goods_url = $this->ecs->url() . build_uri('goods', ['gid' => $id], $rt['goods_name']);
                    $this->smarty->assign(['user_name' => $rt['user_name'], 'goods_name' => $rt['goods_name'], 'goods_url' => $goods_url, 'shop_name' => $GLOBALS['_CFG']['shop_title'], 'send_date' => local_date($GLOBALS['_CFG']['date_format'])]);
                    $content = $this->smarty->fetch("str:$template[template_content]");
                    $add .= $add ? ",('$rt[email]','$template[template_id]','$content','$pri','$time')" : "('$rt[email]','$template[template_id]','$content','$pri','$time')";
                    $i++;
                }
                if ($add) {
                    $sql = "INSERT INTO " . $this->ecs->table('email_sendlist') . " (email,template_id,email_content,pri,last_send) VALUES " . $add;
                    $this->db->query($sql);
                }
                if ($i == 100) {
                    $start = $start + 100;
                } else {
                    $start = $start + $i;
                }
                $links[] = ['text' => sprintf($GLOBALS['_LANG']['finish_list'], $start), 'href' => "attention_list.php?act=addtolist&id=$id&pri=$pri&start=$start"];
                return sys_msg($GLOBALS['_LANG']['finishing'], 0, $links);
            } else {
                $links[] = ['text' => $GLOBALS['_LANG']['attention_list'], 'href' => 'attention_list.php?act=list'];
                return sys_msg($GLOBALS['_LANG']['edit_ok'], 0, $links);
            }
        }

        /**
         * 批量添加
         */
        if ($_REQUEST['act'] == 'batch_addtolist') {
            $olddate = $_REQUEST['date'];
            $date = local_strtotime(trim($_REQUEST['date']));
            $pri = (intval($_REQUEST['pri']) == 1) ? 1 : 0;
            $start = empty($_GET['start']) ? 0 : (int)$_GET['start'];

            $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('goods') . " g"
                . " LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c"
                . " ON g.goods_id = c.goods_id"
                . " LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u"
                . " ON c.user_id = u.user_id" .
                " WHERE c.is_attention = 1 AND g.is_delete = 0 AND g.last_update >= '$date'";

            $count = $this->db->getOne($sql);

            if ($count > $start) {
                $sql = "SELECT u.user_name, u.email, g.goods_name, g.goods_id FROM " . $GLOBALS['ecs']->table('goods') . " g LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c ON g.goods_id = c.goods_id LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u ON c.user_id = u.user_id" .
                    " WHERE c.is_attention = 1 AND g.is_delete = 0 AND g.last_update >= '$date' LIMIT $start,100";
                $query = $this->db->query($sql);
                $add = '';

                $template = $this->db->getRow("SELECT * FROM " . $this->ecs->table('mail_templates') . " WHERE  template_code = 'attention_list' AND type = 'template'");

                $i = 0;
                foreach ($query as $rt) {
                    $time = time();

                    $goods_url = $this->ecs->url() . build_uri('goods', ['gid' => $rt['goods_id']], $rt['user_name']);

                    $this->smarty->assign(['user_name' => $rt['user_name'], 'goods_name' => $rt['goods_name'], 'goods_url' => $goods_url]);
                    $content = $this->smarty->fetch("str:$template[template_content]");
                    $add .= $add ? ",('$rt[email]','$template[template_id]','$content','$pri','$time')" : "('$rt[email]','$template[template_id]','$content','$pri','$time')";
                    $i++;
                }
                if ($add) {
                    $sql = "INSERT INTO " . $this->ecs->table('email_sendlist') . " (email,template_id,email_content,pri,last_send) VALUES " . $add;
                    $this->db->query($sql);
                }
                if ($i == 100) {
                    $start = $start + 100;
                } else {
                    $start = $start + $i;
                }
                $links[] = ['text' => sprintf($GLOBALS['_LANG']['finish_list'], $start), 'href' => "attention_list.php?act=batch_addtolist&date=$olddate&pri=$pri&start=$start"];
                return sys_msg($GLOBALS['_LANG']['finishing'], 0, $links);
            } else {
                $links[] = ['text' => $GLOBALS['_LANG']['attention_list'], 'href' => 'attention_list.php?act=list'];
                return sys_msg($GLOBALS['_LANG']['edit_ok'], 0, $links);
            }
        }
    }

    /**
     * @return array
     */
    private function get_attention()
    {
        $result = get_filter();

        if ($result === false) {
            $where = 'WHERE c.is_attention = 1 AND g.is_delete = 0 ';
            if (!empty($_POST['goods_name'])) {
                $goods_name = trim($_POST['goods_name']);
                $where .= " AND g.goods_name LIKE '%$goods_name%'";
                $filter['goods_name'] = $goods_name;
            }

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'last_update' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $sql = "SELECT COUNT(DISTINCT c.goods_id) FROM " . $GLOBALS['ecs']->table('collect_goods') . " c " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON c.goods_id = g.goods_id " .
                $where;

            $filter['record_count'] = $GLOBALS['db']->getOne($sql);

            // 分页大小
            $filter = page_and_size($filter);

            // 查询
            $sql = "SELECT DISTINCT c.goods_id, g.goods_name, g.last_update FROM " . $GLOBALS['ecs']->table('collect_goods') . " c " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON c.goods_id = g.goods_id " .
                $where .
                " ORDER BY " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ",$filter[page_size]";
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $goodsdb = $GLOBALS['db']->getAll($sql);
        foreach ($goodsdb as $k => $v) {
            $goodsdb[$k]['last_update'] = local_date('Y-m-d', $v['last_update']);
        }

        $arr = ['goodsdb' => $goodsdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
        return $arr;
    }
}