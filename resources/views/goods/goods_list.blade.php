@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="prosearch"><input type="text" /></form>
      </div>
     </header>
     <ul class="pro-select">
      <li class="pro-selCur">
        <a class="default" is_type='1' href="javascript:;">
          <span>新品</span>
          <span>↑</span>
        </a>
      </li>
      <li>
        <a class="default" is_type='2' href="javascript:;">
          <span>销量</span>
          <span>↑</span>
        </a>
      </li>
      <li>
        <a class="default" is_type='3' href="javascript:;">
          <span>价格</span>
          <span>↑</span>
        </a>
      </li>
     </ul><!--pro-select/-->
     <div class="prolist" id="goodsInfo">
      @foreach($goodsInfo as $v)
      <dl>
       <dt><a href="goodsDetail?goods_id={{$v['goods_id']}}"><img src="http://uploads.laravel.com/goods/{{$v['goods_img']}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="goodsDetail?goods_id={{$v['goods_id']}}">{{$v['goods_name']}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v['goods_price']}}</strong> <span>¥{{$v['goods_mprice']}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：{{$v['goods_num']}}</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach
      @csrf
      <dl>
        <dd>{{$goodsInfo->appends($query)->links()}}</dd>
      </dl>
    </div>
    
    <script type="text/javascript">
      $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          $(document).on('click','.default',function(){
            var _this=$(this);
            var is_type=_this.attr('is_type');
            var flag=_this.children('span').last().text();
            _this.parent('li').addClass('pro-selCur');
            _this.parent('li').siblings().removeClass('pro-selCur');
            if(is_type==1){
              var order_field='goods_hot';
              if(flag=='↑'){
                var order_field='asc';
                var flag=_this.children('span').last().text('↓');
              }else if(flag=='↓'){
                var order_field='desc';
                var flag=_this.children('span').last().text('↑');
              }
            }else if(is_type==2){
              if(flag=='↑'){
                var order_field='asc';
                var flag=_this.children('span').last().text('↓');
              }else if(flag=='↓'){
                var order_field='desc';
                var flag=_this.children('span').last().text('↑');
              }
            }else if(is_type==3){
              if(flag=='↑'){
                var order_field='asc';
                var flag=_this.children('span').last().text('↓');
              }else if(flag=='↓'){
                var order_field='desc';
                var flag=_this.children('span').last().text('↑');
              }
            }
            getGoodsInfo(1);
          });

          $(document).on('click','.page-link',function(){
            var _this=$(this);
            var p=_this.text();
            getGoodsInfo(p);
          });

          function getGoodsInfo(p){
            var is_type=$('li.pro-selCur').children('a').attr('is_type');
            var flag=$('li.pro-selCur').find('span').last().text();
            var _token=$('input[name="_token"]').val();
            console.log(flag);
            if(is_type==1){
              var order_field='goods_hot';
              if(flag=='↑'){
                var order_type='asc';
              }else if(flag=='↓'){
                var order_type='desc';
              }
            }else if(is_type==2){
              var order_field='goods_num';
              if(flag=='↑'){
                var order_type='asc';
              }else if(flag=='↓'){
                var order_type='desc';
              }
            }else if(is_type==3){
              var order_field='goods_price';
              if(flag=='↑'){
                var order_type='asc';
              }else if(flag=='↓'){
                var order_type='desc';
              }
            }
            $.post(
              'getGoodsInfo',
              {order_field:order_field,order_type:order_type,p:p,_token:_token},
              function(msg){
                $('#goodsInfo').html(msg);
              },

            )
          }
        });
      });
    </script>
    @extends('public.footer')
@endsection