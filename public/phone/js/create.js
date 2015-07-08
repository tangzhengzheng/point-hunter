//@require public/common/js/jquery-1.7.1.min.js

var create = function(){
	var init = function(){
		$('#ideaThumb').live('change', ideaThumbChange);
		$(".am-form").submit(formSubmit);
	};
	
	var formSubmit = function(){
		var ideaName = $('#ideaName').val();
		var ideaDesc = $('#ideaDesc').val();
		var ideaWebsite = $('#ideaWebsite').val();
		var ideaAndroidLink = $('#ideaAndroidLink').val();
		var ideaIosLink = $('#ideaIosLink').val();
		var ideaWindowsLink = $('#ideaWindowsLink').val();
		if(ideaName == ''){
			alert('点子名称不可为空');
			return false;
		}
		if(ideaDesc == ''){
			alert('点子描述不可为空');
			return false;
		} 
		if(ideaWebsite == '' && ideaAndroidLink == '' && ideaIosLink == '' && ideaWindowsLink == ''){
			alert('点子官网/Android下载地址/Ios下载地址/Windows下载地址至少输入一项');
			return false;
		}
		return true;
	};
	
	var ideaThumbChange = function(){
		var docObj = document.getElementById("ideaThumb");
		var imgObjPreview = document.getElementById("preview");
		var filename = docObj.files[0].name;
		var filesize = docObj.files[0].size;
		var filetype = filename.substring(filename.indexOf("."));
		if(filetype != '.jpg' && filetype != '.png' && filetype != '.gif'){
			alert('仅支持图片文件');
			document.getElementById("ideaThumb").value = '';
			return;
		}
		if(filesize > 1024*1024*2){
			alert('仅支持2M以下的图片文件');
			document.getElementById("ideaThumb").value = '';
			return;
		}
		if(docObj.files && docObj.files[0]){
			imgObjPreview.style.display = 'block';
			imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
		}
	};
	
	init();
}();