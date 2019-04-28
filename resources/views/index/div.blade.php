<div class="index-pro1"  cate_id="{{$floorInfo['topCateInfo']['cate_id']}}">
      @foreach($floorInfo['goodsInfo'] as $v)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="proinfo.html"><img src="http://uploads.laravel.com/goods/{{$v['goods_img']}}" /></a></dt>
        <dd class="ip-text"><a href="proinfo.html">{{$v['goods_name']}}</a><span>已售：{{$v['goods_num']}}</span></dd>
        <dd class="ip-price"><strong>¥{{$v['goods_price']}}</strong> <span>¥{{$v['goods_mprice']}}</span></dd>
       </dl>
      </div>
      @endforeach
  <div></div>
    @csrf
      <center id="load" style="margin-top:30px;color:red;cursor:pointer;"><span>获取更多</span></center>
      <div class="clearfix"></div>
     </div><!--index-pro1/-->