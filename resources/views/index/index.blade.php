
@extends('layouts.shop')
@section('content')
<div class="head-top">
      <img src="{{asset('images/head.jpg')}}" />
      <dl>
       <dt><a href="user.html"><img src="{{asset('images/touxiang.jpg')}}" /></a></dt>
       <dd>
        <span class="username">三级分销终身荣誉会员</span>
        <ul>
         <li><a href="prolist.html"><strong>34</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--head-top/-->
     <form action="#" method="get" class="search">
      <input type="text" class="seaText fl" />
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
     <ul class="reg-login-click">
      <li><a href="login">登录</a></li>
      <li><a href="register" class="rlbg">注册</a></li>
      <div class="clearfix"></div>
     </ul><!--reg-login-click/-->
     <div id="sliderA" class="slider">
      <img src="images/image1.jpg" />
      <img src="images/image2.jpg" />
      <img src="images/image3.jpg" />
      <img src="images/image4.jpg" />
      <img src="images/image5.jpg" />
     </div><!--sliderA/-->
     <ul class="pronav">
      @foreach($cateInfo as $v)
      <li><a href="prolist.html">{{$v['cate_name']}}</a></li>
      @endforeach
      <div class="clearfix"></div>
     </ul><!--pronav/-->
     <div >
     <div class="index-pro1" cate_id="{{$floorInfo['topCateInfo']['cate_id']}}">
      @foreach($floorInfo['goodsInfo'] as $v)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="proinfo.html"><img src="http://uploads.laravel.com/goods/{{$v['goods_img']}}" /></a></dt>
        <dd class="ip-text"><a href="proinfo.html">{{$v['goods_name']}}</a><span>已售：{{$v['goods_num']}}</span></dd>
        <dd class="ip-price"><strong>¥{{$v['goods_price']}}</strong> <span>¥{{$v['goods_mprice']}}</span></dd>
       </dl>
      </div>
      @endforeach
      </div>
    @csrf
      <center id="load" style="margin-top:30px;color:red;cursor:pointer;"><span>获取更多</span></center>
      <div class="clearfix"></div>
     </div><!--index-pro1/-->
     <div class="prolist">
      <dl>
       <dt><a href="proinfo.html"><img src="images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="proinfo.html">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <dl>
       <dt><a href="proinfo.html"><img src="images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="proinfo.html">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <dl>
       <dt><a href="proinfo.html"><img src="images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3>
          <a href="proinfo.html">四叶草</a>
        </h3>
        <div class="prolist-price">
          <strong>¥299</strong> 
          <span>¥599</span>
        </div>
        <div class="prolist-yishou">
          <span>5.0折</span> 
          <em>已售：35</em>
        </div>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div>
     <!--prolist/-->
     <div class="joins"><a href="fenxiao.html"><img src="images/jrwm.jpg" /></a></div>
     <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          $('#load').click(function(){
            var _this=$(this);
            // var cate_id=_this.prev('div').attr('cate_id');
            var cate_id=_this.prevAll('div').attr('cate_id');
            console.log(cate_id);
            var _token=$('input[name="_token"]').val();
            $.post(
              'floorInfo',
              {cate_id:cate_id,_token:_token},
              function(msg){
                _this.before(msg);
              },
              
            )
          });
        });
       });
     </script>
     @extends('public.footer')
     @endsection
