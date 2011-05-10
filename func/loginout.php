<?php 
$user = wp_get_current_user();
if(is_user_logged_in() && $open_id = get_usermeta($user->ID,'open_id',1) )
{
	if(!empty($open_id))
	{
		$display_name=$user->display_name;
		$profile_url = get_option('siteurl') . '/wp-admin/profile.php';
		
		$logout_url = wp_logout_url(get_permalink());
		if( 
			is_front_page() ||
			is_home() ||
			is_archive()
		)
		{
			$logout_url = wp_logout_url(get_option('home'));
		}
?>	
<div class="<?php echo $this->pre?>-loginout"> 
<p>
<?php printf(__('You have Login as <a href="%s">%s</a> <a href="%s">Logout</a>',$this->pre), $profile_url, $display_name, $logout_url);?>
</p>
<iframe id="sina_widget_2104429332" style="width:100%; height:500px;" frameborder="0" scrolling="no" src="http://v.t.sina.com.cn/widget/widget_blog.php?uid=<?php echo $open_id?>&height=500&skin=wd_01&showpic=1"></iframe>
</div>
<?php
	}
}
else
{
	$this->show_bind_button();
}