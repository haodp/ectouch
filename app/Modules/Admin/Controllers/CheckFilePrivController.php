<?php

namespace App\Modules\Admin\Controllers;

/**
 * 系统文件检测
 * Class CheckFilePrivController
 * @package App\Modules\Admin\Controllers
 */
class CheckFilePrivController extends BaseController
{
    public function actionIndex()
    {
        if ($_REQUEST['act'] == 'check') {
            // 检查权限
            admin_priv('file_priv');

            // 要检查目录文件列表
            $goods_img_dir = [];
            $folder = scandir(public_path('images'));
            foreach ($folder as $dir) {
                if (is_dir(public_path(IMAGE_DIR) . '/' . $dir) && preg_match('/^[0-9]{6}$/', $dir)) {
                    $goods_img_dir[] = IMAGE_DIR . '/' . $dir;
                }
            }

            $dir[] = ADMIN_PATH;
            $dir[] = 'cert';

            $dir_subdir['images'][] = IMAGE_DIR;
            $dir_subdir['images'][] = IMAGE_DIR . '/upload';
            $dir_subdir['images'][] = IMAGE_DIR . '/upload/Image';
            $dir_subdir['images'][] = IMAGE_DIR . '/upload/File';
            $dir_subdir['images'][] = IMAGE_DIR . '/upload/Flash';
            $dir_subdir['images'][] = IMAGE_DIR . '/upload/Media';
            $dir_subdir['data'][] = DATA_DIR;
            $dir_subdir['data'][] = DATA_DIR . '/afficheimg';
            $dir_subdir['data'][] = DATA_DIR . '/brandlogo';
            $dir_subdir['data'][] = DATA_DIR . '/cardimg';
            $dir_subdir['data'][] = DATA_DIR . '/feedbackimg';
            $dir_subdir['data'][] = DATA_DIR . '/packimg';
            $dir_subdir['data'][] = DATA_DIR . '/sqldata';
            $dir_subdir['temp'][] = 'temp';
            $dir_subdir['temp'][] = 'temp/backup';
            $dir_subdir['temp'][] = 'temp/caches';
            $dir_subdir['temp'][] = 'temp/compiled';
            $dir_subdir['temp'][] = 'temp/compiled/admin';
            $dir_subdir['temp'][] = 'temp/query_caches';
            $dir_subdir['temp'][] = 'temp/static_caches';

            // 将商品图片目录加入检查范围
            foreach ($goods_img_dir as $val) {
                $dir_subdir['images'][] = $val;
            }

            $tpl = 'themes/' . $GLOBALS['_CFG']['template'] . '/';


            $list = [];

            // 检查目录
            foreach ($dir as $val) {
                $mark = file_mode_info(ROOT_PATH . $val);
                $list[] = ['item' => $val . $GLOBALS['_LANG']['dir'], 'r' => $mark & 1, 'w' => $mark & 2, 'm' => $mark & 4];
            }

            // 检查目录及子目录
            $keys = array_unique(array_keys($dir_subdir));
            foreach ($keys as $key) {
                $err_msg = [];
                $mark = $this->check_file_in_array($dir_subdir[$key], $err_msg);
                $list[] = ['item' => $key . $GLOBALS['_LANG']['dir_subdir'], 'r' => $mark & 1, 'w' => $mark & 2, 'm' => $mark & 4, 'err_msg' => $err_msg];
            }

            // 检查当前模板可写性
            $dwt = scandir(ROOT_PATH . $tpl);
            $tpl_file = []; //获取要检查的文件
            foreach ($dwt as $file) {
                if (is_file(ROOT_PATH . $tpl . $file) && strrpos($file, '.dwt') > 0) {
                    $tpl_file[] = $tpl . $file;
                }
            }

            $lib = scandir(ROOT_PATH . $tpl . 'library/');
            foreach ($lib as $file) {
                if (is_file(ROOT_PATH . $tpl . 'library/' . $file) && strrpos($file, '.lbi') > 0) {
                    $tpl_file[] = $tpl . 'library/' . $file;
                }
            }

            // 开始检查
            $err_msg = [];
            $mark = $this->check_file_in_array($tpl_file, $err_msg);
            $list[] = ['item' => $tpl . $GLOBALS['_LANG']['tpl_file'], 'r' => $mark & 1, 'w' => $mark & 2, 'm' => $mark & 4, 'err_msg' => $err_msg];

            // 检查smarty的缓存目录和编译目录及image目录是否有执行rename()函数的权限
            $tpl_list = [];
            $tpl_dirs[] = 'temp/caches';
            $tpl_dirs[] = 'temp/compiled';
            $tpl_dirs[] = 'temp/compiled/admin';

            // 将商品图片目录加入检查范围
            foreach ($goods_img_dir as $val) {
                $tpl_dirs[] = $val;
            }

            foreach ($tpl_dirs as $dir) {
                $mask = file_mode_info(ROOT_PATH . $dir);

                if (($mask & 4) > 0) {
                    // 之前已经检查过修改权限，只有有修改权限才检查rename权限
                    if (($mask & 8) < 1) {
                        $tpl_list[] = $dir;
                    }
                }
            }
            $tpl_msg = implode(', ', $tpl_list);
            $this->smarty->assign('ur_here', $GLOBALS['_LANG']['check_file_priv']);
            $this->smarty->assign('list', $list);
            $this->smarty->assign('tpl_msg', $tpl_msg);
            return $this->smarty->display('file_priv.html');
        }
    }

    /**
     *  检查数组中目录权限
     *
     * @access  public
     * @param   array $arr 要检查的文件列表数组
     * @param   array $err_msg 错误信息回馈数组
     *
     * @return int       $mark          文件权限掩码
     */
    private function check_file_in_array($arr, &$err_msg)
    {
        $read = true;
        $writen = true;
        $modify = true;
        foreach ($arr as $val) {
            $mark = file_mode_info(ROOT_PATH . $val);
            if (($mark & 1) < 1) {
                $read = false;
                $err_msg['r'][] = $val;
            }
            if (($mark & 2) < 1) {
                $writen = false;
                $err_msg['w'][] = $val;
            }
            if (($mark & 4) < 1) {
                $modify = false;
                $err_msg['m'][] = $val;
            }
        }

        $mark = 0;
        if ($read) {
            $mark ^= 1;
        }
        if ($writen) {
            $mark ^= 2;
        }
        if ($modify) {
            $mark ^= 4;
        }

        return $mark;
    }
}