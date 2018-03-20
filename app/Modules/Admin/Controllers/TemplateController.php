<?php

namespace App\Modules\Admin\Controllers;

/**
 * 模版管理
 * Class TemplateController
 * @package App\Modules\Admin\Controllers
 */
class TemplateController extends BaseController
{
    public function actionIndex()
    {
        load_helper('template', 'admin');

        /**
         * 模版列表
         */
        if ($_REQUEST['act'] == 'list') {
            admin_priv('template_select');

            // 获得当前的模版的信息
            $curr_template = $GLOBALS['_CFG']['template'];
            $curr_style = $GLOBALS['_CFG']['stylename'];

            // 获得可用的模版
            $available_templates = [];
            $template_path = resource_path('themes');
            $template_dir = glob($template_path);
            foreach ($template_dir as $file) {
                if ($file != '.' && $file != '..' && is_dir($template_path . '/' . $file) && $file != '.svn' && $file != 'index.htm') {
                    $available_templates[] = get_template_info($file);
                }
            }
            @closedir($template_dir);

            // 获得可用的模版的可选风格数组
            $templates_style = [];
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $templates_style[$value['code']] = $this->read_tpl_style($value['code'], 2);
                }
            }

            // 清除不需要的模板设置
            $available_code = [];
            $sql = "DELETE FROM " . $this->ecs->table('template') . " WHERE 1 ";
            foreach ($available_templates as $tmp) {
                $sql .= " AND theme <> '" . $tmp['code'] . "' ";
                $available_code[] = $tmp['code'];
            }
            $tmp_bak_path = storage_path('temp/backup/library');
            $tmp_bak_dir = glob($tmp_bak_path);
            foreach ($tmp_bak_dir as $file) {
                if ($file != '.' && $file != '..' && $file != '.svn' && $file != 'index.htm' && is_file($tmp_bak_path . '/' . $file) == true) {
                    $code = substr($file, 0, strpos($file, '-'));
                    if (!in_array($code, $available_code)) {
                        @unlink($tmp_bak_path . '/' . $file);
                    }
                }
            }

            $this->db->query($sql);

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['template_manage']);
            $this->smarty->assign('curr_tpl_style', $curr_style);
            $this->smarty->assign('template_style', $templates_style);
            $this->smarty->assign('curr_template', get_template_info($curr_template, $curr_style));
            $this->smarty->assign('available_templates', $available_templates);

            return $this->smarty->display('templates_list.htm');
        }

        /**
         * 设置模板的内容
         */
        if ($_REQUEST['act'] == 'setup') {
            admin_priv('template_setup');

            $template_theme = $GLOBALS['_CFG']['template'];
            $curr_template = empty($_REQUEST['template_file']) ? 'index' : $_REQUEST['template_file'];

            $temp_options = [];
            $temp_regions = get_template_region($template_theme, $curr_template . '.dwt', false);
            $temp_libs = get_template_region($template_theme, $curr_template . '.dwt', true);

            $editable_libs = get_editable_libs($curr_template, $page_libs[$curr_template]);

            if (empty($editable_libs)) {
                // 获取数据库中数据，并跟模板中数据核对,并设置动态内容
                // 固定内容
                foreach ($page_libs[$curr_template] as $val => $number_enabled) {
                    $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
                    if (!in_array($lib, $GLOBALS['dyna_libs'])) {
                        // 先排除动态内容
                        $temp_options[$lib] = get_setted($val, $temp_libs);
                        $temp_options[$lib]['desc'] = $GLOBALS['_LANG']['template_libs'][$lib];
                        $temp_options[$lib]['library'] = $val;
                        $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                        $temp_options[$lib]['number'] = $number_enabled;
                    }
                }
            } else {
                // 获取数据库中数据，并跟模板中数据核对,并设置动态内容
                // 固定内容
                foreach ($page_libs[$curr_template] as $val => $number_enabled) {
                    $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
                    if (!in_array($lib, $GLOBALS['dyna_libs'])) {
                        // 先排除动态内容
                        $temp_options[$lib] = get_setted($val, $temp_libs);
                        $temp_options[$lib]['desc'] = $GLOBALS['_LANG']['template_libs'][$lib];
                        $temp_options[$lib]['library'] = $val;
                        $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                        $temp_options[$lib]['number'] = $number_enabled;

                        if (!in_array($lib, $editable_libs)) {
                            $temp_options[$lib]['editable'] = 1;
                        }
                    }
                }
            }

            // 动态内容
            $cate_goods = [];
            $brand_goods = [];
            $cat_articles = [];
            $ad_positions = [];

            $sql = "SELECT region, library, sort_order, id, number, type FROM " . $this->ecs->table('template') . " " .
                "WHERE theme='$template_theme' AND filename='$curr_template' AND remarks='' " .
                "ORDER BY region, sort_order ASC ";

            $rc = $this->db->query($sql);
            $db_dyna_libs = [];
            foreach ($rc as $row) {
                if ($row['type'] > 0) {
                    // 动态内容
                    $db_dyna_libs[$row['region']][$row['library']][] = ['id' => $row['id'], 'number' => $row['number'], 'type' => $row['type']];
                } else {
                    // 固定内容
                    $lib = basename(strtolower(substr($row['library'], 0, strpos($row['library'], '.'))));
                    if (isset($lib)) {
                        $temp_options[$lib]['number'] = $row['number'];
                    }
                }
            }

            foreach ($temp_libs as $val) {
                // 对动态内容赋值
                if ($val['lib'] == 'cat_goods') {
                    // 分类下的商品
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $cate_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cats' => cat_list(0, $row['id'])];
                    } else {
                        $cate_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'cats' => cat_list(0)];
                    }
                } elseif ($val['lib'] == 'brand_goods') {
                    // 品牌下的商品
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $brand_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'brand' => $row['id']];
                    } else {
                        $brand_goods[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'brand' => 0];
                    }
                } // 文章列表
                elseif ($val['lib'] == 'cat_articles') {
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $cat_articles[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cat' => article_cat_list(0, $row['id'])];
                    } else {
                        $cat_articles[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'cat' => article_cat_list(0)];
                    }
                } // 广告位
                elseif ($val['lib'] == 'ad_position') {
                    if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']]))) {
                        $ad_positions[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'ad_pos' => $row['id']];
                    } else {
                        $ad_positions[] = ['region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => 0, 'ad_pos' => 0];
                    }
                }
            }

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['03_template_setup']);
            $this->smarty->assign('curr_template_file', $curr_template);
            $this->smarty->assign('temp_options', $temp_options);
            $this->smarty->assign('temp_regions', $temp_regions);
            $this->smarty->assign('cate_goods', $cate_goods);
            $this->smarty->assign('brand_goods', $brand_goods);
            $this->smarty->assign('cat_articles', $cat_articles);
            $this->smarty->assign('ad_positions', $ad_positions);
            $this->smarty->assign('arr_cates', cat_list(0, 0, true));
            $this->smarty->assign('arr_brands', get_brand_list());
            $this->smarty->assign('arr_article_cats', article_cat_list(0, 0, true));
            $this->smarty->assign('arr_ad_positions', get_position_list());
            return $this->smarty->display('template_setup.htm');
        }

        /**
         * 提交模板内容设置
         */
        if ($_REQUEST['act'] == 'setting') {
            admin_priv('template_setup');

            $curr_template = $GLOBALS['_CFG']['template'];
            $this->db->query("DELETE FROM " . $this->ecs->table('template') . " WHERE remarks = '' AND filename = '$_POST[template_file]' AND theme = '$curr_template'");

            // 先处理固定内容
            foreach ($_POST['regions'] as $key => $val) {
                $number = isset($_POST['number'][$key]) ? intval($_POST['number'][$key]) : 0;
                if (!in_array($key, $GLOBALS['dyna_libs']) and (isset($_POST['display'][$key]) and $_POST['display'][$key] == 1 or $number > 0)) {
                    $sql = "INSERT INTO " . $this->ecs->table('template') .
                        "(theme, filename, region, library, sort_order, number)" .
                        " VALUES " .
                        "('$curr_template', '$_POST[template_file]', '$val', '" . $_POST['map'][$key] . "', '" . @$_POST['sort_order'][$key] . "', '$number')";
                    $this->db->query($sql);
                }
            }

            // 分类的商品
            if (isset($_POST['regions']['cat_goods'])) {
                foreach ($_POST['regions']['cat_goods'] as $key => $val) {
                    if ($_POST['categories']['cat_goods'][$key] != '' && intval($_POST['categories']['cat_goods'][$key]) > 0) {
                        $sql = "INSERT INTO " . $this->ecs->table('template') . " (" .
                            "theme, filename, region, library, sort_order, type, id, number" .
                            ") VALUES (" .
                            "'$curr_template', " .
                            "'$_POST[template_file]', '" . $val . "', '/library/cat_goods.lbi', " .
                            "'" . $_POST['sort_order']['cat_goods'][$key] . "', 1, '" . $_POST['categories']['cat_goods'][$key] .
                            "', '" . $_POST['number']['cat_goods'][$key] . "'" .
                            ")";
                        $this->db->query($sql);
                    }
                }
            }

            // 品牌的商品
            if (isset($_POST['regions']['brand_goods'])) {
                foreach ($_POST['regions']['brand_goods'] as $key => $val) {
                    if ($_POST['brands']['brand_goods'][$key] != '' && intval($_POST['brands']['brand_goods'][$key]) > 0) {
                        $sql = "INSERT INTO " . $this->ecs->table('template') . " (" .
                            "theme, filename, region, library, sort_order, type, id, number" .
                            ") VALUES (" .
                            "'$curr_template', " .
                            "'$_POST[template_file]', '" . $val . "', '/library/brand_goods.lbi', " .
                            "'" . $_POST['sort_order']['brand_goods'][$key] . "', 2, '" . $_POST['brands']['brand_goods'][$key] .
                            "', '" . $_POST['number']['brand_goods'][$key] . "'" .
                            ")";
                        $this->db->query($sql);
                    }
                }
            }

            // 文章列表
            if (isset($_POST['regions']['cat_articles'])) {
                foreach ($_POST['regions']['cat_articles'] as $key => $val) {
                    if ($_POST['article_cat']['cat_articles'][$key] != '' && intval($_POST['article_cat']['cat_articles'][$key]) > 0) {
                        $sql = "INSERT INTO " . $this->ecs->table('template') . " (" .
                            "theme, filename, region, library, sort_order, type, id, number" .
                            ") VALUES (" .
                            "'$curr_template', " .
                            "'$_POST[template_file]', '" . $val . "', '/library/cat_articles.lbi', " .
                            "'" . $_POST['sort_order']['cat_articles'][$key] . "', 3, '" . $_POST['article_cat']['cat_articles'][$key] .
                            "', '" . $_POST['number']['cat_articles'][$key] . "'" .
                            ")";
                        $this->db->query($sql);
                    }
                }
            }

            // 广告位
            if (isset($_POST['regions']['ad_position'])) {
                foreach ($_POST['regions']['ad_position'] as $key => $val) {
                    if ($_POST['ad_position'][$key] != '' && intval($_POST['ad_position'][$key]) > 0) {
                        $sql = "INSERT INTO " . $this->ecs->table('template') . " (" .
                            "theme, filename, region, library, sort_order, type, id, number" .
                            ") VALUES (" .
                            "'$curr_template', " .
                            "'$_POST[template_file]', '" . $val . "', '/library/ad_position.lbi', " .
                            "'" . $_POST['sort_order']['ad_position'][$key] . "', 4, '" . $_POST['ad_position'][$key] .
                            "', '" . $_POST['number']['ad_position'][$key] . "'" .
                            ")";
                        $this->db->query($sql);
                    }
                }
            }

            // 对提交内容进行处理
            $post_regions = [];
            foreach ($_POST['regions'] as $key => $val) {
                switch ($key) {
                    case 'cat_goods':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['categories']['cat_goods'][$k]) > 0) {
                                $post_regions[] = ['region' => $v,
                                    'type' => 1,
                                    'number' => $_POST['number']['cat_goods'][$k],
                                    'library' => '/library/' . $key . '.lbi',
                                    'sort_order' => $_POST['sort_order']['cat_goods'][$k],
                                    'id' => $_POST['categories']['cat_goods'][$k]];
                            }
                        }
                        break;
                    case 'brand_goods':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['brands']['brand_goods'][$k]) > 0) {
                                $post_regions[] = ['region' => $v,
                                    'type' => 2,
                                    'number' => $_POST['number']['brand_goods'][$k],
                                    'library' => '/library/' . $key . '.lbi',
                                    'sort_order' => $_POST['sort_order']['brand_goods'][$k],
                                    'id' => $_POST['brands']['brand_goods'][$k]];
                            }
                        }
                        break;
                    case 'cat_articles':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['article_cat']['cat_articles'][$k]) > 0) {
                                $post_regions[] = ['region' => $v,
                                    'type' => 3,
                                    'number' => $_POST['number']['cat_articles'][$k],
                                    'library' => '/library/' . $key . '.lbi',
                                    'sort_order' => $_POST['sort_order']['cat_articles'][$k],
                                    'id' => $_POST['article_cat']['cat_articles'][$k]];
                            }
                        }
                        break;
                    case 'ad_position':
                        foreach ($val as $k => $v) {
                            if (intval($_POST['ad_position'][$k]) > 0) {
                                $post_regions[] = ['region' => $v,
                                    'type' => 4,
                                    'number' => $_POST['number']['ad_position'][$k],
                                    'library' => '/library/' . $key . '.lbi',
                                    'sort_order' => $_POST['sort_order']['ad_position'][$k],
                                    'id' => $_POST['ad_position'][$k]];
                            }
                        }
                        break;
                    default:
                        if (!empty($_POST['display'][$key])) {
                            $post_regions[] = ['region' => $val,
                                'type' => 0,
                                'number' => 0,
                                'library' => $_POST['map'][$key],
                                'sort_order' => $_POST['sort_order'][$key],
                                'id' => 0];
                        }

                }
            }

            // 排序
            usort($post_regions, "array_sort");

            // 修改模板文件
            $template_file = '../themes/' . $curr_template . '/' . $_POST['template_file'] . '.dwt';
            $template_content = file_get_contents($template_file);
            $template_content = str_replace("\xEF\xBB\xBF", '', $template_content);
            $org_regions = get_template_region($curr_template, $_POST['template_file'] . '.dwt', false);

            $region_content = '';
            $pattern = '/(<!--\\s*TemplateBeginEditable\\sname="%s"\\s*-->)(.*?)(<!--\\s*TemplateEndEditable\\s*-->)/s';
            $replacement = "\\1\n%s\\3";
            $lib_template = "<!-- #BeginLibraryItem \"%s\" -->\n%s\n <!-- #EndLibraryItem -->\n";

            foreach ($org_regions as $region) {
                $region_content = ''; // 获取当前区域内容
                foreach ($post_regions as $lib) {
                    if ($lib['region'] == $region) {
                        if (!file_exists('../themes/' . $curr_template . $lib['library'])) {
                            continue;
                        }
                        $lib_content = file_get_contents('../themes/' . $curr_template . $lib['library']);
                        $lib_content = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=.*["|\']>/i', '', $lib_content);
                        $lib_content = str_replace("\xEF\xBB\xBF", '', $lib_content);
                        $region_content .= sprintf($lib_template, $lib['library'], $lib_content);
                    }
                }

                // 替换原来区域内容
                $template_content = preg_replace(sprintf($pattern, $region), sprintf($replacement, $region_content), $template_content);
            }

            if (file_put_contents($template_file, $template_content)) {
                //clear_tpl_files(false, '.dwt.php'); // 清除对应的编译文件
                clear_cache_files();
                $lnk[] = ['text' => $GLOBALS['_LANG']['go_back'], 'href' => 'template.php?act=setup&template_file=' . $_POST['template_file']];
                return sys_msg($GLOBALS['_LANG']['setup_success'], 0, $lnk);
            } else {
                return sys_msg(sprintf($GLOBALS['_LANG']['modify_dwt_failed'], 'themes/' . $curr_template . '/' . $_POST['template_file'] . '.dwt'), 1, null, false);
            }
        }

        /**
         * 管理库项目
         */
        if ($_REQUEST['act'] == 'library') {
            admin_priv('library_manage');

            // 包含插件语言项
            $sql = "SELECT code FROM " . $this->ecs->table('plugins');
            $rs = $this->db->query($sql);
            foreach ($rs as $row) {
                // 取得语言项
                if (file_exists(ROOT_PATH . 'plugins/' . $row['code'] . '/languages/common_' . $GLOBALS['_CFG']['lang'] . '.php')) {
                    include_once(ROOT_PATH . 'plugins/' . $row['code'] . '/languages/common_' . $GLOBALS['_CFG']['lang'] . '.php');
                }
            }
            $curr_template = $GLOBALS['_CFG']['template'];
            $arr_library = [];
            $library_path = resource_path('themes/' . $curr_template . '/library');
            $library_dir = scandir($library_path);
            $curr_library = '';

            foreach ($library_dir as $file) {
                if (substr($file, -3) == "lbi") {
                    $filename = substr($file, 0, -4);
                    $arr_library[$filename] = $file . ' - ' . @$GLOBALS['_LANG']['template_libs'][$filename];

                    if ($curr_library == '') {
                        $curr_library = $filename;
                    }
                }
            }

            ksort($arr_library);

            $lib = $this->load_library($curr_template, $curr_library);

            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['04_template_library']);
            $this->smarty->assign('curr_library', $curr_library);
            $this->smarty->assign('libraries', $arr_library);
            $this->smarty->assign('library_html', $lib['html']);
            return $this->smarty->display('template_library.htm');
        }

        /**
         * 安装模版
         */
        if ($_REQUEST['act'] == 'install') {
            check_authz_json('backup_setting');

            $tpl_name = trim($_GET['tpl_name']);
            $tpl_fg = 0;
            $tpl_fg = trim($_GET['tpl_fg']);

            $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value = '$tpl_name' WHERE code = 'template'";
            $step_one = $this->db->query($sql, 'SILENT');
            $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value = '$tpl_fg' WHERE code = 'stylename'";
            $step_two = $this->db->query($sql, 'SILENT');

            if ($step_one && $step_two) {
                clear_all_files(); //清除模板编译文件

                $error_msg = '';
                if (move_plugin_library($tpl_name, $error_msg)) {
                    return make_json_error($error_msg);
                } else {
                    return make_json_result($this->read_style_and_tpl($tpl_name, $tpl_fg), $GLOBALS['_LANG']['install_template_success']);
                }
            } else {
                return make_json_error($this->db->error());
            }
        }

        /**
         * 备份模版
         */
        if ($_REQUEST['act'] == 'backup') {
            check_authz_json('backup_setting');

            $tpl = $GLOBALS['_CFG']['template'];
            //$tpl = trim($_REQUEST['tpl_name']);

            $filename = '../temp/backup/' . $tpl . '_' . date('Ymd') . '.zip';

            $zip = new PHPZip;
            $done = $zip->zip('../themes/' . $tpl . '/', $filename);

            if ($done) {
                return make_json_result($filename);
            } else {
                return make_json_error($GLOBALS['_LANG']['backup_failed']);
            }
        }

        /**
         * 载入指定库项目的内容
         */
        if ($_REQUEST['act'] == 'load_library') {
            $library = $this->load_library($GLOBALS['_CFG']['template'], trim($_GET['lib']));
            $message = ($library['mark'] & 7) ? '' : $GLOBALS['_LANG']['library_not_written'];

            return make_json_result($library['html'], $message);
        }

        /**
         * 更新库项目内容
         */
        if ($_REQUEST['act'] == 'update_library') {
            check_authz_json('library_manage');

            $html = stripslashes(json_str_iconv($_POST['html']));
            $lib_file = '../themes/' . $GLOBALS['_CFG']['template'] . '/library/' . $_POST['lib'] . '.lbi';
            $lib_file = str_replace("0xa", '', $lib_file); // 过滤 0xa 非法字符

            $org_html = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

            if (@file_exists($lib_file) === true && @file_put_contents($lib_file, $html)) {
                @file_put_contents('../temp/backup/library/' . $GLOBALS['_CFG']['template'] . '-' . $_POST['lib'] . '.lbi', $org_html);

                return make_json_result('', $GLOBALS['_LANG']['update_lib_success']);
            } else {
                return make_json_error(sprintf($GLOBALS['_LANG']['update_lib_failed'], 'themes/' . $GLOBALS['_CFG']['template'] . '/library'));
            }
        }

        /**
         * 还原库项目
         */
        if ($_REQUEST['act'] == 'restore_library') {
            admin_priv('backup_setting');
            $lib_name = trim($_GET['lib']);
            $lib_file = '../themes/' . $GLOBALS['_CFG']['template'] . '/library/' . $lib_name . '.lbi';
            $lib_file = str_replace("0xa", '', $lib_file); // 过滤 0xa 非法字符
            $lib_backup = '../temp/backup/library/' . $GLOBALS['_CFG']['template'] . '-' . $lib_name . '.lbi';
            $lib_backup = str_replace("0xa", '', $lib_backup); // 过滤 0xa 非法字符

            if (file_exists($lib_backup) && filemtime($lib_backup) >= filemtime($lib_file)) {
                return make_json_result(str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_backup)));
            } else {
                return make_json_result(str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file)));
            }
        }

        /**
         * 布局备份
         */
        if ($_REQUEST['act'] == 'backup_setting') {
            admin_priv('backup_setting');

            $sql = "SELECT DISTINCT(remarks) FROM " . $this->ecs->table('template') . " WHERE theme = '" . $GLOBALS['_CFG']['template'] . "' AND remarks > ''";
            $col = $this->db->getCol($sql);
            $remarks = [];
            foreach ($col as $val) {
                $remarks[] = ['content' => $val, 'url' => urlencode($val)];
            }

            $sql = "SELECT DISTINCT(filename) FROM " . $this->ecs->table('template') . " WHERE theme = '" . $GLOBALS['_CFG']['template'] . "' AND remarks = ''";
            $col = $this->db->getCol($sql);
            $files = [];
            foreach ($col as $val) {
                $files[$val] = $GLOBALS['_LANG']['template_files'][$val];
            }


            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['backup_setting']);
            $this->smarty->assign('list', $remarks);
            $this->smarty->assign('files', $files);
            return $this->smarty->display('templates_backup.htm');
        }

        if ($_REQUEST['act'] == 'act_backup_setting') {
            $remarks = empty($_POST['remarks']) ? local_date($GLOBALS['_CFG']['time_format']) : trim($_POST['remarks']);

            if (empty($_POST['files'])) {
                $files = [];
            } else {
                $files = $_POST['files'];
            }

            $sql = "SELECT COUNT(*) FROM " . $this->ecs->table('template') . " WHERE remarks='$remarks' AND theme = '" . $GLOBALS['_CFG']['template'] . "'";
            if ($this->db->getOne($sql) > 0) {
                return sys_msg(sprintf($GLOBALS['_LANG']['remarks_exist'], $remarks), 1);
            }

            $sql = "INSERT INTO " . $this->ecs->table('template') .
                " (filename, region, library, sort_order, id, number, type, theme, remarks)" .
                " SELECT filename, region, library, sort_order, id, number, type, theme, '$remarks'" .
                " FROM " . $this->ecs->table('template') .
                " WHERE remarks = '' AND theme = '" . $GLOBALS['_CFG']['template'] . "'" .
                " AND " . db_create_in($files, 'filename');

            $this->db->query($sql);
            return sys_msg($GLOBALS['_LANG']['backup_template_ok'], 0, [['text' => $GLOBALS['_LANG']['backup_setting'], 'href' => 'template.php?act=backup_setting']]);
        }

        if ($_REQUEST['act'] == 'del_backup') {
            $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
            if ($remarks) {
                $sql = "DELETE FROM " . $this->ecs->table('template') . " WHERE remarks='$remarks' AND theme = '" . $GLOBALS['_CFG']['template'] . "'";
                $this->db->query($sql);
            }
            return sys_msg($GLOBALS['_LANG']['del_backup_ok'], 0, [['text' => $GLOBALS['_LANG']['backup_setting'], 'href' => 'template.php?act=backup_setting']]);
        }

        if ($_REQUEST['act'] == 'restore_backup') {
            $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
            if ($remarks) {
                $sql = "SELECT filename, region, library, sort_order " .
                    " FROM " . $this->ecs->table('template') .
                    " WHERE remarks='$remarks' AND theme = '" . $GLOBALS['_CFG']['template'] . "'" .
                    " ORDER BY filename, region, sort_order";
                $arr = $this->db->getAll($sql);
                if ($arr) {
                    $data = [];
                    foreach ($arr as $val) {
                        $lib_content = file_get_contents(resource_path('themes/' . $GLOBALS['_CFG']['template'] . $val['library']));
                        //去除lib头部
                        $lib_content = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=utf-8"|\']>/i', '', $lib_content);
                        //去除utf bom
                        $lib_content = str_replace("\xEF\xBB\xBF", '', $lib_content);
                        //加入dw 标识
                        $lib_content = '<!-- #BeginLibraryItem "' . $val['library'] . "\" -->\r\n" . $lib_content . "\r\n" . '<!-- #EndLibraryItem -->';
                        if (isset($data[$val['filename']][$val['region']])) {
                            $data[$val['filename']][$val['region']] .= $lib_content;
                        } else {
                            $data[$val['filename']][$val['region']] = $lib_content;
                        }
                    }

                    foreach ($data as $file => $regions) {
                        $pattern = '/(?:<!--\\s*TemplateBeginEditable\\sname="(' . implode('|', array_keys($regions)) . ')"\\s*-->)(?:.*?)(?:<!--\\s*TemplateEndEditable\\s*-->)/se';
                        $temple_file = resource_path('themes/' . $GLOBALS['_CFG']['template'] . '/' . $file . '.dwt');
                        $template_content = file_get_contents($temple_file);
                        $match = [];
                        $template_content = preg_replace($pattern, "'<!-- TemplateBeginEditable name=\"\\1\" -->\r\n' . \$regions['\\1'] . '\r\n<!-- TemplateEndEditable -->';", $template_content);
                        file_put_contents($temple_file, $template_content);
                    }

                    // 文件修改成功后，恢复数据库
                    $sql = "DELETE FROM " . $this->ecs->table('template') .
                        " WHERE remarks = '' AND  theme = '" . $GLOBALS['_CFG']['template'] . "'" .
                        " AND " . db_create_in(array_keys($data), 'filename');
                    $this->db->query($sql);
                    $sql = "INSERT INTO " . $this->ecs->table('template') .
                        " (filename, region, library, sort_order, id, number, type, theme, remarks)" .
                        " SELECT filename, region, library, sort_order, id, number, type, theme, ''" .
                        " FROM " . $this->ecs->table('template') .
                        " WHERE remarks = '$remarks' AND theme = '" . $GLOBALS['_CFG']['template'] . "'";
                    $this->db->query($sql);
                }
            }
            return sys_msg($GLOBALS['_LANG']['restore_backup_ok'], 0, [['text' => $GLOBALS['_LANG']['backup_setting'], 'href' => 'template.php?act=backup_setting']]);
        }
    }

    private function array_sort($a, $b)
    {
        $cmp = strcmp($a['region'], $b['region']);

        if ($cmp == 0) {
            return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
        } else {
            return ($cmp > 0) ? -1 : 1;
        }
    }

    /**
     * 载入库项目内容
     *
     * @access  public
     * @param   string $curr_template 模版名称
     * @param   string $lib_name 库项目名称
     * @return  array
     */
    private function load_library($curr_template, $lib_name)
    {
        $lib_name = str_replace("0xa", '', $lib_name); // 过滤 0xa 非法字符

        $lib_file = resource_path('themes/' . $curr_template . '/library/' . $lib_name . '.lbi');
        $arr['mark'] = file_mode_info($lib_file);
        $arr['html'] = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

        return $arr;
    }

    /**
     * 读取模板风格列表
     *
     * @access  public
     * @param   string $tpl_name 模版名称
     * @param   int $flag 1，AJAX数据；2，Array
     * @return
     */
    private function read_tpl_style($tpl_name, $flag = 1)
    {
        if (empty($tpl_name) && $flag == 1) {
            return 0;
        }

        // 获得可用的模版
        $temp = '';
        $start = 0;
        $available_templates = [];
        $dir = resource_path('themes/' . $tpl_name) . '/';
        $tpl_style_dir = scandir($dir);
        foreach ($tpl_style_dir as $file) {
            if ($file != '.' && $file != '..' && is_file($dir . $file) && $file != '.svn' && $file != 'index.htm') {
                if (preg_match("/^(style|style_)(.*)*/i", $file)) { // 取模板风格缩略图
                    $start = strpos($file, '.');
                    $temp = substr($file, 0, $start);
                    $temp = explode('_', $temp);
                    if (count($temp) == 2) {
                        $available_templates[] = $temp[1];
                    }
                }
            }
        }

        if ($flag == 1) {
            $ec = '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(0, this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(0);"  bgcolor="#FFFFFF"><tr><td>&nbsp;</td></tr></table>';
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $tpl_info = get_template_info($tpl_name, $value);

                    $ec .= '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(\'' . $value . '\', this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(\'' . $value . '\');"  bgcolor="' . $tpl_info['type'] . '"><tr><td>&nbsp;</td></tr></table>';

                    unset($tpl_info);
                }
            } else {
                $ec = '0';
            }

            return $ec;
        } elseif ($flag == 2) {
            $templates_temp = [''];
            if (count($available_templates) > 0) {
                foreach ($available_templates as $value) {
                    $templates_temp[] = $value;
                }
            }

            return $templates_temp;
        }
    }

    /**
     * 读取当前风格信息与当前模板风格列表
     *
     * @access  public
     * @param   string $tpl_name 模版名称
     * @param   string $tpl_style 模版风格名
     * @return
     */
    private function read_style_and_tpl($tpl_name, $tpl_style)
    {
        $style_info = [];
        $style_info = get_template_info($tpl_name, $tpl_style);

        $tpl_style_info = [];
        $tpl_style_info = $this->read_tpl_style($tpl_name, 2);
        $tpl_style_list = '';
        if (count($tpl_style_info) > 1) {
            foreach ($tpl_style_info as $value) {
                $tpl_style_list .= '<span style="cursor:pointer;" onMouseOver="javascript:onSOver(\'screenshot\', \'' . $value . '\', this);" onMouseOut="onSOut(\'screenshot\', this, \'' . $style_info['screenshot'] . '\');" onclick="javascript:setupTemplateFG(\'' . $tpl_name . '\', \'' . $value . '\', \'\');" id="templateType_' . $value . '"><img src="../themes/' . $tpl_name . '/images/type' . $value . '_';

                if ($value == $tpl_style) {
                    $tpl_style_list .= '1';
                } else {
                    $tpl_style_list .= '0';
                }
                $tpl_style_list .= '.gif" border="0"></span>&nbsp;';
            }
        }
        $style_info['tpl_style'] = $tpl_style_list;

        return $style_info;
    }
}