<?php
namespace Home\Controller;
use Home\Logic\ArticleLogic;

class ArticleController extends BaseController {
    
    public function index(){
        $article_cat = M('article_cat')->where("parent_id  = 36")->select();
        $list = M('lunbo')->where(array('name'=>3))->limit(3)->select();
        $this->assign('list',$list);
        $this->assign('article_cat',$article_cat);
        //$article = D('article')->where("cate_id=$article_id")->find();
    	//$this->assign('article',$article);
        $this->display();
    }
 
    /**
     * 文章内列表页
     */
    public function articleList(){
        $article_cat = M('ArticleCat')->where("parent_id  = 0")->select();
        $this->assign('article_cat',$article_cat);        
        $this->display();
    }    
    /**
     * 文章内容页
     */
    public function detail(){
    	$article_id = $_GET['id'];
    	$article = D('article')->where("article_id=$article_id")->find();
        $article_cat = M('article_cat')->where(array('parent_id'=> 36))->select();
        $cat_arr = array();
        foreach($article_cat as $k=>$v){
            $cat_arr[] = $v['cat_id'];
        }
        $map['cat_id'] = array('in',$cat_arr);
        $id_arr = M('article')->field('article_id')->where($map)->select();
        //var_dump($id_arr);exit;
        $arr = array();
        foreach( $id_arr as $k=>$v){
            $arr[] =  $v['article_id'];
        }
        $pre = $article['article_id']-1;
        for($i=0;$i<count($arr);$i++) {
            if (!in_array($pre, $arr)) {
                $pre--;
                if($pre < min($arr)){
                    $pre = min($arr);
                }
            } else {
                $this->assign('pre', $pre);
                break;
            }
        }
        $next = $article['article_id']+1;
        for($i=0;$i<count($arr);$i++) {
            if (!in_array($next, $arr)) {
                $next++;
                if($next > max($arr)) {
                    $next = max($arr);
                }
            } else {
                $this->assign('next', $next);
                break;
            }
        }
    	if($article){
    		$parent = D('article_cat')->where("cat_id=".$article['cat_id'])->find();
    		$this->assign('cat_name',$parent['cat_name']);
            $this->assign('pre',$pre);
            $this->assign('next',$next);
    		$this->assign('article',$article);
    	}
        $this->display();
    } 
   
}