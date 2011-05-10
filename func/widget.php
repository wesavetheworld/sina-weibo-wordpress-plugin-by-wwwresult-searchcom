<?php 

class www_yaha_me_weibo_login_widget extends WP_Widget{
	function www_yaha_me_weibo_login_widget(){
		$this->pre = DOMAIN_www_yaha_me_weibo;
		$desc = array(
			'classname'=>__CLASS__,
			'description'=>__('weibo login',$this->pre)
		);
		
		$this->WP_Widget(
			false,
			__('weibo login',$this->pre),
			$desc
		);
	}
	function form($instance){
	}
	function update($new_instance,$old_instance){
		return $instance;
	}
	function widget($args,$instance){
		global $www_yaha_me_weibo;
		$www_yaha_me_weibo->loginout();
	?>
<?php 
	}
}