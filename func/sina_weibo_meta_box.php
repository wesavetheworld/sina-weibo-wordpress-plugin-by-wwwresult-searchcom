<?php 

$o = get_option($this->pre);
$formate = empty($o['formate']) ? '%title% %link%' : $o['formate'];

$send = 'no';
global $current_screen;
if($current_screen->post_type=='post' && $current_screen->action=='add')
{
	$send = 'yes';
}
$upload_image_or_not = 'yes';
if(!empty($o['upload_image_or_not']))
{
	$upload_image_or_not = $o['upload_image_or_not'];
}
?>

<p>
<?php _e('Do you want to update to sina weibo:',$this->pre) ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label>
	<input type="radio" name="sina_weibo[send_or_not]" value="yes" <?php if($send=='yes')echo 'checked="checked"';?>/>
	<?php _e('yes',$this->pre) ?>
</label>
<label>
	<input type="radio" name="sina_weibo[send_or_not]" value="no" <?php if($send=='no')echo 'checked="checked"';?>/>
	<?php _e('no',$this->pre) ?>
</label>
</p>
<p>
	<label><?php _e('Formate:',$this->pre) ?>
		<textarea cols="40" rows="2" name="sina_weibo[content]"><?php echo $formate?></textarea>
		<?php _e('Default Formate: %title% %link%',$this->pre) ?>
	</label>
</p>

<p>
<?php _e('remote image:',$this->pre) ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label>
	<input type="text" name="sina_weibo_url_image" style="width:300px" value="" />
</label>

</p>


<?php 
$using_days = $this->get_using_days();
if($using_days>3)
{
?>
也许您需要支持本地上传图片，自动获取文章特色图片，自动获取文章内容的第一张图片同步到微博功能？点击查看
<a href="http://www.yaha.me/002/wordpress-sina-t-plugin/unlimited"><img src="http://ww4.sinaimg.cn/large/7cd57fe4jw1dh1w1ov9y6j.jpg" width="400px" /></a>
<br />
您可以在选项设置中设置关闭这个功能提示
<?php 
}
?>
<p>
<?php _e('Having trouble while using this plugin?, Just <a href="http://www.yaha.me/go/?p=3" target="_blank">click here</a> come to yaha.me to get support!',$this->pre);?>
</p>
