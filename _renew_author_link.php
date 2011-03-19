<?php 
$o = get_option(self::option_weibo_token);
require_once (dirname (__FILE__) . '/lib/weibooauth.php');
$do = '';
if(!empty($_GET['do']))
{
	$do = $_GET['do'];
}
switch($do)
{
	case 'select_cat_to_impower':
		$auth = new WeiboOAuth( self::$wb_key , self::$wb_skey  );
		$newToken = $auth->getRequestToken();
		$o = $newToken;
		$aurl = $auth->getAuthorizeURL( $newToken['oauth_token'] ,false , self::$pageUrl.'&do=back_from_author_server');
		update_option(self::option_weibo_token,$o);
		?>
		<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){
			window.location.href = '<?php echo $aurl?>';
		});
		//]]>
		</script>
		<?php 
		break;
	case 'back_from_author_server':
		
		$newToken = '';
		$o = get_option(self::option_weibo_token);
		$newToken = $o;
		$auth = new WeiboOAuth( self::$wb_key , self::$wb_skey , $newToken['oauth_token'] , $newToken['oauth_token_secret'] );
		$last_key = $auth->getAccessToken(  $_GET['oauth_verifier'] ) ;
		$o = $last_key;
		$author_list_page = get_admin_url().'admin.php?page='.self::$class.'_author_list';
		?>
		<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){
			window.location.href = '<?php echo $author_list_page?>';
		});
		//]]>
		</script>
		<?php 
		update_option(self::option_weibo_token,$o);
		break;
	
	default:
?>
<table class="form-table">
<tbody>
	<tr valign="top">
		<th scope="row"><?php _e('Are you sure you want to reauthor?',self::$class)?></th>
		<td id="cat_for_impower">
			<input type="submit" value="<?php _e('submit',self::$class)?>" class="button-primary" id="submit" name="submit">
		</td>
	</tr>
</tbody>
</table>
<p class="submit"></p>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){
	$('#submit').click(function(){
		var url	= "<?php echo self::$pageUrl?>&do=select_cat_to_impower";
		window.location.href = url;
	});
});
//]]>
</script>
<?php 
}