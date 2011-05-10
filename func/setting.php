<?php 

$o = get_option($this->pre);
$message = '';

$formate = $o['formate'];
if(empty($formate))
{
	$formate = '%title% %link%';
}

if(!empty($_POST['sina_weibo']))
{
	$sina_weibo = $_POST['sina_weibo'];
	$o['formate'] = $sina_weibo['formate'];
	$o['upload_image_or_not'] = $sina_weibo['upload_image_or_not'];
	if(!empty($sina_weibo['block_promotion']))
	{
		$o['block_promotion'] = $sina_weibo['block_promotion'];
	}
	update_option($this->pre,$o);
	$message = '<p><strong>'.__('Operate Success!',$this->pre).'</strong></p>';
}

$block_promotion = 'yes';
if(!empty($o['block_promotion']))
{
	$block_promotion = $o['block_promotion'];
}

$upload_image_or_not = 'yes';
if(!empty($o['upload_image_or_not']))
{
	$upload_image_or_not = $o['upload_image_or_not'];
}

$this->message($message);	
?>

<form action="" method="post"> 
	<table class="form-table">
	<tbody>
		<tr>
			<th><?php _e('Default Formate:',$this->pre)?></th>
			<td><input type="text" name="sina_weibo[formate]" value="<?php echo $formate?>" style="width:300px" /> <?php _e('%title% %link%',$this->pre)?></td>
		</tr>
		<tr>
			<th><?php _e('Upload Image for Default:',$this->pre)?></th>
			<td>
				<label>
					<input type="radio" name="sina_weibo[upload_image_or_not]" value="yes" <?php if($upload_image_or_not=='yes')echo 'checked="checked"';?>/>
					<?php _e('yes',$this->pre) ?>
				</label>
				<label>
					<input type="radio" name="sina_weibo[upload_image_or_not]" value="no" <?php if($upload_image_or_not=='no')echo 'checked="checked"';?>/>
					<?php _e('no',$this->pre) ?>
				</label>
			</td>
		</tr>
		
		<?php 
		$active_date = get_option($this->pre.'active_date');
		$today = current_time('timestamp');
		$using_days = ($today - $active_date)/3600/24;
		
		if($using_days>3)
		{
		?>
		<tr>
			<th><?php _e('Block Unlimited Feature Promotion:',$this->pre)?></th>
			<td>
				<label>
					<input type="radio" name="sina_weibo[block_promotion]" value="yes" <?php if($block_promotion=='yes')echo 'checked="checked"';?>/>
					<?php _e('yes',$this->pre) ?>
				</label>
				<label>
					<input type="radio" name="sina_weibo[block_promotion]" value="no" <?php if($block_promotion=='no')echo 'checked="checked"';?>/>
					<?php _e('no',$this->pre) ?>
				</label>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Submit',$this->pre)?>" class="button-primary"/>
	</p>
</form>
