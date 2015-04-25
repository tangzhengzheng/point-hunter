<!DOCTYPE html>
{html}
{head}
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>{lang('title')}</title>
<meta name="description" content="{lang('description')}">
<meta name="keywords" content="{lang('keywords')}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
{require name="{$COMMON_CSS_PATH}amazeui.min.css"}
{require name="{$CSS_PATH}style.css"}
{/head}
{body}
<header class="am-topbar">
	<h1 class="am-topbar-brand">
    	<a href="#">{lang('title')}</a>
  	</h1>
  	<div class="am-dropdown am-topbar-right" data-am-dropdown>
  		<button class="am-btn am-btn-primary am-topbar-btn am-btn-sm am-dropdown-toggle" data-am-dropdown-toggle>更多</button>
  		<ul class="am-dropdown-content">
    		<li><a href="#">关于</a></li>
    		<li><a href="#">帮助</a></li>
  		</ul>
	</div>
	<div class="am-topbar-right" style="float:right">
      <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">登录</button>
    </div>
</header>