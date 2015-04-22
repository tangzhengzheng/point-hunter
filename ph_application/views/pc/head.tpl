<!DOCTYPE html>
{html}
{head}
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>{lang('title')}</title>
<meta name="description" content="{lang('description')}">
<meta name="keywords" content="{lang('keywords')}">
{require name="{$COMMON_CSS_PATH}amazeui.min.css"}
{require name="{$CSS_PATH}style.css"}
{/head}
{body}
<header>
  <div class="am-container">
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
    <div class="am-topbar-right">
      <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">登录</button>
    </div>
    <form class="am-topbar-form am-form-inline am-topbar-right" role="search">
     <div class="am-form-group">
       <input type="text" class="am-form-field am-input-sm" placeholder="搜索好点子">
     </div>
     <button type="submit" class="am-btn am-btn-default am-btn-sm">搜索</button>
    </form>
  </div>
</header>