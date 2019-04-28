@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="" method="get" >
        <input type="text" name="goods_name" placeholder="请输入商品名称" value="{{$goods_name}}" />
        <input type="text" name="goods_desc" placeholder="请输入商品描述" value="{{$goods_desc}}">
        <button>搜索</button>
       </form>
      </div>
     </header>
     <ul class="pro-select">
      <li class="pro-selCur"><a href="javascript:;">新品</a></li>
      <li><a href="javascript:;">销量</a></li>
      <li><a href="javascript:;">价格</a></li>
     </ul><!--pro-select/-->
     <div class="prolist">
      @foreach($goodsInfo as $v)
      <dl>
       <dt><a href="goodDetail?goods_id={{$v['goods_id']}}"><img src="http://uploads.laravel.com/{{$v['goods_img']}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="goodDetail?goods_id={{$v['goods_id']}}">{{$v['goods_name']}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v['goods_price']}}</strong> <span>¥{{$v['goods_mprice']}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：{{$v['goods_num']}}</em></div>
        <div>{{$v['goods_desc']}}</div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach
      <dl>
        <dd>{{$goodsInfo->appends($query)->links()}}</dd>
      </dl>
    </div><!--prolist/-->
@endsection
@extends('public.footer')