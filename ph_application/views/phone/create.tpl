<link rel="import" href="head.tpl?__inline">

<form class="am-form" action="{site_url('idea/create')}" method='POST' enctype="multipart/form-data">
	<fieldset>
		<div class="am-form-group">
      		<select name="ideaPost">
        		<option value="1">点子作者</option>
        		<option value="2">点子猎手</option>
      		</select>
    	</div>
		<div class="am-form-group">
      		<label>点子名称</label>
      		<input type="text" placeholder="如：小米App" name="ideaName" id="ideaName">
    	</div>
    	<div class="am-form-group">
      		<label>一句话描述该点子</label>
      		<textarea rows="5" name="ideaDesc" id="ideaDesc"></textarea>
    	</div>
    	<div class="am-form-group">
      		<label>点子官网</label>
      		<input type="text" placeholder="以http://开头的有效网址" name="ideaWebsite" id="ideaWebsite">
    	</div>
    	<div class="am-form-group">
      		<label>Android下载地址</label>
      		<input type="text" placeholder="以http://开头的有效网址" name="ideaAndroidLink" id="ideaAndroidLink">
    	</div>
    	<div class="am-form-group">
      		<label>Ios下载地址</label>
      		<input type="text" placeholder="以http://开头的有效网址" name="ideaIosLink" id="ideaIosLink">
    	</div>
    	<div class="am-form-group">
      		<label>Windows下载地址</label>
      		<input type="text" placeholder="以http://开头的有效网址" name="ideaWindowsLink" id="ideaWindowsLink">
    	</div>
    	<div class="am-form-group am-form-file">
      		<label>点子截图</label>
      		<div>
      			<img id="preview" class="preview">
        		<button type="button" class="am-btn am-btn-default am-btn-sm">
          		<i class="am-icon-cloud-upload"></i>选择要上传的文件</button>
      		</div>
      		<input type="file" id="ideaThumb" name="ideaThumb">
    	</div>
    	<p><button type="submit" class="am-btn am-btn-primary am-topbar-btn">提交</button></p>
	</fieldset>
</form>
{require name="{$JS_PATH}create.js"}
<link rel="import" href="footer.tpl?__inline">