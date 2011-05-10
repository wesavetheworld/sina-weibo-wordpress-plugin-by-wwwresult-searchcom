<?php 

$user = wp_get_current_user();

$do = '';
if(!empty($_GET['do']))
{
	$do = $_GET['do'];
}
switch($do)
{
	case 'unbind':
		update_user_meta($user->ID, 'open_id', '');
		update_user_meta($user->ID, 'sina_open_token_array'.$this->key, array());
?>
<script type="text/javascript">
//<![CDATA[
	window.location.href = '<?php echo $this->pageUrl?>';
//]]>
</script>
<?php 
		break;
	default:
		break;
}

if(is_user_logged_in() && $open_id = get_usermeta($user->ID,'open_id',1) )
{
?>
	<a href="<?php echo $this->pageUrl?>&do=unbind"><?php _e('unbind weibo',$this->pre)?></a>
<?php 
}
	
$this->loginout($args);