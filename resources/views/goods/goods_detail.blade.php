@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      <img src="{{asset('images/image1.jpg')}}" />
      <img src="{{asset('images/image2.jpg')}}" />
      <img src="{{asset('images/image3.jpg')}}" />
      <img src="{{asset('images/image4.jpg')}}" />
      <img src="{{asset('images/image5.jpg')}}" />
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$goodsInfo['goods_price']}}</strong></th>
       <td>
        <input type="text" id="buy_num" class="spinnerExample" />
       </td>
       <input type="hidden" name="goods_num" value="{{$goodsInfo['goods_num']}}">
       <input type="hidden" name="goods_id" value="{{$goodsInfo['goods_id']}}">
       @csrf
      </tr>
      <tr>
       <td>
        <strong>{{$goodsInfo['goods_name']}}</strong>
        <p class="hui"></p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="http://uploads.laravel.com/goods/{{$goodsInfo['goods_img']}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><a href="javascript:;" id="cart">加入购物车</a></td>
      </tr>
     </table>
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          var _token=$('input[name="_token"]').val();
          var goods_num=$('input[name="goods_num"]');
          var buy_num=$('#buy_num');
          $('.increase').click(function(){
            var _this=$(this);
            if(parseInt(buy_num.val())>=parseInt(goods_num.val())){
              _this.prop('disabled',true);
            }else{
              _this.prevAll('button').prop('disabled',false);
            }
          });

          $('.decrease').click(function(){
            var _this=$(this);
            if(parseInt(buy_num.val())<=1){
              _this.prop('disabled',true);
            }else{
              _this.nextAll('button').prop('disabled',false);
            }
          });

          buy_num.blur(function(){
            var _this=$(this);
            var reg=/^\d{1,}$/;
            if(_this.val()=='' || parseInt(_this.val())<=1 || !reg.test(_this.val())){
              _this.val(1);
            }else if(parseInt(_this.val())>=parseInt(goods_num.val())){
              _this.val(goods_num.val());
            }else{
              _this.val(parseInt(_this.val()));
            }
          });

          $('#cart').click(function(){
            var goods_id=$('input[name="goods_id"]').val();
            if(goods_id==''){
              layer.msg('请选择一件商品',{icon:5,time:1000});
              return false;
            }
            if(buy_num.val()=='' || parseInt(buy_num.val())<1){
              layer.msg('请输入购买数量',{icon:5,time:1000});
              return false;
            }

            location.href='/cart/add?goods_id='+goods_id+'&buy_num='+buy_num.val();

            // $.post(
            //   '/cart/add',
            //   {goods_id:goods_id,buy_num:buy_num,_token:_token},
            //   function(msg){

            //   },
            //   'json'
            // )
          });
        });
       });
     </script>
     @endsection