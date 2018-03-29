<?php

namespace app\shop\controller;

/**
 * 所有分类及品牌
 * Class CatalogController
 * @package app\shop\controller
 */
class CatalogController extends InitController
{
    public function index()
    {
        if (!$this->smarty->is_cached('catalog.dwt')) {
            // 取出所有分类
            $cat_list = cat_list(0, 0, false);

            foreach ($cat_list as $key => $val) {
                if ($val['is_show'] == 0) {
                    unset($cat_list[$key]);
                }
            }

            assign_template();
            assign_dynamic('catalog');
            $position = assign_ur_here(0, $GLOBALS['_LANG']['catalog']);
            $this->smarty->assign('page_title', $position['title']);   // 页面标题
            $this->smarty->assign('ur_here', $position['ur_here']); // 当前位置

            $this->smarty->assign('helps', get_shop_help()); // 网店帮助
            $this->smarty->assign('cat_list', $cat_list);       // 分类列表
            $this->smarty->assign('brand_list', get_brands());    // 所以品牌赋值
            $this->smarty->assign('promotion_info', get_promotion_info());

            /**
             * 模拟数据
             */
            $content = file_get_contents(base_path('docs/mock/jd.php'));

            preg_match_all('/jsonp(.*?)\}\)/', $content, $childs);
            $list = [];
            foreach ($childs[0] as $item) {
                preg_match_all('/expoSrv(.*?)pictureUrl":"(.*?)"(.*?)name":"(.*?)"(.*?)picHeight/', $item, $matches);
                $list[] = array_combine($matches[4], $matches[2]);
            }

            // 一级类别
            preg_match_all('/\<li class=""\>(.*?)\<\/li\>/', $content, $matches);
            $category = [];
            foreach ($matches[1] as $key => $item) {
                $category[$item] = $list[$key];
            }

            $this->smarty->assign('category', $category);       // 分类列表
        }

        return $this->smarty->display('catalog.dwt');
    }

    /**
     * 计算指定分类的商品数量
     *
     * @access public
     * @param   integer $cat_id
     *
     * @return void
     */
    private function calculate_goods_num($cat_list, $cat_id)
    {
        $goods_num = 0;

        foreach ($cat_list as $cat) {
            if ($cat['parent_id'] == $cat_id && !empty($cat['goods_num'])) {
                $goods_num += $cat['goods_num'];
            }
        }

        return $goods_num;
    }
}