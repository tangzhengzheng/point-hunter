<link rel="import" href="head.tpl?__inline">

<form class="am-form" action="{site_url('home/doLogin')}" method='POST' enctype="multipart/form-data">
	<fieldset>
		<div class="am-form-group">
      		<label>用户id</label>
      		<input type="text" placeholder="请输入用户id" name="uid" id="uid">
    	</div>
    	<p><button type="submit" class="am-btn am-btn-primary am-topbar-btn">提交</button></p>
	</fieldset>
</form>
<link rel="import" href="footer.tpl?__inline">