<?php

namespace App\Http\Controllers;

/**
 * Class CrowdFundingController
 * @package App\Http\Controllers
 */
class CrowdFundingController extends InitController
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
		$this->user_id = $_SESSION['user_id'];
        $this->cat_id = I('request.id');
		$this->type = I('request.type');
		$this->keywords = I('request.keywords');
		$this->goods_id = I('request.id');
		$this->size = 10;
		$this->page = 1;

		if (!empty($_COOKIE['ZCECS']['keywords'])) {
            $histroy = explode(',',$_COOKIE['ZCECS']['keywords']);
            foreach ($histroy as $key=>$val) {
                if($key < 10){
                    $zchistroy_list[$key] = $val;
                }
            }
            $this->assign('zcsearch_histroy', $zchistroy_list);
        }


    }

    /**
     * 众筹项目列表信息
     */
    public function index() {
        $category = model('Crowdfunding')->category_all($this->type);//获取众筹分类
		$goodslist = $this->crowd_goods();//获取众筹产品列表
		if($this->cat_id){
			$this->assign('id', $this->cat_id);
		}

		$this->assign('type', $this->type);
		$this->assign('page', $this->page);
        $this->assign('size', $this->size);
		$this->assign('keywords', $this->keywords);
		$this->assign('goods_list', $goodslist);
		$this->assign('category', $category);
        $this->display('crowd/crowd_category.html');
    }

    /**
     * 众筹项目列表信息
     */
	public function crowd_goods() {

		/*记录搜索历史记录*/
		if (!empty($_COOKIE['ZCECS']['keywords'])) {
			$history = explode(',', $_COOKIE['ZCECS']['keywords']);
			array_unshift($history, $this->keywords); //在数组开头插入一个或多个元素
			$history = array_values(array_filter(array_unique($history)));  //移除数组中的重复的值，并返回结果数组。
			setcookie('ZCECS[keywords]', implode(',', $history), gmtime() + 3600 * 24 * 30);
		} else {
			setcookie('ZCECS[keywords]', $this->keywords, gmtime() + 3600 * 24 * 30);
		}



		if ($this->keywords) {
            $where .= " and goods_name like '%$this->keywords%' ";
        }
		if ($this->cat_id > 0) {
            $where .= " and cat_id = $this->cat_id ";
        }

		if ($this->type) {
            switch ($this->type) {
                case 'new':
                    $where .= ' order by start_time DESC';
                    break;
                case 'sum_price':
                    $where .= ' order by sum_price DESC ';
                    break;
                case 'buy_num':
                    $where .= ' order by buy_num DESC ';
                    break;
				case 'end':
                    $where .= ' order by end_time asc ';
                    break;
                default:
                    $where .= '';
            }
        }
		$start = ($this->page - 1) * $this->size;
		$now = time();
		$sql = 'SELECT goods_id, cat_id, goods_name, goods_img, sum_price, start_time,end_time,status '.'FROM '
		. $this->model->pre . 'crowd_goods ' . "WHERE start_time <= '$now' AND end_time >= '$now' and status < 2 $where LIMIT $start , $this->size";
		//echo $sql;
        $res = $this->model->query($sql);
		$goods = array();
        foreach ($res AS $key => $row) {
            $goods[$key]['id'] = $row['goods_id'];
			$goods[$key]['cat_id'] = $row['cat_id'];
            $goods[$key]['goods_name'] = $row['goods_name'];
            $goods[$key]['buy_num'] = model('Crowdfunding')->crowd_buy_num($row['goods_id']);
			$goods[$key]['start_time'] = model('Crowdfunding')->start_time_past($row['start_time'],time());
            //$goods[$key]['sum_price'] = price_format($row['sum_price']);
			$goods[$key]['sum_price'] = $row['sum_price'];
            $goods[$key]['total_price'] = model('Crowdfunding')->crowd_buy_price($row['goods_id']);
            $goods[$key]['goods_img'] = $row['goods_img'];
			$goods[$key]['url'] = url('Crowdfunding/goods_info', array('id' => $row['goods_id']));
			if($row['sum_price'] > 0){
				$goods[$key]['bar'] = $goods[$key]['total_price']*100/$row['sum_price'];
			}
			$goods[$key]['bar'] = round($goods[$key]['bar'],1); //计算百分比
        }
        return $goods;

	}
	/**
     * 异步加载商品列表
     */
    public function async_list()
    {

		$this->page = I('request.page') > 0 ? intval(I('request.page')) : 1;
        $this->type = I('request.type');
		$this->cat_id = I('request.id');
		$this->keywords = I('request.keywords');

        $goodslist = $this->crowd_goods();
        die(json_encode(array('list' => $goodslist)));
        exit();
    }

	/**
     * 众筹项目详情信息
     */
	public function goods_info() {

		$goods = model('Crowdfunding')->crowd_goods_info($this->goods_id);  //项目信息
		if($goods == false){
			ecs_header("Location: " . url('crowdfunding/index') . "\n");
		}
		//项目相册
		$gallery = explode(',',$goods['gallery_img']);
		foreach ($gallery as $key=>$val) {
			$gallery_img[$key] = $val;
        }
		if($gallery_img[0] != ''){
			$this->assign('gallery_img', $gallery_img);
		}

		$goods_plan = model('Crowdfunding')->crowd_goods_paln($this->goods_id);//项目方案
		$comment_list = model('Crowdfunding')->crowd_comment($this->goods_id);//项目评论
		$trends_list = model('Crowdfunding')->crowd_trends($this->goods_id);//项目动态
		$buy_list = model('Crowdfunding')->crowd_buy($this->goods_id);//项目的支持者
		// 检查是否已经存在于用户的关注列表
        if ($_SESSION ['user_id']) {
            $where['user_id'] = $_SESSION ['user_id'];
            $where['goods_id'] = $this->goods_id;
            $rs = $this->model->table('crowd_like')->where($where)->count();
            if ($rs > 0) {
                $this->assign('sc', 1);
            }
        }
		$this->assign('goods', $goods);

        // 微信JSSDK分享
        $share_data = array(
            'title' => $goods['goods_name'],
            'desc' => $goods['shiping_time'],
            'link' => '',
            'img' => $goods['goods_img'],
        );
        $this->assign('share_data', $this->get_wechat_share_content($share_data));

		$this->assign('id', $this->goods_id);
		$this->assign('goods_plan', $goods_plan);
		$this->assign('comment_list', $comment_list);
		$this->assign('trends_list', $trends_list);
		$this->assign('buy_list', $buy_list);
		$this->display('crowd/crowd_goods.html');
	}

	/**
     * 改变方案数量修改价格
     */
	public function plan_price() {

		//格式化返回数组
        $res = array(
            'err_msg' => '',
            'result' => '',
            'qty' => 1
        );
        // 获取参数
		$goods_id = (isset($_REQUEST ['goods_id'])) ? intval($_REQUEST ['goods_id']) : 1;
		$cp_id = (isset($_REQUEST ['cp_id'])) ? intval($_REQUEST ['cp_id']) : 1;
        $number = (isset($_REQUEST ['number'])) ? intval($_REQUEST ['number']) : 1;
        // 如果商品id错误
        if ($goods_id == 0) {
            $res ['err_msg'] = L('err_change_attr');
            $res ['err_no'] = 1;
        } else {
            // 查询
            $condition['goods_id'] = $goods_id;
			$condition['cp_id'] = $cp_id;
            $goods = $this->model->table('crowd_plan')->field('shop_price , number ,backey_num')->where($condition)->find();
			$surplus_num = $goods['number'] - $goods['backey_num'];
            if ($number <= 0) {
                $res ['qty'] = $number = 1;
            } else {
                $res ['qty'] = $number;
            }
			if($number > $surplus_num){
				$res ['err_msg'] = '已超出计划销售数量';
				$res ['number'] = $surplus_num;
				$res ['result'] = price_format($goods['shop_price'] * $surplus_num);
				$res ['err_no'] = 1;
			}else{
				$res ['result'] = price_format($goods['shop_price'] * $number);
			}
        }
        die(json_encode($res));
	}

	/**
     * 加入关注
     */
	public function add_crowd_like(){

		$result = array(
            'error' => 0,
            'message' => ''
        );

		if ($this->user_id == 0) {
            $result['error'] = 2;
            $result['message'] = '请先登录';
            die(json_encode($result));
        }

        $goods_id = intval($_GET['id']);
		// 检查是否已经存在于用户的关注列表
		$where['user_id'] = $this->user_id;
		$where['goods_id'] = $goods_id;
		$rs = $this->model->table('crowd_like')
				->where($where)
				->count();
		if ($rs > 0) {
			$rs = $this->model->table('crowd_like')
					->where($where)
					->delete();
			if (!$rs) {
				$result['error'] = 1;
				$result['message'] = M()->errorMsg();
				die(json_encode($result));
			} else {
				$result['error'] = 0;
				$result['message'] = '已成移除加关注列表';
				die(json_encode($result));
			}
		} else {
			$data['user_id'] = $this->user_id;
			$data['goods_id'] = $goods_id;
			$data['add_time'] = time();
			if ($this->model->table('crowd_like')
							->data($data)
							->insert() === false) {
				$result['error'] = 1;
				$result['message'] = M()->errorMsg();
				die(json_encode($result));
			} else {
				$result['error'] = 0;
				$result['message'] = '已成功添加关注列表';
				die(json_encode($result));
			}
		}


	}

	/**
     * 获取众筹项目的评论列表
     */
	public function crowd_comment_list(){
		$cmt = new stdClass();
		if(!empty($_GET['id'])){
			$cmt->id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
			$_SESSION['goods_id']= $cmt->id;
		}else{
			$_SESSION['goods_id'];
		}

        $cmt->type = !empty($_GET['type']) ? intval($_GET['type']) : 1;
        $cmt->page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        $com = model('Crowdfunding')->crowd_comment_info($_SESSION['goods_id']);
        $this->assign('comments_info', $com);
        $pay = 0;
        $size = I(C('page_size'), 10);
        $this->assign('show_asynclist', C('show_asynclist'));
        $count = $com['sum_count'];
        $filter['page'] = '{page}';
        $offset = $this->pageLimit(url('Crowdfunding/crowd_comment_list', $filter), $size);
        $offset_page = explode(',', $offset);
        $comment_list = model('Crowdfunding')->crowd_get_comment($_SESSION['goods_id'], $pay, $offset_page[1], $offset_page[0]);
        $this->assign('comment_list', $comment_list);

        $result['message'] = C('comment_check') ? L('cmt_submit_wait') : L('cmt_submit_done');
        $this->assign('id', $cmt->id);
        //$this->assign('type', $cmt->type);
        $this->assign('pager', $this->pageShow($count));
        $this->assign('title', L('goods_comment'));

		$this->display('crowd/crowd_comment_list.html');
	}


	/**
     * 获取众筹项目的详情
     */
	public function crowd_goods_properties(){
		$goods_desc = $this->model->table('crowd_goods')->where(array('goods_id'=>$this->goods_id))->field('goods_desc,goods_id')->find();
		$this->assign('goods', $goods_desc);
		$this->assign('id', $this->goods_id);
		$this->display('crowd/crowd_goods_info.html');
	}



	 /**
     * 清空浏览历史
     */
    public function clear_history() {
        // ajax请求
        if (IS_AJAX && IS_AJAX) {
            setcookie('ZCECS[keywords]', '', 1);
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
    }
    
    /**
     * 众筹项目订单确认页
     */
    public function crowd_checkout() {
		/* 取得购物类型 */
        //$flow_type = isset($_SESSION ['flow_type']) ? intval($_SESSION ['flow_type']) : CART_GENERAL_GOODS;
		if(!empty($_POST)){
			$goods_id = I('request.goods_id');
			$cp_id = I('request.cp_id');
			$number = I('request.number');

			$_SESSION['goods_id'] =$goods_id ;
			$_SESSION['cp_id'] = $cp_id ;
			$_SESSION['number'] = $number ;			
		}else{
			$_SESSION['goods_id'];
			$_SESSION['cp_id'];
			$_SESSION['number'];			
		}
		$this->assign('goods_id', $_SESSION['goods_id']);
		$this->assign('cp_id', $_SESSION['cp_id']);
		$this->assign('number', $_SESSION['number']);
		
		//  检查用户是否已经登录 如果用户已经登录了则检查是否有默认的收货地址 如果没有登录则跳转到登录和注册页面
        if (empty($_SESSION ['direct_shopping']) && $_SESSION ['user_id'] == 0) {
            /* 用户没有登录且没有选定匿名购物，转向到登录页面 */
            $this->redirect(url('user/login',array('step'=>'crowdflow')));
            exit;
        }
		
		//验证购买方案是否超出
        model('Mycrowd')->check_order($_SESSION['goods_id'],$_SESSION['cp_id'],$_SESSION['number']);
					
        // 获取收货人信息
        $consignee = model('Order')->get_consignee($_SESSION ['user_id']);
        /* 检查收货人信息是否完整 */
        if (!model('Order')->check_consignee_info($consignee, $flow_type)) {
            /* 如果不完整则转向到收货人信息填写界面 */
            ecs_header("Location: " . url('crowdflow/crowd_consignee_list') . "\n");
        }
		
		/* 对商品信息赋值 */
		$cart_goods = model('Crowdbuy')->cart_crowd_goods($_SESSION['goods_id'], $_SESSION['cp_id'], $_SESSION['number']);  //项目信息
		$this->assign('goods', $cart_goods);
		
		// 取得订单信息
        $order = model('Crowdbuy')->crowd_flow_order_info();
		$this->assign('order', $order);
		
        // 获取配送地址
        $consignee_list = model('Users')->get_consignee_list($_SESSION ['user_id']);
        $this->assign('consignee_list', $consignee_list);
        //获取默认配送地址
        $address_id = $this->model->table('users')->field('address_id')->where("user_id = '" . $_SESSION['user_id'] . "' ")->getOne();
        $this->assign('address_id', $address_id);

        $_SESSION ['flow_consignee'] = $consignee;
        $this->assign('consignee', $consignee);		
		//计算订单的费用
		$total = model('Crowdbuy')->crowd_order_fee($order, $cart_goods, $consignee);
		$this->assign('total', $total);
	
		
		/* 取得配送列表 */
		$region = array(
            $consignee ['country'],
            $consignee ['province'],
            $consignee ['city'],
            $consignee ['district']
        );
        $shipping_list = model('Shipping')->available_shipping_list($region);
		$cart_weight_price = model('Order')->cart_weight_price($flow_type);
        $insure_disabled = true;
        $cod_disabled = true;

        foreach ($shipping_list as $key => $val) {

            $shipping_cfg = unserialize_config($val ['configure']);
            $shipping_fee = shipping_fee($val['shipping_code'], unserialize($val ['configure']), $cart_weight_price ['weight'], $cart_weight_price ['amount'], $cart_weight_price ['number']);

            $shipping_list [$key] ['format_shipping_fee'] = price_format($shipping_fee, false);
            $shipping_list [$key] ['shipping_fee'] = $shipping_fee;
            $shipping_list [$key] ['free_money'] = price_format($shipping_cfg ['free_money'], false);
            $shipping_list [$key] ['insure_formated'] = strpos($val ['insure'], '%') === false ? price_format($val ['insure'], false) : $val ['insure'];

            /* 当前的配送方式是否支持保价 */
            if ($val ['shipping_id'] == $order ['shipping_id']) {
                $insure_disabled = ($val ['insure'] == 0);
                $cod_disabled = ($val ['support_cod'] == 0);
            }
	        // 兼容过滤ecjia配送方式
            if (substr($val['shipping_code'], 0 , 5) == 'ship_') {
                unset($shipping_list[$key]);
            }
        }
        $this->assign('shipping_list', $shipping_list);
        $this->assign('insure_disabled', $insure_disabled);
        $this->assign('cod_disabled', $cod_disabled);
		
		
		/* 取得支付列表 */
        if ($order ['shipping_id'] == 0) {
            $cod = true;
            $cod_fee = 0;
        } else {
            $shipping = model('Shipping')->shipping_info($order ['shipping_id']);
            $cod = $shipping ['support_cod'];

            if ($cod) {
                /* 如果是团购，且保证金大于0，不能使用货到付款 */
                if ($flow_type == CART_GROUP_BUY_GOODS) {
                    $group_buy_id = $_SESSION ['extension_id'];
                    if ($group_buy_id <= 0) {
                        crowd_show_message('error group_buy_id');
                    }
                    $group_buy = model('GroupBuyBase')->group_buy_info($group_buy_id);
                    if (empty($group_buy)) {
                        crowd_show_message('group buy not exists: ' . $group_buy_id);
                    }

                    if ($group_buy ['deposit'] > 0) {
                        $cod = false;
                        $cod_fee = 0;

                        /* 赋值保证金 */
                        $this->assign('gb_deposit', $group_buy ['deposit']);
                    }
                }

                if ($cod) {
                    $shipping_area_info = model('Shipping')->shipping_area_info($order ['shipping_id'], $region);
                    $cod_fee = $shipping_area_info ['pay_fee'];
                }
            } else {
                $cod_fee = 0;
            }
        }

        // 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
        $payment_list = model('Order')->available_payment_list(1, $cod_fee);
        if (isset($payment_list)) {
            foreach ($payment_list as $key => $payment) {
                // 只保留显示手机版支付方式
                if(!file_exists(ROOT_PATH . 'plugins/payment/'.$payment['pay_code'].'.php')){
                    unset($payment_list[$key]);
                }
                if ($payment ['is_cod'] == '1') {
                    $payment_list [$key] ['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment ['format_pay_fee'] . '</span>';
                }

                /* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
                if ($payment ['pay_code'] == 'yeepayszx' && $total ['amount'] > 300) {
                    unset($payment_list [$key]);
                }
                /* 如果有余额支付 */
                if ($payment ['pay_code'] == 'balance') {
                    /* 如果未登录，不显示 */
                    if ($_SESSION ['user_id'] == 0) {
                        unset($payment_list [$key]);
                    } else {
                        if ($_SESSION ['flow_order'] ['pay_id'] == $payment ['pay_id']) {
                            $this->assign('disable_surplus', 1);
                        }
                    }
                }
                // 如果不是微信浏览器访问并且不是微信会员 则不显示微信支付
                if ($payment ['pay_code'] == 'wxpay' && !is_wechat_browser() && empty($_SESSION['openid'])) {
                    unset($payment_list [$key]);
                }
                // 兼容过滤ecjia支付方式
                if (substr($payment['pay_code'], 0 , 4) == 'pay_') {
                    unset($payment_list[$key]);
                }
            }
        }
        $this->assign('payment_list', $payment_list);
		
		//当前选中的支付方式
        if($order['pay_id']){
            $payment_selected = model('Crowdbuy')->crowd_payment_info($order['pay_id']);			
			if(file_exists(ROOT_PATH . 'plugins/payment/' . $payment_selected ['pay_code'] . '.php')){
                $payment_selected['format_pay_fee'] = strpos($payment_selected['pay_fee'], '%') !== false ? $payment_selected['pay_fee'] :  price_format($payment_selected['pay_fee'], false);
                $this->assign('payment_selected', $payment_selected);
            }
        }
		
		$this->assign('number', $_SESSION['number']);
		
	    $this->display('crowd/crowd_checkout.html');
	   
    }
	
	 /**
     * 改变配送方式
     */
    public function select_shipping() {

        // 格式化返回数组
        $result = array(
            'error' => '',
            'content' => '',
            'need_insure' => 0
        );
        /* 取得购物类型 */
        $flow_type = isset($_SESSION ['flow_type']) ? intval($_SESSION ['flow_type']) : CART_GENERAL_GOODS;
        /* 获得收货人信息 */
        $consignee = model('Order')->get_consignee($_SESSION ['user_id']);
        /* 对商品信息赋值 */
        $cart_goods = model('Crowdbuy')->cart_crowd_goods($_SESSION['goods_id'], $_SESSION['cp_id'], $_SESSION['number']);  //项目信息	
        if (empty($cart_goods) || !model('Order')->check_consignee_info($consignee, $flow_type)) {
            $result ['error'] = L('no_goods_in_cart');
        } else {
            /* 取得购物流程设置 */
            $this->assign('config', C('CFG'));
            /* 取得订单信息 */
			$order = model('Crowdbuy')->crowd_flow_order_info();
			
            $order ['shipping_id'] = intval($_REQUEST ['shipping']);
            $regions = array(
                $consignee ['country'],
                $consignee ['province'],
                $consignee ['city'],
                $consignee ['district']
            );
            $shipping_info = model('Shipping')->shipping_area_info($order ['shipping_id'], $regions);


            /* 计算订单的费用 */
			$total = model('Crowdbuy')->crowd_order_fee($order, $cart_goods, $consignee);
            $this->assign('total', $total);

            /* 取得可以得到的积分和红包 */
            /* $this->assign('total_integral', model('Order')->cart_amount(false, $flow_type) - $total ['bonus'] - $total ['integral_money']);
            $this->assign('total_bonus', price_format(model('Order')->get_total_bonus(), false));  */

            /* 团购标志 */
            if ($flow_type == CART_GROUP_BUY_GOODS) {
                $this->assign('is_group_buy', 1);
            }
			$result['amount'] = $total['amount_formated'];
            $result ['cod_fee'] = $shipping_info ['pay_fee'];
            if (strpos($result ['cod_fee'], '%') === false) {
                $result ['cod_fee'] = price_format($result ['cod_fee'], false);
            }
            $result ['need_insure'] = ($shipping_info ['insure'] > 0 && !empty($order ['need_insure'])) ? 1 : 0;
            $result ['content'] = ECTouch::$view->fetch('crowd/order_total.html');
        }
        echo json_encode($result);
    }
	
	/**
     * 收货地址列表
     */
    public function crowd_consignee_list() {


            // 获得用户所有的收货人信息
            $consignee_list = model('Users')->get_consignee_list($_SESSION['user_id'], 0);

            if ($consignee_list) {
                foreach ($consignee_list as $k => $v) {
                    $address = '';
                    if ($v['province']) {
                        $address .= model('RegionBase')->get_region_name($v['province']);
                    }
                    if ($v['city']) {
                        $address .= model('RegionBase')->get_region_name($v['city']);
                    }
                    if ($v['district']) {
                        $address .= model('RegionBase')->get_region_name($v['district']);
                    }
                    $consignee_list[$k]['address'] = $address . ' ' . $v['address'];
                    $consignee_list[$k]['url'] = url('crowdflow/crowd_consignee', array('id' => $v ['address_id']));
                }
            }
        // 赋值于模板
		$this->assign('consignee_list', $consignee_list);
        $this->assign('title', L('consignee_info'));
	
		$this->display('crowd/crowd_flow_consignee_list.html');
	}
	
	
	/**
     * 收货地址添加修改
     */
    public function crowd_consignee() {
		  if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
            /* 取得购物类型 */
            $flow_type = isset($_SESSION ['flow_type']) ? intval($_SESSION ['flow_type']) : CART_GENERAL_GOODS;
            //收货人信息填写界面
            if (isset($_REQUEST ['direct_shopping'])) {
                $_SESSION ['direct_shopping'] = 1;
            }

            /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
            $this->assign('country_list', model('RegionBase')->get_regions());
            $this->assign('shop_country', C('shop_country'));
            $this->assign('shop_province_list', model('RegionBase')->get_regions(1, C('shop_country')));

            /* 获得用户所有的收货人信息 */
            if ($_SESSION ['user_id'] > 0) {
                $addressId = I('get.id');
                if ($addressId > 0) {
                    $consignee_list[] = model('Users')->get_consignee_list($_SESSION ['user_id'], $addressId);
                } else {
					if(!empty($_SESSION['consignee'])){
						$consignee = $_SESSION['consignee'];
						$consignee_list [] = array(
                        'country' => C('shop_country'),
						'province' => $consignee['province'],						   
						'city' => $consignee['city'],						   
						'district' => $consignee['district'],
						   
						);
					}else{
						$consignee_list [] = array(
                        'country' => C('shop_country'),
						);						
					}                    
                }
            } else {
                if (isset($_SESSION ['flow_consignee'])) {
                    $consignee_list = array(
                        $_SESSION ['flow_consignee']
                    );
                } else {
                    $consignee_list [] = array(
                        'country' => C('shop_country')
                    );
                }
            }
            $this->assign('name_of_region', array(
                C('name_of_region_1'),
                C('name_of_region_2'),
                C('name_of_region_3'),
                C('name_of_region_4')
            ));
            $this->assign('consignee_list', $consignee_list);

            /* 取得每个收货地址的省市区列表 */
            $city_list = array();
            $district_list = array();
            foreach ($consignee_list as $region_id => $consignee) {
                $consignee ['country'] = isset($consignee ['country']) ? intval($consignee ['country']) : 1;
                $consignee ['province'] = isset($consignee ['province']) ? intval($consignee ['province']) : 0;
                $consignee ['city'] = isset($consignee ['city']) ? intval($consignee ['city']) : 0;

                $city_list [$region_id] = model('RegionBase')->get_regions(2, $consignee ['province']);
                $district_list [$region_id] = model('RegionBase')->get_regions(3, $consignee ['city']);
            }
            $this->assign('province_list', model('RegionBase')->get_regions(1, $consignee ['country']));
            $this->assign('city_list', $city_list);
            $this->assign('district_list', $district_list);

            /* 返回收货人页面代码 */
            $this->assign('real_goods_count', model('Order')->exist_real_goods(0, $flow_type) ? 1 : 0 );
        } else {
            /*  保存收货人信息 	 */
            $consignee = array(
                'address_id' => empty($_POST ['address_id']) ? 0 : intval($_POST ['address_id']),
                'consignee' => empty($_POST ['consignee']) ? '' : I('post.consignee'),
                'country' => empty($_POST ['country']) ? '' : intval($_POST ['country']),
                'province' => empty($_POST ['province']) ? '' : intval($_POST ['province']),
                'city' => empty($_POST ['city']) ? '' : intval($_POST ['city']),
                'district' => empty($_POST ['district']) ? '' : intval($_POST ['district']),
                'address' => empty($_POST ['address']) ? '' : I('post.address'),
                'mobile' => empty($_POST ['mobile']) ? '' : make_semiangle(I('post.mobile'))
            );

            if ($_SESSION ['user_id'] > 0) {
                /* 如果用户已经登录，则保存收货人信息 */
                $consignee ['user_id'] = $_SESSION ['user_id'];
                model('Users')->save_consignee($consignee, true);
            }

            /* 保存到session */
            $_SESSION ['flow_consignee'] = stripslashes_deep($consignee);
            ecs_header("Location: " . url('crowdflow/crowd_checkout') . "\n");
        }

        $this->assign('currency_format', C('currency_format'));
        $this->assign('integral_scale', C('integral_scale'));
        $this->assign('step', ACTION_NAME);
        $this->assign('title', L('consignee_info'));

		$this->display('crowd/crowd_flow_consignee.html');
	}
	
	/*设置默认收货地址*/
	public function crowd_edit_address_info() {
		if (IS_AJAX && IS_AJAX) {
            $address_id = I('id');
			$data['address_id'] = $address_id;
            $condition['user_id'] = $_SESSION['user_id'];
			$this->model->table('users')->data($data)->where($condition)->update();	
			unset($_SESSION['flow_consignee']);
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
         }
		 
	}
	
	 /**
     * 删除收货地址
     */
    public function crowd_del_address_list() {
        $id = intval($_GET['id']);

        if (model('Users')->drop_consignee($id)) {
            $url = url('crowdflow/crowd_consignee_list');
			unset($_SESSION['flow_consignee']);
            ecs_header("Location: $url\n");
            exit();
        } else {
            show_message(L('del_address_false'));
        }
    }
	
	/**
     *  提交订单
     */
	 public function crowd_done() {
		
		//$goods_id = I('post.goods_id', 0);
		//$cp_id = I('post.cp_id', 0);
		//$number = I('post.number', 0);
		//$_SESSION['goods_id'] =$goods_id ;
		//$_SESSION['cp_id'] = $cp_id ;
		//$_SESSION['number'] = $number ;	

		if(empty($_SESSION['goods_id']) && empty($_SESSION['cp_id'])&& empty($_SESSION['number'])){
			//ecs_header("Location: " . url('index/index') . "\n");
			crowd_show_message('暂无商品', '去选购', url('crowdfunding/index'), 'info');
			 
		 }		
        // 检查用户是否已经登录 如果用户已经登录了则检查是否有默认的收货地址 如果没有登录则跳转到登录和注册页面
        if (empty($_SESSION ['direct_shopping']) && $_SESSION ['user_id'] == 0) {
            /* 用户没有登录且没有选定匿名购物，转向到登录页面 */
            ecs_header("Location: " . url('user/login') . "\n");
        }
        //验证购买方案是否超出
        model('Mycrowd')->check_order($_SESSION['goods_id'],$_SESSION['cp_id'],$_SESSION['number']);
		
		/*判断重复商品订单 是否支付 */		
		$condition = "user_id = '".$_SESSION[user_id]."' AND goods_id = '".$_SESSION['goods_id']."' AND cp_id = '".$_SESSION['cp_id']."' AND pay_status = 0 and order_status !=2 ";
        $order_num = $this->model->table('crowd_order_info')->field('count(order_id)')->where($condition)->getOne();
		if($order_num > 0)
		{
			//show_message('您有未支付的众筹订单，请付款后再提交新订单','返回上一页',U('mycrowd/index/order'));
			crowd_show_message('您有未支付的众筹订单，请付款后再提交新订单', '去支付', url('mycrowd/crowd_order'), 'info');
		}

		
        // 获取收货人信息
        $consignee = model('Order')->get_consignee($_SESSION ['user_id']);
        /* 检查收货人信息是否完整 */
        if (!model('Order')->check_consignee_info($consignee, $flow_type)) {
            /* 如果不完整则转向到收货人信息填写界面 */
            ecs_header("Location: " . url('crowdflow/crowd_consignee_list') . "\n");
        }

        // 处理接收信息
        $how_oos = I('post.how_oos', 0);
        $card_message = I('post.card_message',  '');
        $inv_type = I('post.inv_type', '');
        $inv_payee = I('post.inv_payee', '');
        $inv_content = I('post.inv_content','');
        $postscript = I('post.postscript', '');
        $oos = L('oos.' . $how_oos);
        // 订单信息
        $order = array(
            'shipping_id' => I('post.shipping_id'),
            'pay_id' => I('post.payment_id'), // 付款方式
            'pack_id' => I('post.pack', 0),
            'card_id' => isset($_POST ['card']) ? intval($_POST ['card']) : 0,
            'card_message' => $card_message,
            'surplus' => isset($_POST ['surplus']) ? floatval($_POST ['surplus']) : 0.00,
            'integral' => isset($_POST ['integral']) ? intval($_POST ['integral']) : 0,
            'bonus_id' => isset($_POST ['bonus']) ? intval($_POST ['bonus']) : 0,
            'need_inv' => empty($_POST ['need_inv']) ? 0 : 1,
            'inv_type' => $inv_type,
            'inv_payee' => $inv_payee,
            'inv_content' => $inv_content,
            'postscript' => $postscript,//订单留言
            'how_oos' => isset($oos) ? addslashes("$oos") : '',
            'need_insure' => isset($_POST ['need_insure']) ? intval($_POST ['need_insure']) : 0,
            'user_id' => $_SESSION ['user_id'],
            'add_time' => time(),
            'order_status' => OS_UNCONFIRMED,
            'shipping_status' => SS_UNSHIPPED,
            'pay_status' => PS_UNPAYED,
            'agency_id' => model('Order')->get_agency_by_regions(array(
                $consignee ['country'],
                $consignee ['province'],
                $consignee ['city'],
                $consignee ['district']
            ))
        );

		
		/* 检查积分余额是否合法 */
        $user_id = $_SESSION ['user_id'];
        if ($user_id > 0) {

            $user_info = model('Order')->user_info($user_id);
            $order ['surplus'] = min($order ['surplus'], $user_info ['user_money'] + $user_info ['credit_line']);
            if ($order ['surplus'] < 0) {
                $order ['surplus'] = 0;
            }

            // 查询用户有多少积分
            $flow_points = model('Flow')->flow_available_points(); // 该订单允许使用的积分
            $user_points = $user_info ['pay_points']; // 用户的积分总数

            $order ['integral'] = min($order ['integral'], $user_points, $flow_points);
            if ($order ['integral'] < 0) {
                $order ['integral'] = 0;
            }
        } else {
            $order ['surplus'] = 0;
            $order ['integral'] = 0;
        }

		
		
        /* 订单中的商品 */
        $cart_goods = model('Crowdbuy')->cart_crowd_goods($_SESSION['goods_id'], $_SESSION['cp_id'], $_SESSION['number']);  
        if (empty($cart_goods)) {
            show_message(L('no_goods_in_cart'), L('back_home'), './', 'warning');
        }
        /* 检查商品总额是否达到最低限购金额 */
        if ($flow_type == CART_GENERAL_GOODS && model('Order')->cart_amount(true, CART_GENERAL_GOODS) < C('min_goods_amount')) {
            show_message(sprintf(L('goods_amount_not_enough'), price_format(C('min_goods_amount'), false)));
        }
		
        /* 收货人信息 */
        foreach ($consignee as $key => $value) {
            $order [$key] = addslashes($value);
        }

        /* 订单中的总额 */
        $total = model('Crowdbuy')->crowd_order_fee($order, $cart_goods, $consignee);
        $order ['bonus'] = $total ['bonus'];
        $order ['goods_amount'] = $total ['goods_price'];
        $order ['discount'] = $total ['discount'];
        $order ['surplus'] = $total ['surplus'];
        $order ['tax'] = $total ['tax'];

        // 购物车中的商品能享受红包支付的总额
        $discount_amout = model('Order')->compute_discount_amount();
        // 红包和积分最多能支付的金额为商品总额
        $temp_amout = $order ['goods_amount'] - $discount_amout;
        if ($temp_amout <= 0) {
            $order ['bonus_id'] = 0;
        }

        /* 配送方式 */
        if ($order ['shipping_id'] > 0) {
            $shipping = model('Shipping')->shipping_info($order ['shipping_id']);
            $order ['shipping_name'] = addslashes($shipping ['shipping_name']);
        }
		
        $order ['shipping_fee'] = $total ['shipping_fee'];
        $order ['insure_fee'] = $total ['shipping_insure'];
        /* 支付方式 */
        if ($order ['pay_id'] > 0) {
            $payment = model('Order')->payment_info($order ['pay_id']);
            $order ['pay_name'] = addslashes($payment ['pay_name']);
        }

        $order ['pay_fee'] = $total ['pay_fee'];
        $order ['cod_fee'] = $total ['cod_fee'];

		
        /* 商品包装 */
        if ($order ['pack_id'] > 0) {
            $pack = model('Order')->pack_info($order ['pack_id']);
            $order ['pack_name'] = addslashes($pack ['pack_name']);
        }
        $order ['pack_fee'] = $total ['pack_fee'];

        /* 祝福贺卡 */
        if ($order ['card_id'] > 0) {
            $card = model('Order')->card_info($order ['card_id']);
            $order ['card_name'] = addslashes($card ['card_name']);
        }
        $order ['card_fee'] = $total ['card_fee'];
        $order ['order_amount'] = number_format($total ['amount'], 2, '.', '');

        /* 如果全部使用余额支付，检查余额是否足够 */
        if ($payment ['pay_code'] == 'balance' && $order ['order_amount'] > 0) {
            if ($order ['surplus'] > 0) {    // 余额支付里如果输入了一个金额
                $order ['order_amount'] = $order ['order_amount'] + $order ['surplus'];
                $order ['surplus'] = 0;
            }
            if ($order ['order_amount'] > ($user_info ['user_money'] + $user_info ['credit_line'])) {
                show_message(L('balance_not_enough'));
            } else {
                $order ['surplus'] = $order ['order_amount'];
                $order ['order_amount'] = 0;
            }
        }

        /* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
        if ($order ['order_amount'] <= 0) {
            $order ['order_status'] = OS_CONFIRMED;
            $order ['confirm_time'] = time();
            $order ['pay_status'] = PS_PAYED;
            $order ['pay_time'] = time();
            $order ['order_amount'] = 0;
        }

        $order ['integral_money'] = $total ['integral_money'];
        $order ['integral'] = $total ['integral'];



        $order ['from_ad'] = !empty($_SESSION ['from_ad']) ? $_SESSION ['from_ad'] : '0';
        $order ['referer'] = !empty($_SESSION ['referer']) ? addslashes($_SESSION ['referer']). 'Touch' : 'Touch';

        /* 记录扩展信息 */       
        $order ['extension_code'] = 'crowd_buy';
  

        $parent_id = M()->table('users')->field('parent_id')->where("user_id=".$_SESSION['user_id'])->getOne();
        $order ['parent_id'] = $parent_id;
		/* 插入众筹项目信息 */ 
		$order ['goods_id'] = $cart_goods['goods_id'];
		$order ['cp_id'] = $cart_goods['cp_id'];
		$order ['goods_name'] = $cart_goods['goods_name'];
		$order ['goods_number'] = $cart_goods['number'];
		$order ['goods_price'] = $cart_goods['shop_price'];
		
        /* 插入订单表 */
        $error_no = 0;
        do {
            $order ['order_sn'] = get_order_sn(); // 获取新订单号
            $new_order = model('Common')->filter_field('crowd_order_info', $order);
            $this->model->table('crowd_order_info')->data($new_order)->insert();
            $error_no = M()->errno();

            if ($error_no > 0 && $error_no != 1062) {
                die(M()->errorMsg());
            }
        } while ($error_no == 1062); // 如果是订单号重复则重新提交数据
        $new_order_id = M()->insert_id();
        $order ['order_id'] = $new_order_id;	


		/* 如果全部使用余额支付，检查余额是否足够 */
        if ($payment ['pay_code'] == 'balance' && $order ['surplus'] > 0) {
			//验证项目是否成功
			$crowd_goods = $this->model->table('crowd_goods')->field('sum_price')->where("goods_id = '" . $order['goods_id'] . "'  ")->find();
			$total_price = model('Crowdfunding')->crowd_buy_price($order['goods_id']);
			if($total_price >= $crowd_goods['sum_price']){
				$data['status'] = 1;
				$this->model->table('crowd_goods')->data($data)->where("goods_id = '" . $order['goods_id'] . "'  ")->update();			
			}
			
		}
		
		
		
        /* 处理余额、积分、红包 */
        if ($order ['user_id'] > 0 && $order ['surplus'] > 0) {
            model('ClipsBase')->log_account_change($order ['user_id'], $order ['surplus'] * (- 1), 0, 0, 0, sprintf(L('pay_order'), $order ['order_sn']));
			//余额付款更新众筹信息
			model('Crowdbuy')->update_crowd($order['order_id']);
			
        }
        if ($order ['user_id'] > 0 && $order ['integral'] > 0) {
            model('ClipsBase')->log_account_change($order ['user_id'], 0, 0, 0, $order ['integral'] * (- 1), sprintf(L('pay_order'), $order ['order_sn']));
        }

        if ($order ['bonus_id'] > 0 && $temp_amout > 0) {
            model('Order')->use_bonus($order ['bonus_id'], $new_order_id);
        }


        /* 给商家发邮件 */
        /* 增加是否给客服发送邮件选项 */
        if (C('send_service_email') && C('service_email') != '') {
            $tpl = model('Base')->get_mail_template('remind_of_new_order');
            $this->assign('order', $order);
            $this->assign('goods_list', $cart_goods);
            $this->assign('shop_name', C('shop_name'));
            $this->assign('send_date', date(C('time_format')));
            $content = ECTouch::$view->fetch('str:' . $tpl ['template_content']);
            send_mail(C('shop_name'), C('service_email'), $tpl ['template_subject'], $content, $tpl ['is_html']);
        }

        /* 如果需要，发短信 */
        if (C('sms_order_placed') == '1' && C('sms_shop_mobile') != '') {
            $sms = new EcsSms();
            $msg = $order ['pay_status'] == PS_UNPAYED ? L('order_placed_sms') : L('order_placed_sms') . '[' . L('sms_paid') . ']';
            $sms->send(C('sms_shop_mobile'), sprintf($msg, $order ['consignee'], $order ['mobile']), '', 13, 1);
        }
        /* 如果需要，微信通知 by wanglu */
        // if (method_exists('WechatController', 'do_oauth')) {
        //     $order_url = __HOST__ . url('user/order_detail', array('order_id' => $order ['order_id']));
        //     $order_url = urlencode(base64_encode($order_url));
        //     send_wechat_message('order_remind', '', $order['order_sn'] . L('order_effective'), $order_url, $order['order_sn']);
        // }
        /* 如果订单金额为0 处理虚拟卡 */
        if ($order ['order_amount'] <= 0) {
            $sql = "SELECT goods_id, goods_name, goods_number AS num FROM " . $this->model->pre . "cart WHERE is_real = 0 AND extension_code = 'virtual_card'" . " AND session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";
            $res = $this->model->query($sql);

            $virtual_goods = array();
            foreach ($res as $row) {
                $virtual_goods ['virtual_card'] [] = array(
                    'goods_id' => $row ['goods_id'],
                    'goods_name' => $row ['goods_name'],
                    'num' => $row ['num']
                );
            }

            if ($virtual_goods and $flow_type != CART_GROUP_BUY_GOODS) {
                /* 虚拟卡发货 */
                if (model('OrderBase')->virtual_goods_ship($virtual_goods, $msg, $order ['order_sn'], true)) {
                    /* 如果没有实体商品，修改发货状态，送积分和红包 */
                    $count = $this->model->table('order_goods')->field('COUNT(*)')->where("order_id = '$order[order_id]' " . " AND is_real = 1")->getOne();
                    if ($count <= 0) {
                        /* 修改订单状态 */
                        model('Users')->update_order($order ['order_id'], array(
                            'shipping_status' => SS_SHIPPED,
                            'shipping_time' => time()
                        ));

                        /* 如果订单用户不为空，计算积分，并发给用户；发红包 */
                        if ($order ['user_id'] > 0) {
                            /* 取得用户信息 */
                            $user = model('Order')->user_info($order ['user_id']);

                            /* 计算并发放积分 */
                            $integral = model('Order')->integral_to_give($order);
                            model('ClipsBase')->log_account_change($order ['user_id'], 0, 0, intval($integral ['rank_points']), intval($integral ['custom_points']), sprintf(L('order_gift_integral'), $order ['order_sn']));

                            /* 发放红包 */
                            model('Order')->send_order_bonus($order ['order_id']);
                        }
                    }
                }
            }
        }


        /* 插入支付日志 */
        $order ['log_id'] = model('ClipsBase')->insert_pay_log($new_order_id, $order ['order_amount'], PAY_CROWD);
		$order['zc_apply'] = 'crowd';//
        /* 取得支付信息，生成支付代码 */
        if ($order ['order_amount'] > 0) {
            $payment = model('Order')->payment_info($order ['pay_id']);
			
            include_once (ROOT_PATH . 'plugins/payment/' . $payment ['pay_code'] . '.php');

            $pay_obj = new $payment ['pay_code'] ();

            $pay_online = $pay_obj->get_code($order, unserialize_config($payment ['pay_config']));

            $order ['pay_desc'] = $payment ['pay_desc'];

            $this->assign('pay_online', $pay_online);
        }
        if (!empty($order ['shipping_name'])) {
            $order ['shipping_name'] = trim(stripcslashes($order ['shipping_name']));
        }
        // 如果是银行汇款或货到付款 则显示支付描述
        if ($payment['pay_code'] == 'bank' || $payment['pay_code'] == 'cod'){
            if (empty($order ['pay_name'])) {
                $order ['pay_name'] = trim(stripcslashes($payment ['pay_name']));
            }
            $this->assign('pay_desc',$order['pay_desc']);
        }
        // 货到付款不显示
        if ($payment ['pay_code'] != 'balance') {
            /* 生成订单后，修改支付，配送方式 */

            // 支付方式
            $payment_list = model('Order')->available_payment_list(0);
            if (isset($payment_list)) {
                foreach ($payment_list as $key => $payment) {

                    /* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
                    if ($payment ['pay_code'] == 'yeepayszx' && $total ['amount'] > 300) {
                        unset($payment_list [$key]);
                    }
                    // 过滤掉当前的支付方式
                    if ($payment ['pay_id'] == $order ['pay_id']) {
                        unset($payment_list [$key]);
                    }
                    /* 如果有余额支付 */
                    if ($payment ['pay_code'] == 'balance') {
                        /* 如果未登录，不显示 */
                        if ($_SESSION ['user_id'] == 0) {
                            unset($payment_list [$key]);
                        } else {
                            if ($_SESSION ['flow_order'] ['pay_id'] == $payment ['pay_id']) {
                                $this->assign('disable_surplus', 1);
                            }
                        }
                    }
                    // 如果不是微信浏览器访问并且不是微信会员 则不显示微信支付
                    if ($payment ['pay_code'] == 'wxpay' && !is_wechat_browser() && empty($_SESSION['openid'])) {
                        unset($payment_list [$key]);
                    }
                    // 兼容过滤ecjia支付方式
                    if (substr($payment['pay_code'], 0 , 4) == 'pay_') {
                        unset($payment_list[$key]);
                    }
                }
            }
            $this->assign('payment_list', $payment_list);
            $this->assign('pay_code', 'no_balance');
        }
        $order['pay_code'] = $payment ['pay_code'];


        /* 订单信息 */
        $this->assign('order', $order);

        $this->assign('total', $total);
        $this->assign('goods_list', $cart_goods);
        $this->assign('order_submit_back', sprintf(L('order_submit_back'), L('back_home'), L('goto_user_center'))); // 返回提示

        user_uc_call('add_feed', array($order ['order_id'], BUY_GOODS)); // 推送feed到uc
        unset($_SESSION ['flow_consignee']); // 清除session中保存的收货人信息
        unset($_SESSION ['flow_order']);
        unset($_SESSION ['direct_shopping']);
		// 清除session中保存项目信息
		unset($_SESSION['goods_id']); 
        unset($_SESSION['cp_id']);
        unset($_SESSION['number']);

        $this->assign('currency_format', C('currency_format'));
        $this->assign('integral_scale', C('integral_scale'));
        $this->assign('step', ACTION_NAME);

        $this->assign('title', L('order_submit'));
		 
		$this->display('crowd/crowd_done.html');
	 }
	 
	 //======
	 
	 protected $user_id;
    protected $action;
    protected $back_act = '';

	 /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
		$this->user_id = $_SESSION['user_id'];
		$this->action = ACTION_NAME;
		// 验证登录
        $this->check_login();

        // 用户信息
        $info = model('ClipsBase')->get_user_default($this->user_id);
        // 显示第三方API的头像
        if(isset($_SESSION['avatar'])){
            $info['avatar'] = $_SESSION['avatar'];
        }

		// 如果是显示页面，对页面进行相应赋值
        assign_template();
        $this->size = 10;
        $this->page = 1;
        $this->assign('page', $this->page);

		$this->assign('info', $info);
		$this->assign('action', $this->action);
    }

    /**
     * 众筹项目列表信息
     */
    public function index() {

        $recommend = model('Mycrowd')->recom_list();//获取推荐众筹
		$this->assign('recommend', $recommend);
        $this->display('crowd/crowd_user.html');
    }

	/**
     * 关注众筹项目列表信息
     */
    public function crowd_like() {
		$this->type = I('request.type') ? intval(I('request.type')) : 1 ;
        $this->page = I('request.page') > 0 ? intval(I('request.page')) : 1;

        if (IS_AJAX) {

            $like = model('Mycrowd')->like_list($this->user_id, $this->type,$this->page,$this->size);//获取我的支持众筹项目
            die(json_encode(array('list' => $like)));
            exit();

         }

        $like = model('Mycrowd')->like_list($this->user_id, $this->type,$this->page,$this->size);//获取关注众筹
        if(empty($like)){
            $this->assign('is_show', '1');
        }

		$this->assign('like', $like);
        $this->assign('page', $this->page);
        $this->assign('size', $this->size);
		$this->assign('type', $this->type);
        $this->display('crowd/crowd_like.html');
    }

	/**
     * 我的支持众筹项目列表信息
     */
    public function crowd_buy() {
		$this->type = I('request.type') ? intval(I('request.type')) : 1 ;
        $this->page = I('request.page') > 0 ? intval(I('request.page')) : 1;

        if (IS_AJAX) {
            $buy_list = model('Mycrowd')->crowd_buy_list($this->user_id, $this->type,$this->page,$this->size);//获取我的支持众筹项目
            die(json_encode(array('list' => $buy_list)));
            exit();

         }

        $buy_list = model('Mycrowd')->crowd_buy_list($this->user_id, $this->type,$this->page,$this->size);//获取我的支持众筹项目
        if(empty($buy_list)){
            $this->assign('is_show', '1');
        }

        $this->assign('buy_list', $buy_list);
        $this->assign('page', $this->page);
        $this->assign('size', $this->size);
		$this->assign('type', $this->type);
        $this->display('crowd/crowd_buy.html');
    }


	/**
     * 关于众筹
     */
    public function crowd_articlecat() {
        $sql = 'SELECT cat_id, cat_name' .
            ' FROM ' .$this->model->pre. 'article_cat ' .
            ' WHERE cat_type = 1 AND parent_id = 0' .
            ' ORDER BY sort_order ASC';
        $data = $this->model->query($sql);
        foreach($data as $key=>$vo){
            $data[$key]['url'] = url('crowd_art_list', array('id'=>$vo['cat_id']));
        }
        $this->assign('data', $data); //文章分类树
        $this->display('crowd/crowd_help.html');
    }


	/**
     * 关余众筹详细order_list
     */
    public function crowd_art_list() {
		//$id = I('request.id') ? intval(I('request.id')) : 0 ;
        $sql = 'SELECT title, 	description' .
            ' FROM ' .$this->model->pre. 'crowd_article ' .
            " WHERE is_open = 1 " ;
        $data = $this->model->query($sql);
        $this->assign('data', $data);
        $this->display('crowd/crowd_problem.html');
    }


	/**
     * 众筹订单
     */
    public function crowd_order() {

		if(empty($_GET['status']) && !empty($_SESSION['pay'])){
			$_SESSION['pay'] = $_SESSION['pay'];
		}elseif(!empty($_SESSION['pay']) && $_SESSION['pay'] != I('request.status')){
			$this->status = I('request.status') ? intval(I('request.status')) : 1 ;
			$_SESSION['pay'] = $this->status;
		}else{
			$this->status = I('request.status') ? intval(I('request.status')) : 1 ;
			$_SESSION['pay'] = $this->status;
		}
        $size = I(C('page_size'),5);
        $count = model('Mycrowd')->crowd_orders_num($this->user_id, $_SESSION['pay']);//获取订单数量
        $filter['page'] = '{page}';
        $offset = $this->pageLimit(url('crowd_order', $filter), $size);
        $offset_page = explode(',', $offset);
        $orders = model('Mycrowd')->crowd_user_orders($this->user_id, $_SESSION['pay'], $offset_page[1], $offset_page[0]);
        $this->assign('title', L('order_list_lnk'));
        $this->assign('pager', $this->pageShow($count));
		$this->assign('status', $_SESSION['pay']);
        $this->assign('orders_list', $orders);


        $this->display('crowd/crowd_order.html');
    }

	/**
     * 众筹订单详情
     */
    public function crowd_order_detail() {
		$order_id = I('request.order_id') ? intval(I('request.order_id')) : 0 ;
		// 订单详情
		$order = model('Mycrowd')->get_order_detail($order_id, $this->user_id);
		$goods_list = model('Mycrowd')->order_goods($order['order_id']);//获取订单商品详情
		//dump($order);
		$this->assign('goods', $goods_list);
		$this->assign('order', $order);

        $this->display('crowd/crowd_order_detail.html');
    }


	/**
     * 获取订单商品的评论
    */
    public function crowd_comment() {
		 if (IS_POST) {
            $data = array(
                'user_id' => $this->user_id,
                'user_name' => $_SESSION['user_name'],
                'content' => I('post.content'),
				'add_time' => time(),
                'order_id' => I('post.order_id', 0),
				'goods_id' => I('post.goods_id', 0)

            );
            $orders = $this->model->table('crowd_comment')->field('id')->where(array('order_id' =>$data['order_id']))->select();
            if(empty($orders)){
                $this->model->table('crowd_comment')
                        ->data($data)
                        ->insert();
                crowd_show_message('评论提交成功', '返回', url('crowd_order'), 'info');
            }else{
                crowd_show_message('请勿重复提交', '返回', url('index'), 'info');
            }           
        }

		$order_id = I('request.order_id') ? intval(I('request.order_id')) : 0 ;
		$goods_id = I('request.id') ? intval(I('request.id')) : 0 ;
		$sql = "SELECT cg.goods_img, cg.goods_name FROM " . $this->model->pre . "order_info as o left join " . $this->model->pre . "order_goods as g on o.order_id = g.order_id left join ". $this->model->pre ."crowd_goods as cg on g.goods_id = cg.goods_id " ." WHERE o.order_id = '$order_id' and  o.extension_code = 'crowd_buy'  limit 1";
		$order = $this->model->query($sql);
		foreach ($order AS $key => $row) {
            $order[$key]['goods_img'] = 'data/attached/crowdimage/'.$row['goods_img'];

		}
		$this->assign('order_id', $order_id);
		$this->assign('goods_id', $goods_id);
		$this->assign('order', $order);

		$this->display('crowd/crowd_comment.html');
    }



	/**
    * 取消订单
    */
    public function cancel_order() {
        $order_id = I('get.order_id', 0, 'intval');

        if (model('Mycrowd')->cancel_order($order_id, $this->user_id)) {
            $url = url('crowd_order');
            ecs_header("Location: $url\n");
            exit();
        } else {
            ECTouch::err()->show(L('order_list_lnk'), url('crowd_order'));
        }
    }



	/**
     * 确认收货
     */
    public function affirm_received() {
        $order_id = I('get.order_id', 0, 'intval');
        if (model('Mycrowd')->affirm_received($order_id, $this->user_id)) {
            ecs_header("Location: " . url('crowd_order') . "\n");
            exit();
        } else {
            ECTouch::err()->show(L('order_list_lnk'), url('crowd_order'));
        }
    }

	 /**
     * 订单跟踪
     */
    public function order_tracking() {
        $order_id = I('get.order_id', 0);
        $ajax = I('get.ajax', 0);
        $where['user_id'] = $this->user_id;
        $where['order_id'] = $order_id;
        $orders = $this->model->table('crowd_order_info')->field('order_id, order_sn, invoice_no, shipping_name, shipping_id')->where($where)->find();

        // 生成快递100查询接口链接
        $shipping = get_shipping_object($orders['shipping_id']);
        // 接口模式
        $query_link = $shipping->query($orders['invoice_no']);
        $get_content = Http::doGet($query_link);
        $get_content_data = json_decode($get_content, 1);
        if($get_content_data['status'] != '200'){
            // 跳转模式
            $query_link = $shipping->third_party($orders['invoice_no']);
            if($query_link){
                header('Location: '.$query_link);
                exit();
            }
        }
        $this->assign('title', L('order_tracking'));
        $this->assign('trackinfo', $get_content);
        $this->display('user_order_tracking.dwt');
    }


	/**
    * 验证登录
    */
	private function check_login() {
        // 是否登录
        if(empty($this->user_id)){
            $url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            redirect(url('user/login', array('referer' => urlencode($url)) ));
            exit();
        }


	}

}
