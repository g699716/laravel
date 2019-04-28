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