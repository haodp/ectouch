<?php

namespace App\Dashboard\Controllers;

class TeamController extends InitController {

    //拼团列表
    public function index() {
        //分页
        $filter['page'] = '{page}';
        $offset = $this->pageLimit(url('index', $filter), 20);
        $total = $this->model->table('team_category')
                ->where(array('parent_id'=> '0'))
                ->order('sort_order desc')
                ->count();     
        $this->assign('page', $this->pageShow($total));
        $sql = 'select * from ' . $this->model->pre . 'team_category order by sort_order desc limit ' . $offset;
        $cat_list = $this->get_child_tree(0,$offset);
        foreach ($cat_list as $key => $value) {
            if ($value['parent_id'] > 0) {
                $cat_name = $this->model->table('team_category')->field('name')->where(array('id' => $value[parent_id]))->find();
                $cat_list[$key]['cat_name'] = $cat_name['name'];
            }
        }
        $this->assign('cat_info', $cat_list);
        $this->display();
    }

    //添加
    public function add() {
        if (IS_POST) {                  
            $data = I('post.data');          
            $cat_id1 = $this->model->table('team_category')->field('parent_id')->where(array('id' => $data[parent_id]))->find();
            $cat_id2 = $this->model->table('team_category')->field('parent_id')->where(array('id' => $data[id]))->find();

           
            //不能修改当前分类为自己的下级分类
            if(($data['id'] == $data['parent_id']) && $data['id'] != 0 ){
                $this->message("不能修改当前分类为自己的下级分类", url('team/add' ,array('id' => $data['id'])));
            }
            if (empty($data['name'])) {
                $this->message('频道名称不能为空');
            }
            // 频道图片处理
            if (!empty($_FILES['tc_img']['name'])) {
                $image = new EcsImage();
                $img_name = $image->upload_image($_FILES['tc_img'], 'attached/team');
                $data['tc_img'] = $image->make_thumb($img_name, 320, 320);
            }
            if (empty($data['id'])) {
                if ($data['parent_id'] > 0) {
                    $count = $this->model->table('team_category')
                            ->data($data)
                            ->where(array('parent_id' => $data['parent_id']))
                            ->count();
                    if ($count >= 4) {
                        $this->message("子频道不能超过4个", url('team/index'));
                    }
                }
                //入库
                $this->model->table('team_category')
                        ->data($data)
                        ->insert();
            } else {
                //修改
                if (empty($_FILES['tc_img']['name'])) {
                    $cat_info = $this->model->table('team_category')->where(array('id' => $data[id]))->find();
                    $data['tc_img'] = $cat_info['tc_img'];
                }
                $this->model->table('team_category')
                        ->data($data)
                        ->where(array('id' => $data['id']))
                        ->update();
            }
            $this->message(L('success'), url('team/index'));
        }
        if (I('id')) {
            $id = I('id', '', 'intval');
            $team_category = $this->model->table('team_category')->where(array('id' => $id))->find();
            $this->assign('cat_info', $team_category);
        }
        $cat_list = $this->model->table('team_category')->where(array('parent_id' => 0))->select();
        $this->assign('cat_select', $cat_list);
        $this->display();
    }

    //删除
    public function del() {
        $id = I('id');
        if (empty($id)) {
            $this->message(L('menu_select_del'), NULL, 'error');
        }
        $this->model->table('team_category')
                ->where(array('id' => $id))
                ->delete();
        $this->model->table('team_category')
                ->where(array('parent_id' => $id))
                ->delete();
        $this->message(L('drop') . L('success'), url('index'));
    }
    //频道树形
    function get_child_tree($tree_id = 0, $offset) {
        $three_arr = array();
        $count = $this->model->table('team_category')->where(array('parent_id' => $tree_id))->count();
        if ($count || $tree_id == 0) {
            $sql="select * from {pre}team_category where parent_id='$tree_id'limit ".$offset;
            $res=$this->model->query($sql);
            foreach ($res AS $k => $row) {
                if (!empty($row)) {
                    $three_arr[$k]['id'] = $row['id'];
                    $three_arr[$k]['status'] = $row['status'];
                    $three_arr[$k]['sort_order'] = $row['sort_order'];
                    $three_arr[$k]['name'] = $row['name'];
                    $three_arr[$k]['haschild'] = 0;
                }
                if (isset($row['id'])) {
                    $child_tree = $this->get_child_tree($row['id'],$offset);
                    if ($child_tree) {
                        $three_arr[$k]['cat_id'] = $child_tree;
                        $three_arr[$k]['haschild'] = 1;
                    }
                }
            }
        }
        return $three_arr;
    }
}
