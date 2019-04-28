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

// Route::get('/', function () {
//     return view('welcome');
// });
//前台首页
Route::get('/','IndexController@index');
//一楼数据
Route::post('floorInfo','IndexController@floorInfo');

// Route::get('/index',function(){
// 	return 123;
// });
// 

Route::get('/alipay',function(){
	$order_no=date('Ymd').rand(1000,9999);
	echo "<a href='pagepay/".$order_no."'>点击支付</a>";
});

Route::get('pagepay/{id}','AlipayController@pagepay');

Route::get('/returnPay','AlipayController@returnPay');

Route::post('/notifyUrl','AlipayController@notifyUrl');

Route::get('/index',function(){
	return view('test');
});
//登录
Route::get('/login',function(){
	return view('login/login');
});
//注册
Route::get('/Register',function(){
	return view('login/register');
});
Route::post('doregister','LoginController@doregister');
//检测用户名唯一性
Route::post('checkUnique','LoginController@checkUnique');
//发送邮件
Route::post('sendEmail','LoginController@sendEmail');
Route::post('sendMail','LoginController@sendCode');
//发送短信
Route::post('sendNote','LoginController@sendNote');
//登录处理
Route::post('dologin','LoginController@doLogin');

// Route::match(['get','post'],'/form',function(){
// 	$form='<form action="add/1" method="post">'.csrf_field().'<input name="name" type="text"><button>提交</button></form>';
// 	return $form;
// });

// Route::match(['get','post'],'/form',function(){
// 	$form='<form action="add/1" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input name="name" type="text"><button>提交</button></form>';
// 	return $form;
// });

// Route::match(['get','post'],'/form',function(){
// 	$form='<form action="add" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input name="name" type="text"><button>提交</button></form>';
// 	return $form;
// });

// Route::match(['get','post'],'/add',function(){
// 	return $_POST['name'];
// });

// Route::match(['get','post'],'/add/{id}',function($id){
// 	return 'id='.$id;
// })->where('id','\d+');

// Route::match(['get','post'],'/add/{id?}',function($id=null){
// 	return 'id='.$id;
// })->where('id','\d+');
// @csrf
// Route::match(['get','post'],'/add','IndexController@add');

// Route::prefix('users')->group(function(){
// 	Route::get('add',function(){
// 		return view('users/add');
// 	});
// 	Route::get('edit',function(){
// 		return view('users/edit');
// 	});
// 	Route::get('lists',function(){
// 		return view('users/lists');
// 	});
// 	Route::get('del',function(){
// 		return view('users/del');
// 	});
// });
Route::get('users/add','UsersController@add');
Route::prefix('Users')->middleware('checkLogin')->group(function(){
	Route::post('addHandle','UsersController@addHandle');
	Route::get('show','UsersController@show');
	Route::get('edit','UsersController@edit');
	Route::post('update','UsersController@update');
	Route::post('ajax','UsersController@ajax');
	Route::get('destroy','UsersController@destroy');
	// Route::get('search','usersController@search');
});

// Route::get('login',function(){
// 	return view('users/login');
// });
Route::prefix('Goods')->group(function(){
	Route::get('goods_list','GoodsController@goodsList');
	Route::post('getGoodsInfo','GoodsController@getGoodsInfo');
	Route::get('goodsDetail','GoodsController@goodsDetail');
});

Route::prefix('Good')->group(function(){
	Route::get('goodList','GoodController@goodList');
	Route::get('goodDetail','GoodController@goodDetail');
	Route::get('edit','GoodController@edit');
	Route::post('update','GoodController@update');
	Route::post('del','GoodController@del');
});

Route::prefix('Cart')->group(function(){
	Route::get('cart_list','CartController@cartList');
	Route::get('add','CartController@add');
	Route::post('countTotal','CartController@countTotal');
	Route::post('changeBuyNum','CartController@changeBuyNum');
	Route::post('getSubTotal','CartController@getSubTotal');
});

Route::prefix('Order')->group(function(){
	Route::post('isLogin','OrderController@isLogin');
	Route::get('orderList','OrderController@orderList');
});

Route::prefix('Address')->group(function(){
	Route::get('add','AddressController@add');
	Route::post('getArea','AddressController@getArea');
	Route::post('addressDo','AddressController@addressDo');
});

Route::prefix('User')->group(function(){
	Route::get('index','UserController@index');
});

Route::post('doLogin','UsersController@doLogin');

Route::prefix('Friend')->middleware('checkLogin')->group(function(){
	Route::get('add','FriendController@add');
	Route::post('addHandle','FriendController@addHandle');
	Route::post('checkName','FriendController@checkName');
	Route::get('index','FriendController@index');
	Route::get('edit','FriendController@edit');
	Route::post('update','FriendController@update');
	Route::get('destroy','FriendController@destroy');
});
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

