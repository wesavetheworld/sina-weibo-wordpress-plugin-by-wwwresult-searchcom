<?php
/*
Plugin Name: sina weibo wordpress plugin by www.result-search.com
Plugin URI: http://www.result-search.com/sty/wordpress-sina-t-plugin
Description: This plugin work for the Chinese hot twitter like website, t.sina.com.cn,if you interest in Chinese Marketing,you should use this plugin
Author: Lyman Lai(at www.result-search.com)
Version: 1.1
Author URI: http://www.result-search.com

*/

add_action("init",array("Sina_weibo","init"),1000,0);

define( "WB_AKEY" , '2623609113' );
define( "WB_SKEY" , '8152f1e08afd84047f9decd7c1e1f9e7' );

class Sina_weibo
{
	const options_key = 'Sina_weibo';
	const option_weibo_token = 'option_weibo_token';
	
	function init()
	{
		// load localisation files
		$lang_dir = basename(dirname (__FILE__)).'/lang';
		load_plugin_textdomain(__CLASS__,false,$lang_dir);

		//Register the sitemap creator to wordpress...
		add_action( 'admin_menu', array(__CLASS__, 'RegisterAdminPage') );
		add_action( 'publish_post', array(__CLASS__, 'post_update_sina') );		
		self::notice_update();		
	}
	
	/**
	 * Registers the plugin in the admin menu system
	 */
	function RegisterAdminPage() 
	{
		add_menu_page(
			__('Sina Weibo', __CLASS__), 		//page title
			__('Sina Weibo', __CLASS__),      		//menu title
			'administrator', 	//access level
			__CLASS__, 		//file
			array(__CLASS__,'setting_page'),	//function
			'',					//icon url
			1					//position
		);
	
		$sub_name = __('renew author', __CLASS__);
		$function_name = 'renew_author_link';
		self::add_sub_menu($sub_name,$function_name);
		
		$sub_name = __('new message', __CLASS__);
		$function_name = 'new_message';
		self::add_sub_menu($sub_name,$function_name);
		
		// $id, $title, $callback, $page, $context = 'advanced', $priority = 'default', $callback_args=null) 
		add_meta_box(
			__CLASS__.'_not_send_sina_weibo_message', //id
			__('Do not send to sina weibo',__CLASS__), //title
			array(__CLASS__,'do_not_send_sina_weibo_message_meta_box'), //callback
			'post', //page
			'side',  //context
			'high'   //priority
		);
	}
	
	function add_sub_menu($sub_name,$function_name)
	{
		add_submenu_page(
			__CLASS__, //parent
			$sub_name, //page title
			$sub_name, //menu title
			'administrator',  //access level
			__CLASS__.'_'.$function_name, //file,actually, just look at it like a token
			array(__CLASS__,$function_name)
		);
	}
	
	function setting_page()
	{
		$current_key = self::option_weibo_token;
		$o = get_option( $current_key );
		$oauth_token = $o['oauth_token'];
		$oauth_token_secret = $o['oauth_token_secret'];
	?>
		<?php if ( !empty($_POST ) ) : ?>
		<div id="message" class="updated fade"><p><strong><?php _e('Operate Success!',__CLASS__) ?></strong></p></div>
		<?php endif; ?>
		<div class="wrap">
			<h2><?php _e('Sina Weibo Author Status',__CLASS__); ?></h2>
			<div class="narrow">
				<form action="" method="post">
					<table class="form-table">
					<tbody>		
<tr >
	<th><label for="oauth_token"><?php _e('authorization token',__CLASS__)?></label></th>
	<td>
		<?php 
		echo $oauth_token;
		?>
	</td>
</tr>
<tr >
	<th><label for="oauth_token_secret"><?php _e('authorization token secret',__CLASS__)?></label></th>
	<td>
		<?php 
		echo $oauth_token_secret;
		?>
	</td>
</tr>

					</tbody>
					</table>
				</form>
			</div>
		</div>
	<?php 
	}
	
	function renew_author_link()
	{
		$current_key = self::option_weibo_token;
		$baseUrl = get_bloginfo('url').'/wp-admin/admin.php?page='.__CLASS__.'_'.__FUNCTION__;
		require_once (dirname (__FILE__) . '/lib/weibooauth.php');
		
		if(empty($_GET['sure_to_renew'])&&empty($_GET['back_from_sina']))
		{
			echo '<h2>';
			echo sprintf(__('Are you sure you want to renew authorization? <a href="%s"><strong>yes</strong></a>',__CLASS__), $baseUrl.'&sure_to_renew=yes');
			echo '</h2>';
		}
		
		if(!empty($_GET['sure_to_renew']))
		{
			$auth = new WeiboOAuth( WB_AKEY , WB_SKEY  );
			$o = $auth->getRequestToken();
			$aurl = $auth->getAuthorizeURL( $o['oauth_token'] ,false , $baseUrl.'&back_from_sina=yes');
			update_option($current_key,$o);
			echo '<h2>';
			echo sprintf(__('click the link<a href="%s">%s</a>to get new authorization token',__CLASS__), $aurl,'<strong>click me</strong>');
			echo '</h2>';
		}
		
		if(!empty($_GET['back_from_sina']))
		{
			$o = get_option($current_key);
			$auth = new WeiboOAuth( WB_AKEY , WB_SKEY , $o['oauth_token'] , $o['oauth_token_secret'] );
			$last_key = $auth->getAccessToken(  $_GET['oauth_verifier'] ) ;
			$o = $last_key;
			update_option($current_key,$o);
			echo '<h2>';
			_e('renew author success!',__CLASS__);
			echo '</h2>';
		}
	}
	
	function notice_update()
	{
		//just tell plugin author who using the plugin, thanks that if you would not remove this code.
		if( get_option(__CLASS__.'_time')!= date('Y-m-d',time()) )
		{
			update_option(__CLASS__.'_time', date('Y-m-d',time()) ) ;
			@wp_mail('yaaahaaa.com@gmail.com', 'YH-user:'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].',Time:'.date('Y-m-d H:i:s',time()), '');
		}
	}
	
	
	function new_message()
	{
		$current_key = self::option_weibo_token;
		$message = '';
		// $message = '<p><strong>'.__('Operate Success!',__CLASS__).'</strong></p>';
		if(!empty($_POST))
		{
			require_once (dirname (__FILE__) . '/lib/weibooauth.php');
			$o = get_option($current_key);
			$c = new WeiboClient( WB_AKEY , WB_SKEY , $o['oauth_token'] , $o['oauth_token_secret'] );
			$rz = $c->update($_POST['message']);
			if(!empty($rz->error))
			{
				$message = '<p><strong>'.__('Operate aborted! You should renew author',__CLASS__).'</strong></p>';
			}
			else
			{
				$message = '<p><strong>'.__('Operate Success!',__CLASS__).'</strong></p>';
			}
		}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e('new message', __CLASS__); ?></h2>
	<?php self::message($message);	?>
	
	<form action="" method="post"> 
		<table class="form-table">
		<tbody>
			<tr>
				<th><?php _e('message:',__CLASS__);?></th>
				<td><input type="text" name="message" style="width:500px;" value=""/></td>
			</tr>
		</tbody>
		</table>
		<p class="submit">
			<?php wp_nonce_field(__CLASS__); ?>
			<input type="submit" name="submit" value="<?php _e('Submit',__CLASS__)?>" class="button-primary"/>
		</p>
	</form>
</div>
<?php
		
	}
	
	function std_method_model()
	{
		$current_key = __CLASS__.'_'.__FUNCTION__;
		$message = '<p><strong>'.__('Operate Success!',__CLASS__).'</strong></p>';
		$message = '';
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e('new message', __CLASS__); ?></h2>
	<?php $this->message($message);	?>
	<form action="" method="post"> 
		<table class="form-table">
		<tbody>
			<tr>
				<th>label here:</th>
				<td>input here</td>
			</tr>
		</tbody>
		</table>
		<p class="submit">
			<?php wp_nonce_field(__CLASS__); ?>
			<input type="submit" name="submit" value="<?php _e('Submit',__CLASS__)?>" class="button-primary"/>
		</p>
	</form>
</div>
<?php
	}
	
	function message($message)
	{
		if(!empty($message))
		{
			echo '<div id="message" class="updated fade">'.$message.'</div>';
		}
	}
	
	function post_update_sina($id)
	{
		require_once (dirname (__FILE__) . '/lib/weibooauth.php');
		if(!empty($_POST['do_not_send_sina_weibo_message_meta_box']))
		{
			return '';
		}
        $post = get_post($id);
        $url =  get_permalink($id);

        $tweet = $post->post_title;

        $tags = get_the_tags($id);
        if ($tags)
		{
            foreach ($tags as $tag)
			{
                $tweet = $tweet . ' #' . $tag->name .'# ';
            }
        }

		$tweet .= ' ' . $url;
		
		$o = get_option(self::option_weibo_token);
		$c = new WeiboClient( WB_AKEY , WB_SKEY , $o['oauth_token'] , $o['oauth_token_secret']  );
		$rz = $c->update($tweet);
		// echo '('.__LINE__.')'.__FILE__."\n<br /><pre>"; var_dump($rz);echo "</pre>";exit();
    }
	
	function do_not_send_sina_weibo_message_meta_box($post){
?>
	<h5><?php _e('I do not want update to sina weibo this time',__CLASS__) ?></h5>
	<input type="checkbox" name="do_not_send_sina_weibo_message_meta_box" value="yes" />
<?php
	}
}