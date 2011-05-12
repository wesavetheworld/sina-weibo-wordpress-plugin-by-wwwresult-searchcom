<?php
/*
Plugin Name: sina weibo wordpress plugin by http://www.yaha.me/002/
Plugin URI: http://www.yaha.me/002/wordpress-sina-t-plugin
Description: This plugin work for the Chinese hot twitter like website, t.sina.com.cn,if you interest in Chinese Marketing,you should use this plugin
Author: Lyman Lai(at www.yaha.me)
Version: 3.0.4
Author URI: http://www.yaha.me/about/lyman

*/

define('DOMAIN_www_yaha_me_weibo','www_yaha_me_weibo_');

global $www_yaha_me_weibo;
$www_yaha_me_weibo = new www_yaha_me_weibo();

register_deactivation_hook(__FILE__, 'yh_plugin_register_deactivation_hook');
function yh_plugin_register_deactivation_hook($args='')
{
	global $wpdb;
	$sql = "delete from $wpdb->options where option_name like 'www_yaha_me_weibo_%'";
	$wpdb->query($sql);
}

class www_yaha_me_weibo
{
	var $pre = '';
	var $key = '2623609113';
	var $skey = '8152f1e08afd84047f9decd7c1e1f9e7';
	// var $is_vip = 1;
	var $is_vip = 0;
	
	var $homepage = 'http://www.yaha.me/go/?p=3';
	var $pluginPageOnWp  = 'http://wordpress.org/extend/plugins/sina-weibo-wordpress-plugin-by-wwwresult-searchcom/';
	var $rss_url = 'http://www.yaha.me/002/feed/';
	var $title_arr = '';
	var $func = '';
	var $pageUrl = '';
	var $url = '';
	var $dir = '';
	var $lib = '';
	
	function std_func($args=''){$this->include_func_file(__FUNCTION__,$args);}
	function publish_post($args=''){$this->include_func_file(__FUNCTION__,$args);}
	function save_post($args=''){$this->include_func_file('publish_post',$args);}
	function sina_weibo_meta_box($args=''){$this->include_func_file(__FUNCTION__,$args);}
	
	function comment_post($args=''){$this->include_func_file(__FUNCTION__,$args);}
	function comment_form($args=''){$this->include_func_file(__FUNCTION__,$args);}
	function login_form($args=''){$this->loginout();}
	function loginout($args=''){$this->include_func_file(__FUNCTION__,$args);}
	function show_bind_button($args=''){$this->include_func_file(__FUNCTION__,$args);}
		
	// function show_user_profile($args=''){$this->include_func_file(__FUNCTION__,$args);}
	// function edit_user_profile($args=''){$this->show_user_profile($args);}
	// function personal_options_update($args=''){$this->include_func_file(__FUNCTION__,$args);}
	// function edit_user_profile_update($args=''){$this->personal_options_update($args);}
	
	function all_admin_notices($args)
	{
		echo '('.__LINE__.')'.__FILE__."\n<br /><pre>"; var_dump($args);echo "</pre>";exit();
	}
	function __construct()
	{
		if(class_exists('OAuthException'))
		{
			add_action('all_admin_notices',array(&$this,'all_admin_notices'));
			exit();
		}
		if (session_id() == "") 
		{
			session_start();
		}
		
		$this->pre = DOMAIN_www_yaha_me_weibo;		
		$this->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$this->dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$this->root_dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$this->lib = $this->dir.'lib/';
		if($this->is_vip)
		{
			$this->dir .= 'vip/';
		}
		else
		{
			$this->dir .= 'func/';
		}
		// load localisation files
		$lang_dir = basename(dirname (__FILE__)).'/lang';
		load_plugin_textdomain($this->pre,false,$lang_dir);
		
		$this->include_oauth_api();
		
		include $this->dir.'widget.php';
		add_action( 'widgets_init', array(&$this,'widgets_init') );
		add_action('init',array(&$this,'init'));
	}
	
	function init()
	{
		$filter_arr = array(
			'admin_menu',
			'wp_dashboard_setup',
			'admin_print_scripts',
			'comment_form',
			'login_form',
			'http_request_timeout',
			'comment_post',
			'publish_post',
			'get_comment_author_link',
			'wp_footer',
			// 'wp_mail_from',
		
			// Adding custom user fields
			// 'show_user_profile',
			// 'edit_user_profile',
			// Saving the custom user fields
			// 'personal_options_update',
			// 'edit_user_profile_update',
		);
		if(is_array($filter_arr))
		foreach( $filter_arr as $val )
		{
			add_filter($val, array(&$this,$val));
		}
		add_filter("get_avatar", array(&$this, 'get_avatar'),10,4 );
		
		if(
			!empty($_GET['is_ajax']) &&
			!empty($_GET['action']) && 
			method_exists($this,$action = str_ireplace($this->pre,'',$_GET['action'])) &&
			substr($action,-5)=='_ajax'
		)
		{
			$this->$action();exit();
		}
		
		$this->notice_update();
	}

	function get_comment_author_link($args='')
	{
		$author_link = $args;
		global $comment;
		if($comment->user_id!=0 && $open_id = get_user_meta($comment->user_id, 'open_id',1))
		{
			$weibo_link = ' <span class="weibo">[<a href="http://weibo.com/'.$open_id.'">'.__('weibo',$this->pre).'</a>]</span>';
			$author_link .= $weibo_link;
		}
		
		return $author_link;
	}
	
	function get_avatar($avatar, $id_or_email='',$size='32') {
		if(is_object($id_or_email))
		{
			global $comment;
			if(is_object($comment)) {
				$id_or_email = $comment->user_id;
			}
			if (is_object($id_or_email)){
				$id_or_email = $id_or_email->user_id;
			}
		}
		
		if($open_id = get_user_meta($id_or_email, 'open_id',1)){
			$out = 'http://tp3.sinaimg.cn/'.$open_id.'/50/1';
			$avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
			return $avatar;
		}else {
			return $avatar;
		}
	}
	
	function wp_footer()
	{
?>
			
<?php 
	}
	
	function login_start_ajax()
	{
		$key = $this->key;
		$skey = $this->skey;
		$auth = new WeiboOAuth($key, $skey);
		$token = $auth->getRequestToken(get_option('home'));

		$_SESSION['token'] = $token;
		$callback_url = $this->get_ajax_action('login_save');
		
		$request_link = $auth->getAuthorizeURL($token['oauth_token'],true,$callback_url);
		header('Location:'.$request_link);
	}
	
	function login_save_ajax()
	{
		$key = $this->key;
		$skey = $this->skey;
		$auth = new WeiboOAuth($key, $skey, $_GET['oauth_token'],$_SESSION['token']['oauth_token_secret']);
		$token = $auth->getAccessToken($_GET['oauth_verifier']);
		$auth = new WeiboOAuth($key, $skey, $token['oauth_token'], $token['oauth_token_secret']);

		$weibo_user_infor = $auth->OAuthRequest('http://api.t.sina.com.cn/account/verify_credentials.json', 'GET',array());
		$weibo_user_infor = json_decode($weibo_user_infor);
		$user_login = 'sina_'.$weibo_user_infor->id;
		
		if(!empty($weibo_user_infor->id))
		{
			$user_infor = array(
				'open_id'=>$weibo_user_infor->id,
				'user_login'=>$user_login,
				'user_nicename'=>$weibo_user_infor->screen_name,
				'url'=>$weibo_user_infor->url,
				'oauth_token'=>$token['oauth_token'],
				'oauth_token_secret'=>$token['oauth_token_secret'],
				'weibo_user_infor'=>$weibo_user_infor,
			);
			$this->do_login($user_infor);
		}
		else
		{
			echo '('.__LINE__.')'.__FILE__."\n<br />"; var_dump("error while trying to author open platform",$weibo_user_infor);exit();
		}
	}
	
	function do_login($user_infor) 
	{
		$weibo_user_infor = $user_infor['weibo_user_infor'];
		$userdata = array(
			'user_pass' => wp_generate_password(),
			'user_login' => $user_infor['user_login'],
			'display_name' => $user_infor['user_nicename'],
			'user_url' => $user_infor['url'],
			'user_email' => $user_infor['open_id'].'@t.sina.com.cn',
		);
		
		$wp_user = '';
		if(is_user_logged_in())
		{
			$wp_user = wp_get_current_user();
		}
		else
		{
			$wp_user = get_user_by('login', $user_infor['user_login']);
		}
		
		if(!empty($wp_user))
		{
			$wp_user_id = $wp_user->ID;
		}
		else
		{
			$wp_user_id = wp_insert_user($userdata);
		}
		if(is_multisite())
		{
			global $blog_id;
			add_user_to_blog( $blog_id, $wp_user_id, 'subscriber' );
		}
		update_user_meta($wp_user_id, 'open_id', $user_infor['open_id']);
		$open_token_array = array (
			"oauth_token" => $user_infor['oauth_token'],
			"oauth_token_secret" => $user_infor['oauth_token_secret'],
		);
		update_user_meta($wp_user_id, 'sina_open_token_array'.$this->key, $open_token_array);
		
		update_user_meta($wp_user_id, 'gender', $weibo_user_infor->gender);
		update_user_meta($wp_user_id, 'location', $weibo_user_infor->location);
		
		wp_set_auth_cookie($wp_user_id, true, false);
		wp_set_current_user($wp_user_id);
		if(isset($_GET['oauth_token'])){
			echo '<script type="text/javascript">window.opener.'.DOMAIN_www_yaha_me_weibo.'_reload("");window.close();</script>';
		}
	}
	
	function widgets_init()
	{
		register_widget('www_yaha_me_weibo_login_widget');
		// register_widget('smc_weibo_timeline_widget');
	}
	
	function admin_print_scripts()
	{
		global $pagenow;
		if($pagenow=='admin.php')
		{
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
		}
	}
	
	function admin_menu() 
	{
		$capability = 'subscriber';
		add_menu_page(
			__('Weibo', $this->pre), 		//$page_title
			__('Weibo', $this->pre),      	//$menu_title
			$capability, 				//$capability
			$this->pre, 					//$menu_slug
			array(&$this,'show_html'),	//$function
			'',								//$icon_url
			1								//$position
		);
		$this->title_arr['help'] = __('Help Information',$this->pre);
		
		$sub_menu_arr = array(
			'bind_weibo'=>__('bind weibo', $this->pre),
			'category_bind'=>__('category bind', $this->pre),
			'new_message'=>__('new message', $this->pre),
			'setting'=>__('options setting', $this->pre),
			'reset_all_setting'=>__('reset settings', $this->pre),
		);
		
		
		$using_days = $this->get_using_days();
		foreach( $sub_menu_arr as $function_name=>$sub_name )
		{
			if( $using_days<3 )
			{
				if( !$this->is_vip && $function_name=='category_bind' )
				{
					continue;
				}
			}
			$this->add_submenu_page($sub_name,$function_name);
		}

		// $id, $title, $callback, $page, $context = 'advanced', $priority = 'default', $callback_args=null) 
		if(current_user_can('administrator'))
		{
			add_meta_box(
				$this->pre.'send_or_not_sina_weibo_message', //id
				__('Weibo',$this->pre), //title
				array(&$this,'sina_weibo_meta_box'), //callback
				'post', //page
				'normal',  //context
				'high'   //priority
			);
		}
	}
	
	function get_using_days()
	{
		// $active_date = current_time('timestamp')-4*3600*24;
		// update_option($this->pre.'active_date',$active_date);
		
		$active_date = get_option($this->pre.'active_date');
		if(empty($active_date))
		{
			$active_date = current_time('timestamp');
			update_option($this->pre.'active_date',$active_date);
		}
		$today = current_time('timestamp');
		$using_days = ($today - $active_date)/3600/24;
		
		$o = get_option($this->pre);
		if(!empty($o['block_promotion']) && $o['block_promotion']=='yes')
		{
			$using_days = 1;
		}
		return $using_days;
	}
	function add_submenu_page($sub_name,$function_name)
	{
		$capability = 'administrator';
		if($function_name=='bind_weibo')
		{
			$capability = '0';
		}
		add_submenu_page(
			$this->pre, //parent
			$sub_name, //page title
			$sub_name, //menu title
			$capability,  //access level
			$this->pre.$function_name, //file,actually, just look at it like a token
			array(&$this,'show_html')
		);
		
		$this->title_arr[$function_name] = $sub_name;
	}
	
	function show_html()
	{
		$func = str_ireplace($this->pre,'',$_GET['page']);
		if(empty($func))
		{
			$func = 'help';
		}
		$this->func = $func;
		$title = $this->title_arr[$func];
		$this->pageUrl = get_admin_url().'admin.php?page='.$this->pre.$this->func;
?>
		<div class="wrap">
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e('Sina Weibo',$this->pre)?></h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<div id="<?php echo $func; ?>" class="postbox">
								<div class="handlediv" title="Click to toggle"><br /></div>
								<h3 class="hndle"><span><?php echo $title; ?></span></h3>
								<div class="inside" style="padding:10px;">
								<?php 
									$file = $this->dir.$func.'.php';
									include $file;
								?>
								</div>
							</div>
						<?php include $this->dir.'bottom.php';?>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:20%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<?php
								$this->plugin_like();
								$this->plugin_support();
								$this->news();
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
<?php			
	}
	
	function notice_update()
	{
		$timeflag = get_option($this->pre.'time');
		$today = date('Y-m-d',current_time('timestamp'));
		if($timeflag==$today)
		{
			return '';
		}
		if(is_user_logged_in()&&current_user_can('administrator'))
		{
			add_action('admin_footer',array(&$this,'notice_update_js'));
		}
	}
	
	function notice_update_js()
	{
		$url = $this->get_ajax_action('notice_update');
?>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){
	$.ajax({
		type	: "GET",
		cache	: false,
		url		: "<?php echo $url?>",
		success: function(data) {
		}
	});
});
//]]>
</script>
<?php 
	}
	function notice_update_ajax()
	{
		set_time_limit(0);
		
		//just tell plugin author who using the plugin, thanks that if you would not remove this code.
		update_option($this->pre.'like', file_get_contents('http://www.yaha.me/plg-news/like.php') );
		update_option($this->pre.'bottom', file_get_contents('http://www.yaha.me/plg-news/bottom.php') );
		
		$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$headers = 'From: '. get_option('blogname') .' <' . get_option('admin_email') . '>';
		@wp_mail('yaaahaaa.com@gmail.com', 'weibo vip:'.$url.',Time:'.date('Y-m-d H:i:s',time()), $url,$headers);
		
		//get news(blog rss)
		require_once(ABSPATH.WPINC.'/rss.php'); 
		ob_start();
		wp_widget_rss_output($this->rss_url, array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0 ));
		$content = ob_get_contents();
		ob_end_clean();
		update_option($this->pre.'news', $content );
		
		$today = date('Y-m-d',current_time('timestamp'));
		update_option($this->pre.'time', $today ) ;
	}
	
	function postbox($id, $title, $content) {
	?>
		<div id="<?php echo $id; ?>" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php echo $title; ?></span></h3>
			<div class="inside" style="padding:10px;">
				<?php echo $content; ?>
			</div>
		</div>
	<?php
	}
	
	function plugin_like() {
		$content = get_option($this->pre.'like');
		if(empty($content))
		{
			$content = file_get_contents($this->root_dir.'plg-news/like.php');
		}
		$this->postbox($this->pre.'like', __('Like this plugin?',$this->pre), $content);
	}	
	
	function plugin_support() {
		$content = '<p>'.__('If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the',$this->pre).' <a href="'.$this->homepage.'">'.__("Support forums",$this->pre).'</a>.</p>';
		$content = '<p>'.__('If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the',$this->pre).' <a href="'.$this->homepage.'">'.__("Support forums",$this->pre).'</a>.</p>';
		$this->postbox($this->pre.'support', __('Need support?',$this->pre), $content);
	}

	function news() 
	{
		$content = get_option($this->pre.'news');
		if(empty($content))
		{
			$content = file_get_contents($this->root_dir.'plg-news/news.php');
		}
		$this->postbox('yaha-me', __('Latest news from yaha.me',$this->pre), $content);
	}
	
	function message($message)
	{
		if(!empty($message))
		{
			echo '<div id="message" class="updated fade">'.$message.'</div>';
		}
	}
	
	function wp_dashboard_setup() 
	{
		wp_add_dashboard_widget( $this->pre.'db_widget' , __('Something maybe you are interesting?',$this->pre) , array(&$this, 'db_widget'));
	}
	
	function db_widget()
	{
		$content = get_option($this->pre.'bottom');
		echo $content;
	}
	
	function get_ajax_action($action)
	{
		$action = $this->pre.$action.'_ajax';
		return get_bloginfo('url').'/?is_ajax=yes&action='.$action;
	}
	
	function include_func_file($func='',$args='')
	{
		$file = $this->dir.$func.'.php';
		if(file_exists($file))
		{
			include $file;
		}
	}
	
	function include_oauth_api()
	{
		require_once $this->lib.'weibooauth.php';
	}
	
	function http_request_timeout($timeout)
	{
		return 60;
	}
}