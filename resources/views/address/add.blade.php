@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('images/head.jpg')}}" />
     </div><!--head-top/-->
     <form action="" method="" class="reg-login">
      @csrf
      <div class="lrBox">
       <div class="lrList"><input type="text" id="address_name" placeholder="收货人" /></div>   
       <div class="lrList">
          <select name="province" class="area">
              <option value="0" selected="selected">请选择...</option>
              @foreach($areaInfo as $v)
              <option value="{{$v['id']}}">{{$v['name']}}</option>
              @endforeach
          </select>
          <select class="area" name="city">
              <option value="0" selected="selected">请选择...</option>
          </select>
          <select class="area" name="area">
              <option value="0" selected="selected">请选择...</option>
          </select>
       </div>
       <div class="lrList"><input type="text" id="address_detail" placeholder="详细地址" /></div>
       <div class="lrList"><input type="text" id="address_tel" placeholder="手机" /></div>
       <div><input type="checkbox" id="box" name='is_default'  />设为默认地址</div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="sub" value="保存" />
      </div>
     </form><!--reg-login/-->
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          var _token=$('input[name="_token"]').val();
          $(document).on('change','.area',function(){
            var _this=$(this);
            var id=_this.val();
            var _option="<option value='0' selected='selected'>请选择...</option>";
            _this.nextAll('select').html(_option);
            $.post(
              'getArea',
              {id:id,_token:_token},
              function(msg){
                if(msg.code!=2){
                  for(i in msg){
                    _option+="<option value='"+msg[i]['id']+"'>"+msg[i]['name']+"</option>";
                  }
                  _this.next('select').html(_option);
                }
              },
              'json'
            )
          });

          $('#sub').click(function(){
            var _obj={};
            _obj.address_name=$('#address_name').val();
            _obj.address_tel=$('#address_tel').val();
            _obj.address_detail=$('#address_detail').val();
            _obj.province=$('select[name="province"]').val();
            _obj.city=$('select[name="city"]').val();
            _obj.area=$('select[name="area"]').val();

            if($('#box').prop('checked')==true){
              _obj.is_default=1;
            }else{
              _obj.is_default=2;
            }

            if(_obj.province==0 || _obj.city==0 || _obj.area==0){
              layer.msg('请选择配送地区',{icon:5,time:1000});
              return false;
            }

            var reg1=/^[\u4e00-\u9fa5]{2,12}$/;
            if(_obj.address_name==''){
              layer.msg('收货人姓名必填',{icon:5,time:1000});
              return false;
            }else if(!reg1.test(_obj.address_name)){
              layer.msg('收货人姓名由2~12位汉字组成',{icon:5,time:1000});
              return false;
            }

            var reg2=/^[1][3,5,8]\d{9}$/;
            if(_obj.address_tel==''){
              layer.msg('手机号必填',{icon:5,time:1000});
              return false;
            }else if(!reg2.test(_obj.address_tel)){
              layer.msg('手机号由13 15 18开头的11位纯数字组成',{icon:5,time:1000});
              return false;
            }

            if(_obj.address_detail==''){
              layer.msg('请填写详细地址',{icon:5,time:1000});
              return false;
            }

            $.post(
              'addressDo',
              {_obj,_token},
              function(msg){
                console.log(msg);
              },
              'json'
            )
            return false;
          });
        });
       });
     </script>
@endsection('content')
