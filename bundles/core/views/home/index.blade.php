@layout('core::layout') 
@section('content') 
	<div id='home'>
	<?php  
		$rows = \DB::table('fields')->where('pid','=',0)
		 	->order_by('sort','desc')
		 	->order_by('id','desc')
		 	->get();  
		if($rows){ 
			echo "<div class='span12'><h3>".__('admin.website content')."</h3></div>";
			echo "<div class='span12'>";
			foreach($rows as $row){
				unset($active);
				if(is_array(Menu::get_active()) && in_array($row->value,Menu::get_active())){
					$active="class='active'";
				}
				if($row->lock==1){
			 		if(Auth::user()->id!=1){
			 			continue;
			 		}
		 		}
		 		if(CMS::check("node.".$row->value.".index")){
					echo "<blockquote><p><a   href='".action('core/node/index',array('name'=>$row->value))."'>".$row->label.'</a></p></blockquote>';
				}
			}
			echo "</div>";
		 
		} 
	?> 
	</div>
<?php CMS::style('a',"a.btn{margin-right:10px;margin-bottom:10px;}");?> 
@endsection