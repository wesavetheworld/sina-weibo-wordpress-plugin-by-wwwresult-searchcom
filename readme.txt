=== sina-weibo-wordpress-plugin-by-www.result-search.com ===
Contributors: Lyman Lai
Author URI: http://www.yaha.me/go/?p=3
Plugin URI: http://www.yaha.me/go/?p=3
Tags: sina weibo,wp2sinaweibo,新浪微博,同步发布,sina,新浪
Requires at least: 3.1
Tested up to: 3.1
Stable tag: 3.0.4

This plugin makes it simple to update sina weibo message from wordpress.
Also it have the feature to send update status while you post a new blog

== Description ==
V3.0.4
更新了meta box 没有显示的bug

V3.0.3
更新了一个oauth类bug，如果系统已经有其他同类插件使用了oauth,则插件会停止启用，防止造成麻烦

V3.0.2
更新了插件的几个bug，同时adminstrator和Subscriber用户都可以有绑定微博帐号功能

V3.0.1
解决了Fatal error: Cannot redeclare class OAuthException 的bug，建议用户删除其他同类的插件，因为他们也用了weibooauth类，但是有可能使用的版本不是最新的，有可能导致本插件不能正常使用

V3.0 新功能

重新优化了插件的代码
评论时，如果是回复某条评论的，如果用户勾选同步到微博，则在微博系统上也做回复和转发
我的微博好友功能:在后台通过小工具添加我的微博好友，在前端展示微博好友的最新微博信息
非常灵活的消息格式定义，每篇文章还可以随时自定义当篇文章的格式。


功能特点

更新博客时，发送博客标题和博客文章地址到新浪微博
可以勾选选项框选择当前更新不发送到新浪微博
可以通过wordpress后台发送简单的信息到新浪微博
支持多语言，当前有中文和英文
不需要你输入帐号密码，保护你的sina帐号更安全
可以定制每次发布微博时的格式
优化了是否发表微博的设置，默认新建post会自动选择要发布微博，而编辑已有文章则默认是不发表微博的。
评论时，如果是回复某条评论的，如果用户勾选同步到微博，则在微博系统上也做回复和转发


欢迎使用我们的无限升级版，有更好的功能提供

== Installation ==


1. upload to `/wp-content/plugins/` directory
2. go to wordpress plugin page,active "sina weibo wordpress plugin"
3. at "Sina Weibo"->"renew author" author plugin(you must do this while you first install the plugin)


1. 上传到 `/wp-content/plugins/` 目录
2. 在Wordpress后台控制面板"插件(Plugins)"菜单下激活sina weibo wordpress plugin插件
3. 在Wordpress后台控制面板"新浪微博->重新授权"菜单下授权插件。（只有经过设置，插件才能正常使用）

== Frequently Asked Questions ==


== Changelog ==


== Upgrade Notice ==
