<?php

namespace App\Dashboard\Controllers;

/**
 * 专题管理
 * Class TopicController
 * @package App\Dashboard\Controllers
 */
class TopicController extends InitController
{
    public function index()
    {
        // 配置风格颜色选项
        $topic_style_color = [
            '0' => '008080',
            '1' => '008000',
            '2' => 'ffa500',
            '3' => 'ff0000',
            '4' => 'ffff00',
            '5' => '9acd32',
            '6' => 'ffd700'
        ];
        $allow_suffix = ['gif', 'jpg', 'png', 'jpeg', 'bmp', 'swf'];

        /**
         * 专题列表页面
         */
        if ($_REQUEST['act'] == 'list') {
            admin_priv('topic_manage');

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['09_topic']);

            $this->smarty->assign('full_page', 1);
            $list = $this->get_topic_list();

            $this->smarty->assign('topic_list', $list['item']);
            $this->smarty->assign('filter', $list['filter']);
            $this->smarty->assign('record_count', $list['record_count']);
            $this->smarty->assign('page_count', $list['page_count']);

            $sort_flag = sort_flag($list['filter']);
            $this->smarty->assign($sort_flag['tag'], $sort_flag['img']);


            $this->smarty->assign('action_link', ['text' => $GLOBALS['_LANG']['topic_add'], 'href' => 'topic.php?act=add']);
            return $this->smarty->display('topic_list.htm');
        }

        /**
         * 添加,编辑
         */
        if ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit') {
            admin_priv('topic_manage');

            $isadd = $_REQUEST['act'] == 'add';
            $this->smarty->assign('isadd', $isadd);
            $topic_id = empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']);

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['09_topic']);
            $this->smarty->assign('action_link', $this->list_link($isadd));

            $this->smarty->assign('cat_list', cat_list(0, 1));
            $this->smarty->assign('brand_list', get_brand_list());
            $this->smarty->assign('cfg_lang', $GLOBALS['_CFG']['lang']);
            $this->smarty->assign('topic_style_color', $topic_style_color);

            $width_height = $this->get_toppic_width_height();
            if (isset($width_height['pic']['width']) && isset($width_height['pic']['height'])) {
                $this->smarty->assign('width_height', sprintf($GLOBALS['_LANG']['tips_width_height'], $width_height['pic']['width'], $width_height['pic']['height']));
            }
            if (isset($width_height['title_pic']['width']) && isset($width_height['title_pic']['height'])) {
                $this->smarty->assign('title_width_height', sprintf($GLOBALS['_LANG']['tips_title_width_height'], $width_height['title_pic']['width'], $width_height['title_pic']['height']));
            }

            if (!$isadd) {
                $sql = "SELECT * FROM " . $this->ecs->table('topic') . " WHERE topic_id = '$topic_id'";
                $topic = $this->db->getRow($sql);
                $topic['start_time'] = local_date('Y-m-d', $topic['start_time']);
                $topic['end_time'] = local_date('Y-m-d', $topic['end_time']);

                create_html_editor('topic_intro', $topic['intro']);


                $json = new Json();
                $topic['data'] = addcslashes($topic['data'], "'");
                $topic['data'] = $json->encode(@unserialize($topic['data']));
                $topic['data'] = addcslashes($topic['data'], "'");

                if (empty($topic['topic_img']) && empty($topic['htmls'])) {
                    $topic['topic_type'] = 0;
                } elseif ($topic['htmls'] != '') {
                    $topic['topic_type'] = 2;
                } elseif (preg_match('/.swf$/i', $topic['topic_img'])) {
                    $topic['topic_type'] = 1;
                } else {
                    $topic['topic_type'] = '';
                }

                $this->smarty->assign('topic', $topic);
                $this->smarty->assign('act', "update");
            } else {
                $topic = ['title' => '', 'topic_type' => 0, 'url' => 'http://'];
                $this->smarty->assign('topic', $topic);

                create_html_editor('topic_intro');
                $this->smarty->assign('act', "insert");
            }
            return $this->smarty->display('topic_edit.htm');
        }

        if ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update') {
            admin_priv('topic_manage');

            $is_insert = $_REQUEST['act'] == 'insert';
            $topic_id = empty($_POST['topic_id']) ? 0 : intval($_POST['topic_id']);
            $topic_type = empty($_POST['topic_type']) ? 0 : intval($_POST['topic_type']);

            switch ($topic_type) {
                case '0':
                case '1':

                    // 主图上传
                    if ($_FILES['topic_img']['name'] && $_FILES['topic_img']['size'] > 0) {
                        // 检查文件合法性
                        if (!get_file_suffix($_FILES['topic_img']['name'], $allow_suffix)) {
                            return sys_msg($GLOBALS['_LANG']['invalid_type']);
                        }

                        // 处理
                        $name = date('Ymd');
                        for ($i = 0; $i < 6; $i++) {
                            $name .= chr(mt_rand(97, 122));
                        }
                        $name .= '.' . end(explode('.', $_FILES['topic_img']['name']));
                        $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;

                        if (move_upload_file($_FILES['topic_img']['tmp_name'], $target)) {
                            $topic_img = DATA_DIR . '/afficheimg/' . $name;
                        }
                    } elseif (!empty($_REQUEST['url'])) {
                        // 来自互联网图片 不可以是服务器地址
                        if (strstr($_REQUEST['url'], 'http') && !strstr($_REQUEST['url'], $_SERVER['SERVER_NAME'])) {
                            // 取互联网图片至本地
                            $topic_img = $this->get_url_image($_REQUEST['url']);
                        } else {
                            return sys_msg($GLOBALS['_LANG']['web_url_no']);
                        }
                    }
                    unset($name, $target);

                    $topic_img = empty($topic_img) ? $_POST['img_url'] : $topic_img;
                    $htmls = '';

                    break;

                case '2':

                    $htmls = $_POST['htmls'];

                    $topic_img = '';

                    break;
            }

            // 标题图上传
            if ($_FILES['title_pic']['name'] && $_FILES['title_pic']['size'] > 0) {
                // 检查文件合法性
                if (!get_file_suffix($_FILES['title_pic']['name'], $allow_suffix)) {
                    return sys_msg($GLOBALS['_LANG']['invalid_type']);
                }

                // 处理
                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }
                $name .= '.' . end(explode('.', $_FILES['title_pic']['name']));
                $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;

                if (move_upload_file($_FILES['title_pic']['tmp_name'], $target)) {
                    $title_pic = DATA_DIR . '/afficheimg/' . $name;
                }
            } elseif (!empty($_REQUEST['title_url'])) {
                // 来自互联网图片 不可以是服务器地址
                if (strstr($_REQUEST['title_url'], 'http') && !strstr($_REQUEST['title_url'], $_SERVER['SERVER_NAME'])) {
                    // 取互联网图片至本地
                    $title_pic = $this->get_url_image($_REQUEST['title_url']);
                } else {
                    return sys_msg($GLOBALS['_LANG']['web_url_no']);
                }
            }
            unset($name, $target);

            $title_pic = empty($title_pic) ? $_POST['title_img_url'] : $title_pic;


            $start_time = local_strtotime($_POST['start_time']);
            $end_time = local_strtotime($_POST['end_time']);

            $json = new Json();
            $tmp_data = $json->decode($_POST['topic_data']);
            $data = serialize($tmp_data);
            $base_style = $_POST['base_style'];
            $keywords = $_POST['keywords'];
            $description = $_POST['description'];

            if ($is_insert) {
                $sql = "INSERT INTO " . $this->ecs->table('topic') . " (title,start_time,end_time,data,intro,template,css,topic_img,title_pic,base_style, htmls,keywords,description)" .
                    "VALUES ('$_POST[topic_name]','$start_time','$end_time','$data','$_POST[topic_intro]','$_POST[topic_template_file]','$_POST[topic_css]', '$topic_img', '$title_pic', '$base_style', '$htmls','$keywords','$description')";
            } else {
                $sql = "UPDATE " . $this->ecs->table('topic') .
                    "SET title='$_POST[topic_name]',start_time='$start_time',end_time='$end_time',data='$data',intro='$_POST[topic_intro]',template='$_POST[topic_template_file]',css='$_POST[topic_css]', topic_img='$topic_img', title_pic='$title_pic', base_style='$base_style', htmls='$htmls', keywords='$keywords', description='$description'" .
                    " WHERE topic_id='$topic_id' LIMIT 1";
            }

            $this->db->query($sql);

            clear_cache_files();

            $links[] = ['href' => 'topic.php', 'text' => $GLOBALS['_LANG']['back_list']];
            return sys_msg($GLOBALS['_LANG']['succed'], 0, $links);
        }

        if ($_REQUEST['act'] == 'get_goods_list') {
            $json = new Json();

            $filters = $json->decode($_GET['JSON']);

            $arr = get_goods_list($filters);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = ['value' => $val['goods_id'],
                    'text' => $val['goods_name']];
            }

            return make_json_result($opt);
        }

        if ($_REQUEST["act"] == "delete") {
            admin_priv('topic_manage');

            $sql = "DELETE FROM " . $this->ecs->table('topic') . " WHERE ";

            if (!empty($_POST['checkboxs'])) {
                $sql .= db_create_in($_POST['checkboxs'], 'topic_id');
            } elseif (!empty($_GET['id'])) {
                $_GET['id'] = intval($_GET['id']);
                $sql .= "topic_id = '$_GET[id]'";
            } else {
                exit;
            }

            $this->db->query($sql);

            clear_cache_files();

            if (!empty($_REQUEST['is_ajax'])) {
                $url = 'topic.php?act=query&' . str_replace('act=delete', '', $_SERVER['QUERY_STRING']);
                $this->redirect($url);
            }

            $links[] = ['href' => 'topic.php', 'text' => $GLOBALS['_LANG']['back_list']];
            return sys_msg($GLOBALS['_LANG']['succed'], 0, $links);
        }

        if ($_REQUEST["act"] == "query") {
            $topic_list = $this->get_topic_list();
            $this->smarty->assign('topic_list', $topic_list['item']);
            $this->smarty->assign('filter', $topic_list['filter']);
            $this->smarty->assign('record_count', $topic_list['record_count']);
            $this->smarty->assign('page_count', $topic_list['page_count']);
            $this->smarty->assign('use_storage', empty($GLOBALS['_CFG']['use_storage']) ? 0 : 1);

            // 排序标记
            $sort_flag = sort_flag($topic_list['filter']);
            $this->smarty->assign($sort_flag['tag'], $sort_flag['img']);

            $tpl = 'topic_list.htm';
            return make_json_result($this->smarty->fetch($tpl), '', ['filter' => $topic_list['filter'], 'page_count' => $topic_list['page_count']]);
        }
    }

    /**
     * 获取专题列表
     * @access  public
     * @return void
     */
    private function get_topic_list()
    {
        $result = get_filter();
        if ($result === false) {
            // 查询条件
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'topic_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('topic');
            $filter['record_count'] = $GLOBALS['db']->getOne($sql);

            // 分页大小
            $filter = page_and_size($filter);

            $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('topic') . " ORDER BY $filter[sort_by] $filter[sort_order]";

            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $query = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

        $res = [];

        foreach ($query as $topic) {
            $topic['start_time'] = local_date('Y-m-d', $topic['start_time']);
            $topic['end_time'] = local_date('Y-m-d', $topic['end_time']);
            $topic['url'] = $GLOBALS['ecs']->url() . 'topic.php?topic_id=' . $topic['topic_id'];
            $res[] = $topic;
        }

        $arr = ['item' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 列表链接
     * @param   bool $is_add 是否添加（插入）
     * @param   string $text 文字
     * @return  array('href' => $href, 'text' => $text)
     */
    private function list_link($is_add = true, $text = '')
    {
        $href = 'topic.php?act=list';
        if (!$is_add) {
            $href .= '&' . list_link_postfix();
        }
        if ($text == '') {
            $text = $GLOBALS['_LANG']['topic_list'];
        }

        return ['href' => $href, 'text' => $text];
    }

    private function get_toppic_width_height()
    {
        $width_height = [];

        $file_path = ROOT_PATH . 'themes/' . $GLOBALS['_CFG']['template'] . '/topic.dwt';
        if (!file_exists($file_path) || !is_readable($file_path)) {
            return $width_height;
        }

        $string = file_get_contents($file_path);

        $pattern_width = '/var\s*topic_width\s*=\s*"(\d+)";/';
        $pattern_height = '/var\s*topic_height\s*=\s*"(\d+)";/';
        preg_match($pattern_width, $string, $width);
        preg_match($pattern_height, $string, $height);
        if (isset($width[1])) {
            $width_height['pic']['width'] = $width[1];
        }
        if (isset($height[1])) {
            $width_height['pic']['height'] = $height[1];
        }
        unset($width, $height);

        $pattern_width = '/TitlePicWidth:\s{1}(\d+)/';
        $pattern_height = '/TitlePicHeight:\s{1}(\d+)/';
        preg_match($pattern_width, $string, $width);
        preg_match($pattern_height, $string, $height);
        if (isset($width[1])) {
            $width_height['title_pic']['width'] = $width[1];
        }
        if (isset($height[1])) {
            $width_height['title_pic']['height'] = $height[1];
        }

        return $width_height;
    }

    private function get_url_image($url)
    {
        $ext = strtolower(end(explode('.', $url)));
        if ($ext != "gif" && $ext != "jpg" && $ext != "png" && $ext != "bmp" && $ext != "jpeg") {
            return $url;
        }

        $name = date('Ymd');
        for ($i = 0; $i < 6; $i++) {
            $name .= chr(mt_rand(97, 122));
        }
        $name .= '.' . $ext;
        $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;

        $tmp_file = DATA_DIR . '/afficheimg/' . $name;
        $filename = ROOT_PATH . $tmp_file;

        $img = file_get_contents($url);

        $fp = @fopen($filename, "a");
        fwrite($fp, $img);
        fclose($fp);

        return $tmp_file;
    }
}