<?php

/**
 * 设置自定义后台入口路由
 */

Route::group(ADMIN_PATH, function () {

    Route::any('index.php', 'dashboard/Index/index');

    Route::any('account_log.php', 'dashboard/AccountLog/index');

    Route::any('ad_position.php', 'dashboard/AdPosition/index');

    Route::any('admin_logs.php', 'dashboard/AdminLogs/index');

    Route::any('ads.php', 'dashboard/Ads/index');

    Route::any('adsense.php', 'dashboard/Adsense/index');

    Route::any('affiliate_ck.php', 'dashboard/AffiliateCk/index');

    Route::any('affiliate.php', 'dashboard/Affiliate/index');

    Route::any('agency.php', 'dashboard/Agency/index');

    Route::any('area_manage.php', 'dashboard/AreaManage/index');

    Route::any('article_auto.php', 'dashboard/ArticleAuto/index');

    Route::any('article.php', 'dashboard/Article/index');

    Route::any('articlecat.php', 'dashboard/Articlecat/index');

    Route::any('attention_list.php', 'dashboard/AttentionList/index');

    Route::any('attribute.php', 'dashboard/Attribute/index');

    Route::any('auction.php', 'dashboard/Auction/index');

    Route::any('bonus.php', 'dashboard/Bonus/index');

    Route::any('brand.php', 'dashboard/Brand/index');

    Route::any('captcha.php', 'dashboard/Captcha/index');

    Route::any('captcha_manage.php', 'dashboard/CaptchaManage/index');

    Route::any('card.php', 'dashboard/Card/index');

    Route::any('category.php', 'dashboard/Category/index');

    Route::any('check_file_priv.php', 'dashboard/CheckFilePriv/index');

    Route::any('cloud.php', 'dashboard/Cloud/index');

    Route::any('comment_manage.php', 'dashboard/CommentManage/index');

    Route::any('convert.php', 'dashboard/Convert/index');

    Route::any('cron.php', 'dashboard/Cron/index');

    Route::any('database.php', 'dashboard/Database/index');

    Route::any('edit_languages.php', 'dashboard/EditLanguages/index');

    Route::any('email_list.php', 'dashboard/EmailList/index');

    Route::any('exchange_goods.php', 'dashboard/ExchangeGoods/index');

    Route::any('favourable.php', 'dashboard/Favourable/index');

    Route::any('filecheck.php', 'dashboard/Filecheck/index');

    Route::any('flashplay.php', 'dashboard/Flashplay/index');

    Route::any('flow_stats.php', 'dashboard/FlowStats/index');

    Route::any('friend_link.php', 'dashboard/FriendLink/index');

    Route::any('gen_goods_script.php', 'dashboard/GenGoodsScript/index');

    Route::any('get_password.php', 'dashboard/GetPassword/index');

    Route::any('goods_auto.php', 'dashboard/GoodsAuto/index');

    Route::any('goods_batch.php', 'dashboard/GoodsBatch/index');

    Route::any('goods_booking.php', 'dashboard/GoodsBooking/index');

    Route::any('goods.php', 'dashboard/Goods/index');

    Route::any('goods_export.php', 'dashboard/GoodsExport/index');

    Route::any('goods_type.php', 'dashboard/GoodsType/index');

    Route::any('group_buy.php', 'dashboard/GroupBuy/index');

    Route::any('guest_stats.php', 'dashboard/GuestStats/index');

    Route::any('help.php', 'dashboard/Help/index');

    Route::any('integrate.php', 'dashboard/Integrate/index');

    Route::any('license.php', 'dashboard/License/index');

    Route::any('magazine_list.php', 'dashboard/MagazineList/index');

    Route::any('mail_template.php', 'dashboard/MailTemplate/index');

    Route::any('message.php', 'dashboard/Message/index');

    Route::any('navigator.php', 'dashboard/Navigator/index');

    Route::any('order.php', 'dashboard/Order/index');

    Route::any('order_stats.php', 'dashboard/OrderStats/index');

    Route::any('pack.php', 'dashboard/Pack/index');

    Route::any('package.php', 'dashboard/Package/index');

    Route::any('patch_num.php', 'dashboard/PatchNum/index');

    Route::any('payment.php', 'dashboard/Payment/index');

    Route::any('picture_batch.php', 'dashboard/PictureBatch/index');

    Route::any('privilege.php', 'dashboard/Privilege/index');

    Route::any('receive.php', 'dashboard/Receive/index');

    Route::any('reg_fields.php', 'dashboard/RegFields/index');

    Route::any('role.php', 'dashboard/Role/index');

    Route::any('sale_general.php', 'dashboard/SaleGeneral/index');

    Route::any('sale_list.php', 'dashboard/SaleList/index');

    Route::any('sale_order.php', 'dashboard/SaleOrder/index');

    Route::any('search_log.php', 'dashboard/SearchLog/index');

    Route::any('searchengine_stats.php', 'dashboard/SearchengineStats/index');

    Route::any('send.php', 'dashboard/Send/index');

    Route::any('shipping_area.php', 'dashboard/ShippingArea/index');

    Route::any('shipping.php', 'dashboard/Shipping/index');

    Route::any('shop_config.php', 'dashboard/ShopConfig/index');

    Route::any('shophelp.php', 'dashboard/Shophelp/index');

    Route::any('shopinfo.php', 'dashboard/Shopinfo/index');

    Route::any('sitemap.php', 'dashboard/Sitemap/index');

    Route::any('sms.php', 'dashboard/Sms/index');

    Route::any('snatch.php', 'dashboard/Snatch/index');

    Route::any('sql.php', 'dashboard/Sql/index');

    Route::any('suppliers.php', 'dashboard/Suppliers/index');

    Route::any('suppliers_goods.php', 'dashboard/SuppliersGoods/index');

    Route::any('tag_manage.php', 'dashboard/TagManage/index');

    Route::any('template.php', 'dashboard/Template/index');

    Route::any('topic.php', 'dashboard/Topic/index');

    Route::any('user_account.php', 'dashboard/UserAccount/index');

    Route::any('user_account_manage.php', 'dashboard/UserAccountManage/index');

    Route::any('user_msg.php', 'dashboard/UserMsg/index');

    Route::any('user_rank.php', 'dashboard/UserRank/index');

    Route::any('users.php', 'dashboard/Users/index');

    Route::any('users_order.php', 'dashboard/UsersOrder/index');

    Route::any('view_sendlist.php', 'dashboard/ViewSendlist/index');

    Route::any('virtual_card.php', 'dashboard/VirtualCard/index');

    Route::any('visit_sold.php', 'dashboard/VisitSold/index');

    Route::any('vote.php', 'dashboard/Vote/index');

    Route::any('wholesale.php', 'dashboard/Wholesale/index');

});
