var newLoading    = false;                                                                       
var newLoadAll    = false; 
var newOffset     = 0;

var newPoint = function(){
	initTab(PAGE.NEW);
	var $contentList = $('.am-tabs-bd div[data-tab-panel-new]');
	if($contentList.children('div').length == 0){
		getNewData($contentList);
	}
};

var getNewData = function($contentList){
	if(newLoading === false ){
		newLoading = true;
		$contentList.append($('#loadingTpl').html());
		var curPointNum = $('.am-tabs-bd div[data-tab-panel-new]').children('div[class="am-panel am-panel-default"]').length;
		var getDataUrl = base_url + 'ajax/getNewData?offset='+curPointNum;
		ph_ajax(getDataUrl, null, function(data){
			$contentList.find('.loadmsg').remove();
			var tpl = Hogan.compile($('#pointItem').html(),{delimiters:'<% %>'});
			$contentList.append(tpl.render({dataList:data.dataList})); 
			$.AMUI.gallery.init();
			newLoadAll = data.loadAll;
			newLoading = false;
		});
	}
};