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
    	<a href="{site_url('home/index')}">{lang('title')}</a>
  	</h1>
  	<div class="am-dropdown am-topbar-right" data-am-dropdown>
  		<button class="am-btn am-btn-primary am-topbar-btn am-btn-sm am-dropdown-toggle" data-am-dropdown-toggle>{lang('more')}<span class="am-icon-caret-down"></span></button>
  		<ul class="am-dropdown-content">
    		<li><a href="#">{lang('about')}</a></li>
    		<li><a href="#">{lang('help')}</a></li>
    		{if $uid != ''}
    			<li><a href="{site_url('home/create')}">{lang('create')}</a></li>
    			<li><a href="{site_url('home/doLogout')}">{lang('logout')}</a></li>
    		{/if}
  		</ul>
	</div>
	<div class="am-topbar-right" style="float:right">
      {if $uid != ''}
          <p class="topbar-txt">您好，{$uid}</p>
      {else}
          <a href="{site_url('home/login')}" class="am-btn am-btn-primary am-topbar-btn am-btn-sm">{lang('login')}</a>
      {/if}
    </div> 
</header>
{require name="{$COMMON_JS_PATH}jquery-1.7.1.min.js"}
{require name="{$COMMON_JS_PATH}amazeui.min.js"}