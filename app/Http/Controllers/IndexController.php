<?php  
namespace app\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\model\GoodsModel;
use App\model\CategoryModel;
use Illuminate\Support\Facades\DB;

class IndexController extends CommonController
{
	public function add(){
		dump($_POST['name']);
	}

	public function index()
	{
		$cate_id=1;
		$category_model=new CategoryModel;
		$cateInfo=$category_model->where(['cate_show'=>1,'pid'=>0])->get()->toArray();
		$floorInfo=$this->getFloorInfo($cate_id);
		return view('index/index',compact('cateInfo','floorInfo'));
	}

	public function getFloorInfo($cate_id)
	{
		$info=[];
		$category_model=new CategoryModel;
		$goods_model=new GoodsModel;
		//获取顶级分类信息
		$top_where=[
			['cate_show','=',1],
			['cate_id','=',$cate_id]
		];
		$info['topCateInfo']=$category_model->where($top_where)->first()->toArray();
		//获取给定分类下的子类信息
		$son_where=[
			['cate_show','=',1],
			['pid','=',$cate_id]
		];
		$info['sonCateInfo']=$category_model->where($son_where)->get()->toArray();
		//获取该分类下商品数据
		$cateInfo=$category_model->where('cate_show',1)->get()->toArray();
		$c_id=$this->getCateId($cateInfo,$cate_id);
		// $c_id=implode(',',$c_id);
		// $goods_where=[
		// 	['goods_up','=',1],
		// 	['cate_id','in',[$c_id]]
		// ];
		// $info['goodsInfo']=Db::select('select * from shop_goods where goods_up=? and cate_id in (?)',[1,$c_id]);
		// $info['goodsInfo']=$goods_model->where($goods_where)->orderBy('goods_id','asc')->get()->toArray();
		$info['goodsInfo']=$goods_model->whereIn('cate_id',$c_id)->orderBy('goods_id','asc')->get()->toArray();
		return $info;
	}

	public function floorInfo(Request $request)
	{
		$cate_id=$request->input('cate_id');
		$category_model=new CategoryModel;
		$where=[
			['pid','=',0],
			['cate_show','=',1],
			['cate_id','>',$cate_id]
		];
		// $c_id=Db::select('select * from shop_category where pid=? and cate_show=? and cate_id>?',[0,1,$cate_id]);
		$c_id=$category_model->where($where)->select('cate_id')->get()->toArray();
		foreach ($c_id as $v) {
			$floorInfo=$this->getFloorInfo($v['cate_id']);
		}
		echo view('index/div',['floorInfo'=>$floorInfo]);
	}
}

?>