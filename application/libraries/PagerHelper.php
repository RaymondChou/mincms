<?php
/**
 * @author Sun Kang <68103403@qq.com>
 * @link http://www.mincms.com/
 * @copyright 2013-2013 MinCMS Software
 * @license http://www.mincms.com/license/
 */
class PagerHelper{
	/**
	*
	$posts = DB::table('files')->order_by('id','desc')->paginate($per_page);
	{{PagerHelper::next($posts->last);}}
	Plugins\Masonry\Init::view(array(
 			'tag'=>'#masonry',
 			'itemSelector'=>'.item',
 			'scroll'=>true,
 		 
 		)); 
 		
	*/
	static function next($count){
		$page = (int)$_GET['page']?:1;
		$next = $page+1;
		if($page<=$count){
			$url = URL::current(); 
			$_GET['page']=$next; 
			$s = "?"; 
			foreach($_GET as $k=>$v)
				$s .=$k.'='.$v.'&';
			$url = $url.substr($s,0,-1); 
			echo "<div   class='pagination' style='display:none;'><a href='".$url."'></a></div>";
		}else{
			throw new Exception('exception');
		}
	}
 
	/**
	$p = \Vendor\Pager::img($posts,1,true,"apple_pagination showimg");
	$posts = $p[0];
	$pager = $p[1];	
	$per ÿҳ��ʾ����  $img �Ƿ���ͼƬ
	*/
	static function img($arr,$per=2,$img=false, $class='apple_pagination'){	 
		$current = (int)$_GET['page']?:1;
		$top = $current_page-1>0?:1;
		$next = $current_page+1;

		$num = count($arr);
		$page =  ceil($num/$per); 
		if($current>=$page)
			$current = $page;
 		$k=$i = ($current-1) * $per; 
	 	$j = $i+$per;
	 	 
	 	if($j>= $num) $j = $num;
	  
		foreach($arr as $k=>$v){
			$n[] = $v;	  
		} 
		for($i;$i<$j;$i++){
			$post[] = $n[$i];
		}
		
	 	$p = "<div class='".$class."'>";
		for($i=1;$i<=$page;$i++){
			unset($cls);
			if($i==$current)
				$cls = "class='current'";
			if($img==true){
				$p .= "<span><a href='?page=".$i."' $cls   data-content=\"<img src='".thumb($n[($i-1)*$per],400,300)."'/>\" >".$i."</a></span>";
			}
			else 
				$p .= "<span><a href='?page=".$i."' $cls >".$i."</a></span>";
		}
		$p .= "</div>";
		return array($post,$p);
	}
	
	/**
    * ��ת��ҳ
    */
    function jump_page($page,$pages){
    	$_inc = 10; //���� 
    	if($pages<5){
    		for($i=1;$i<=$pages; $i++){
    			$new[] = $i;
    		}
    	}else{
    		//ǰ��һ��
    		for($i=1;$i<6;$i++){
    			$new[] = $i;
    		}
    		//ѡ�з�ҳһ��
    		for($p = $page-5;$p <= $page+5 ; $p++){
    			if($p>0 && $p<=$pages && !in_array($p,$new)){
    				$new[] = $p;
    			} 
    		}   
			if(($p+$_inc) < $pages && ($p+$_inc) <= $pages-5){
	    		//��ǰ��5����ҳ ��1�Ĳ�����ʾ  
    			if(!in_array($p,$new)){
					while($p<$pages){  
						$p+=$_inc;
						$new[] = $p;
						$_inc*=2;
					} 
    			} 
	    	}  
	    	//��β��һ�� 
    		for($i = $pages-5;$i<=$pages;$i++){
    			if(!in_array($i,$new) && $i>0){
    				$new[] = $i;
    			}
    		} 
    	} 
    	return $new;
    }

}