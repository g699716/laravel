
@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
        @foreach($cartInfo as $v)
       <tr goods_num="{{$v['goods_num']}}" goods_id="{{$v['goods_id']}}">
        <td width="4%"><input type="checkbox" class="box" name="1" /></td>
        <td class="dingimg" width="15%"><img src="http://uploads.laravel.com/goods/{{$v['goods_img']}}" /></td>
        <td width="50%">
         <h3>{{$v['goods_name']}}</h3>
         <time>下单时间：{{date('Y-m-d H:i:s',$v['goods_time'])}}</time>
        </td>
        <td align="right">
          <button class="less">-</button>
            <input type="text" style="width:50px;" value="{{$v['buy_num']}}" id="buy_num"  />
          <button class="add">+</button>
        </td>
       </tr>
       <tr>
        <th colspan="4"><font class="subTotal">¥{{$v['goods_price']*$v['buy_num']}}</font></th>
       </tr>
       @endforeach
       @csrf
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" /> 删除</a></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong id="countTotal" class="orange">¥0</strong></td>
       <td width="40%"><a href="javascript:;" id="order" class="jiesuan">去结算</a></td>
      </tr>
     </table>
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          var _token=$('input[name="_token"]').val();
          $(document).on('click','.add',function(){
            var _this=$(this);
            var buy_num=parseInt(_this.prev('input').val());
            var goods_num=parseInt(_this.parents('tr').attr('goods_num'));
            if(buy_num>=goods_num){
              _this.prop('disabled',true);
            }else{
              buy_num+=1;
              _this.prev('input').val(buy_num);
              $('.less').prop('disabled',false);
            }
            var goods_id=_this.parents('tr').attr('goods_id');
            changeBuyNum(goods_id,buy_num);
            getSubTotal(goods_id,_this);
            checkedBox(_this);
            countTotal();
          });

          $(document).on('click','.less',function(){
            var _this=$(this);
            var buy_num=parseInt(_this.next('input').val());
            var goods_num=parseInt(_this.parents('tr').attr('goods_num'));
            if(buy_num<=1){
              _this.prop('disabled',true);
            }else{
              buy_num-=1;
              _this.next('input').val(buy_num);
              $('.add').prop('disabled',false);
            }
            var goods_id=_this.parents('tr').attr('goods_id');
            changeBuyNum(goods_id,buy_num);
            getSubTotal(goods_id,_this);
            checkedBox(_this);
            countTotal();
          });

          $(document).on('blur','#buy_num',function(){
            var _this=$(this);
            var buy_num=parseInt(_this.val());
            var goods_num=parseInt(_this.parents('tr').attr('goods_num'));
            var reg=/^\d{1,}$/;
            if(_this.val()=='' || _this.val()<=1 || !reg.test(_this.val())){
              _this.val(1);
            }else if(buy_num>=goods_num){
              _this.val(goods_num);
            }else{
              _this.val(buy_num);
            }
            var goods_id=_this.parents('tr').attr('goods_id');
            changeBuyNum(goods_id,buy_num);
            getSubTotal(goods_id,_this);
            checkedBox(_this);
            countTotal();
          });

          $('#order').click(function(){
            var _box=$('.box');
            var goods_id='';
            _box.each(function(index){
              if($(this).prop('checked')==true){
                goods_id+=$(this).parents('tr').attr('goods_id')+',';
              }
            });
            goods_id=goods_id.substr(0,goods_id.length-1);
            if(goods_id==''){
              layer.msg('请至少选择一件商品',{icon:2,time:1000});
              return false;
            }
            $.post(
              '/order/isLogin',
              {_token:_token},
              function(msg){
                if(msg.code==1){
                  location.href='/order/orderList?goods_id='+goods_id;
                }else if(msg.code==2){
                  layer.msg(msg.font,{icon:msg.skin,time:1000},function(){
                    location.href='/login';
                  });
                }
              },
              'json'
            )
          });

          function changeBuyNum(goods_id,buy_num){
            $.ajax({
              url:'changeBuyNum',
              method:'post',
              async:false,
              data:{goods_id:goods_id,buy_num:buy_num,_token:_token},
              success:function(msg){
                if(msg.code==2){
                  layer.msg(msg.font,{icon:msg.skin,time:1000});
                }
              },
              dataType:'json'
            });
          }

          function getSubTotal(goods_id,_this){
            $.post(
              'getSubTotal',
              {goods_id:goods_id,_token:_token},
              function(msg){
                _this.parents('tr').next('tr').find('font[class="subTotal"]').text(msg);
              },
              'json'
            )
          }

          function countTotal(){
            var _box=$('.box');
            var goods_id='';
            _box.each(function(index){
              if($(this).prop('checked')==true){
                goods_id+=$(this).parents('tr').attr('goods_id')+',';
              }
            });
            goods_id=goods_id.substr(0,goods_id.length-1);
            $.post(
              'countTotal',
              {goods_id:goods_id,_token:_token},
              function(msg){
                $('#countTotal').text(msg);
              },
              'json'
            )
          }

          function checkedBox(_this){
            _this.parents('tr').find('input[class="box"]').prop('checked',true);
          }
        });
       });
     </script>
@endsection