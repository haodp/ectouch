<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', 'IndexController@index');

Route::any('activity.php', 'ActivityController@index');

Route::any('affiche.php', 'AfficheController@index');

Route::any('affiliate.php', 'AffiliateController@index');

Route::any('api.php', 'ApiController@index');

Route::any('article-{id}{s?}.html', 'ArticleController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('article.php', 'ArticleController@index');

Route::any('article_cat-{id}-{page}-{sort}-{order}{s?}.html', 'ArticleCatController@index')
    ->where(['id' => '[0-9]+', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+', 's' => '.*']);

Route::any('article_cat-{id}-{page}-{keywords}{s?}.html', 'ArticleCatController@index')
    ->where(['id' => '[0-9]+', 'page' => '[0-9]+', 'keywords' => '.+', 's' => '.*']);

Route::any('article_cat-{id}-{page}{s?}.html', 'ArticleCatController@index')
    ->where(['id' => '[0-9]+', 'page' => '[0-9]+', 's' => '.*']);

Route::any('article_cat-{id}{s?}.html', 'ArticleCatController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('article_cat.php', 'ArticleCatController@index');

Route::any('auction-{id}.html', 'auctionController@index')
    // ->bind('act', 'view')
    ->where(['id' => '[0-9]+']);

Route::any('auction.php', 'AuctionController@index');

Route::any('brand-{id}-c{cat}-{page}-{sort}-{order}.html', 'BrandController@index')
    ->where(['id' => '[0-9]+', 'cat' => '[0-9]+', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+']);

Route::any('brand-{id}-c{cat}-{page}{s?}.html', 'BrandController@index')
    ->where(['id' => '[0-9]+', 'cat' => '[0-9]+', 'page' => '[0-9]+', 's' => '.*']);

Route::any('brand-{id}-c{cat}{s?}.html', 'BrandController@index')
    ->where(['id' => '[0-9]+', 'cat' => '[0-9]+', 's' => '.*']);

Route::any('brand-{id}{s?}.html', 'BrandController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('brand.php', 'BrandController@index');

Route::any('captcha.php', 'CaptchaController@index');

Route::any('catalog.php', 'CatalogController@index');

Route::any('category-{id}-b{brand}-min{price_min}-max{price_max}-attr{filter_attr}-{page}-{sort}-{order}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 'brand' => '[0-9]+', 'price_min' => '[0-9]+', 'price_max' => '[0-9]+', 'filter_attr' => '[^-]*', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+', 's' => '.*']);

Route::any('category-{id}-b{brand}-min{price_min}-max{price_max}-attr{filter_attr}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 'brand' => '[0-9]+', 'price_min' => '[0-9]+', 'price_max' => '[0-9]+', 'filter_attr' => '[^-]*', 's' => '.*']);

Route::any('category-{id}-b{brand}-{page}-{sort}-{order}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 'brand' => '[0-9]+', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+', 's' => '.*']);

Route::any('category-{id}-b{brand}-{page}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 'brand' => '[0-9]+', 'page' => '[0-9]+', 's' => '.*']);

Route::any('category-{id}-b{brand}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 'brand' => '[0-9]+', 's' => '.*']);

Route::any('category-{id}{s?}.html', 'CategoryController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('category.php', 'CategoryController@index');

Route::any('certi.php', 'CertiController@index');

Route::any('comment.php', 'CommentController@index');

Route::any('compare.php', 'CompareController@index');

Route::any('cycle_image.php', 'CycleImageController@index');

Route::any('exchange-id{id}{s?}.html', 'ExchangeController@index')
    // ->bind('act', 'view')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('exchange-{cat_id}-min{integral_min}-max{integral_max}-{page}-{sort}-{order}{s?}.html', 'ExchangeController@index')
    ->where(['cat_id' => '[0-9]+', 'integral_min' => '[0-9]+', 'integral_max' => '[0-9]+', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+', 's' => '.*']);

Route::any('exchange-{cat_id}-{page}-{sort}-{order}{s?}.html', 'ExchangeController@index')
    ->where(['cat_id' => '[0-9]+', 'page' => '[0-9]+', 'sort' => '.+', 'order' => '[a-zA-Z]+', 's' => '.*']);

Route::any('exchange-{cat_id}-{page}{s?}.html', 'ExchangeController@index')
    ->where(['id' => '[0-9]+', 'page' => '[0-9]+', 's' => '.*']);

Route::any('exchange-{cat_id}{s?}.html', 'ExchangeController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('exchange.php', 'ExchangeController@index');

Route::any('feed-c{cat}.xml', 'FeedController@index')
    ->where(['cat' => '[0-9]+']);

Route::any('feed-b{brand}.xml', 'FeedController@index')
    ->where(['brand' => '[0-9]+']);

Route::any('feed-type{type}.xml', 'FeedController@index')
    ->where(['type' => '[^-]+']);

Route::any('feed.{ext}', 'FeedController@index')
    ->where(['ext' => 'xml|php']);

Route::any('flow.php', 'FlowController@index');

Route::any('gallery.php', 'GalleryController@index');

Route::any('goods-{id}{s?}.html', 'GoodsController@index')
    ->where(['id' => '[0-9]+', 's' => '.*']);

Route::any('goods.php', 'GoodsController@index');

Route::any('goods_script.php', 'GoodsScriptController@index');

Route::any('group_buy-{id}.html', 'GroupBuyController@index')
    // ->bind('act', 'view')
    ->where(['id' => '[0-9]+']);

Route::any('group_buy.php', 'GroupBuyController@index');

Route::any('message.php', 'MessageController@index');

Route::any('myship.php', 'MyshipController@index');

Route::any('package.php', 'PackageController@index');

Route::any('pick_out.php', 'PickOutController@index');

Route::any('pm.php', 'PmController@index');

Route::any('quotation.php', 'QuotationController@index');

Route::any('receive.php', 'ReceiveController@index');

Route::any('region.php', 'RegionController@index');

Route::any('respond.php', 'RespondController@index');

Route::any('tag-{keywords}.html', 'SearchController@index')
    ->where(['keywords' => '.*']);

Route::any('search.php', 'SearchController@index');

Route::any('sitemaps.php', 'SitemapsController@index');

Route::any('snatch-{id}.html', 'SnatchController@index')
    ->where(['id' => '[0-9]+']);

Route::any('snatch.php', 'SnatchController@index');

Route::any('tag_cloud.php', 'TagCloudController@index');

Route::any('topic.php', 'TopicController@index');

Route::any('user.php', 'UserController@index');

Route::any('vote.php', 'VoteController@index');

Route::any('wholesale.php', 'WholesaleController@index');