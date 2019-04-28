@extends('layouts.shop')
@section('content')
<form action="update" method="post" enctype="multipart/form-data">
  @csrf
  <table border="0" width="500">
    <input type="hidden" name="goods_id" value="{{$goodsInfo['goods_id']}}">
    <tr>
      <td>商品名称</td>
      <td>
        <input type="text" name="goods_name" value="{{$goodsInfo['goods_name']}}">
      </td>
    </tr>
    <tr>
      <td>本店价格</td>
      <td>
        <input type="text" name="goods_price" value="{{$goodsInfo['goods_price']}}">
      </td>
    </tr>
    <tr>
      <td>库存</td>
      <td>
        <input type="text" name="goods_num" value="{{$goodsInfo['goods_num']}}">
      </td>
    </tr>
    <tr>
      <td>商品原图片</td>
      <td>
        <img src="http://uploads.laravel.com/{{$goodsInfo['goods_img']}}" width="50">
      </td>
    </tr>
    <tr>
      <td>更换图片</td>
      <td>
        <input type="file" name="goods_img">
      </td>
    </tr>
    <tr>
      <td>商品描述</td>
      <td>
        <textarea name="goods_desc">{{$goodsInfo['goods_desc']}}</textarea>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <button id="edit">修改</button>
        <button type="reset">重置</button>
      </td>
    </tr>
  </table>
</form>
@endsection('content')