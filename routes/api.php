<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group('api', function () {

    Route::get('article.{id}', 'api/Article/show')
        ->pattern(['id' => '[0-9]+']);

    Route::get('notice.{id:[0-9]+}', 'api/Notice/show');

    Route::post('order.notify.{code}', 'api/Order/notify');

    Route::get('product.intro.{id:[0-9]+}', 'api/Goods/intro');

    Route::get('product.share.{id:[0-9]+}', 'api/Goods/share');

    Route::get('ecapi.auth.web', 'api/User/webOauth');

    Route::get('ecapi.auth.web.callback/{vendor:[0-9]+}', 'api/User/webCallback');

    // Guest
    Route::post('ecapi.access.dns', 'api/Access/dns');

    Route::post('ecapi.access.batch', 'api/Access/batch');

    Route::post('ecapi.category.list', 'api/Goods/category');

    Route::post('ecapi.product.list', 'api/Goods/index');

    Route::post('ecapi.home.product.list', 'api/Goods/home');

    Route::post('ecapi.search.product.list', 'api/Goods/search');

    Route::post('ecapi.review.product.list', 'api/Goods/review');

    Route::post('ecapi.review.product.subtotal', 'api/Goods/subtotal');

    Route::post('ecapi.recommend.product.list', 'api/Goods/recommendList');

    Route::post('ecapi.product.accessory.list', 'api/Goods/accessoryList');

    Route::post('ecapi.product.get', 'api/Goods/info');

    Route::post('ecapi.auth.signin', 'api/User/signin');

    Route::post('ecapi.auth.social', 'api/User/auth');

    Route::post('ecapi.auth.default.signup', 'api/User/signupByEmail');

    Route::post('ecapi.auth.mobile.signup', 'api/User/signupByMobile');

    Route::post('ecapi.user.profile.fields', 'api/User/fields');

    Route::post('ecapi.auth.mobile.verify', 'api/User/verifyMobile');

    Route::post('ecapi.auth.mobile.send', 'api/User/sendCode');

    Route::post('ecapi.auth.mobile.reset', 'api/User/resetPasswordByMobile');

    Route::post('ecapi.auth.default.reset', 'api/User/resetPasswordByEmail');

    Route::post('ecapi.cardpage.get', 'api/CardPage/view');

    Route::post('ecapi.cardpage.preview', 'api/CardPage/preview');

    Route::post('ecapi.config.get', 'api/Config/index');

    Route::post('ecapi.article.list', 'api/Article/index');

    Route::post('ecapi.brand.list', 'api/Brand/index');

    Route::post('ecapi.search.keyword.list', 'api/Search/index');

    Route::post('ecapi.region.list', 'api/Region/index');

    Route::post('ecapi.invoice.type.list', 'api/Invoice/type');

    Route::post('ecapi.invoice.content.list', 'api/Invoice/content');

    Route::post('ecapi.invoice.status.get', 'api/Invoice/status');

    Route::post('ecapi.notice.list', 'api/Notice/index');

    Route::post('ecapi.banner.list', 'api/Banner/index');

    Route::post('ecapi.version.check', 'api/Version/check');

    Route::post('ecapi.recommend.brand.list', 'api/Brand/recommend');

    Route::post('ecapi.message.system.list', 'api/Message/system');

    Route::post('ecapi.message.count', 'api/Message/unread');

    Route::post('ecapi.site.get', 'api/Site/index');

    Route::post('ecapi.splash.list', 'api/Splash/index');

    Route::post('ecapi.splash.preview', 'api/Splash/view');

    Route::post('ecapi.theme.list', 'api/Theme/index');

    Route::post('ecapi.theme.preview', 'api/Theme/view');

    Route::post('ecapi.search.category.list', 'api/Goods/categorySearch');

    Route::post('ecapi.order.reason.list', 'api/Order/reasonList');

    Route::post('ecapi.search.shop.list', 'api/Shop/search');

    Route::post('ecapi.recommend.shop.list', 'api/Shop/recommand');

    Route::post('ecapi.shop.list', 'api/Shop/index');

    Route::post('ecapi.shop.get', 'api/Shop/info');

    Route::post('ecapi.areacode.list', 'api/AreaCode/index');

    // Authorization
    Route::post('ecapi.user.profile.get', 'api/User/profile');

    Route::post('ecapi.user.profile.update', 'api/User/updateProfile');

    Route::post('ecapi.user.password.update', 'api/User/updatePassword');

    Route::post('ecapi.order.list', 'api/Order/index');

    Route::post('ecapi.order.get', 'api/Order/view');

    Route::post('ecapi.order.confirm', 'api/Order/confirm');

    Route::post('ecapi.order.cancel', 'api/Order/cancel');

    Route::post('ecapi.order.price', 'api/Order/price');

    Route::post('ecapi.product.like', 'api/Goods/setLike');

    Route::post('ecapi.product.unlike', 'api/Goods/setUnlike');

    Route::post('ecapi.product.liked.list', 'api/Goods/likedList');

    Route::post('ecapi.order.review', 'api/Order/review');

    Route::post('ecapi.order.subtotal', 'api/Order/subtotal');

    Route::post('ecapi.payment.types.list', 'api/Order/paymentList');

    Route::post('ecapi.payment.pay', 'api/Order/pay');

    Route::post('ecapi.shipping.vendor.list', 'api/Shipping/index');

    Route::post('ecapi.shipping.status.get', 'api/Shipping/info');

    Route::post('ecapi.consignee.list', 'api/Consignee/index');

    Route::post('ecapi.consignee.update', 'api/Consignee/modify');

    Route::post('ecapi.consignee.add', 'api/Consignee/add');

    Route::post('ecapi.consignee.delete', 'api/Consignee/remove');

    Route::post('ecapi.consignee.setDefault', 'api/Consignee/setDefault');

    Route::post('ecapi.score.get', 'api/Score/view');

    Route::post('ecapi.score.history.list', 'api/Score/history');

    Route::post('ecapi.cashgift.list', 'api/CashGift/index');

    Route::post('ecapi.cashgift.available', 'api/CashGift/available');

    Route::post('ecapi.push.update', 'api/Message/updateDeviceId');

    Route::post('ecapi.cart.add', 'api/Cart/add');

    Route::post('ecapi.cart.clear', 'api/Cart/clear');

    Route::post('ecapi.cart.delete', 'api/Cart/delete');

    Route::post('ecapi.cart.get', 'api/Cart/index');

    Route::post('ecapi.cart.update', 'api/Cart/update');

    Route::post('ecapi.cart.checkout', 'api/Cart/checkout');

    Route::post('ecapi.cart.promos', 'api/Cart/promos');

    Route::post('ecapi.product.purchase', 'api/Goods/purchase');

    Route::post('ecapi.product.validate', 'api/Goods/checkProduct');

    Route::post('ecapi.message.order.list', 'api/Message/order');

    Route::post('ecapi.shop.watch', 'api/Shop/watch');

    Route::post('ecapi.shop.unwatch', 'api/Shop/unwatch');

    Route::post('ecapi.shop.watching.list', 'api/Shop/watchingList');

    Route::post('ecapi.coupon.list', 'api/Coupon/index');

    Route::post('ecapi.coupon.available', 'api/Coupon/available');

    Route::post('ecapi.recommend.bonus.list', 'api/Affiliate/index');

    Route::post('ecapi.recommend.bonus.info', 'api/Affiliate/info');

    Route::post('ecapi.withdraw.submit', 'api/Account/submit');

    Route::post('ecapi.withdraw.cancel', 'api/Account/cancel');

    Route::post('ecapi.withdraw.list', 'api/Account/index');

    Route::post('ecapi.withdraw.info', 'api/Account/getDetail');

    Route::post('ecapi.balance.get', 'api/Account/surplus');

    Route::post('ecapi.balance.list', 'api/Account/accountDetail');

});
