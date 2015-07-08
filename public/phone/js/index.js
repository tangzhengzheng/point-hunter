var indexLoading  = false;                                                                       
var indexLoadAll  = false;  
var indexDay = 0;

var index = function(){
	initTab(PAGE.INDEX);
	var $contentList = $('.am-tabs-bd div[data-tab-panel-index]');
	if($contentList.children('div').length == 0){
		getIndexData($contentList);
	}
};

var getIndexData = function($contentList){
	if(indexLoading === false ){
		indexLoading = true;
		$contentList.append($('#loadingTpl').html());
		var curPointNum = $('.am-tabs-bd div[data-tab-panel-index]').children('div[class="am-panel am-panel-default"]').length;
		var getDataUrl = base_url + 'ajax/getIndexData?curNum='+curPointNum+'&d='+indexDay;
		ph_ajax(getDataUrl, null, function(data){
			$contentList.find('.loadmsg').remove();
			var tpl = Hogan.compile($('#pointItem').html(),{delimiters:'<% %>'});
			$contentList.append(tpl.render({dataList:data.dataList})); 
			$.AMUI.gallery.init();
			indexDay = data.curDay;
			indexLoadAll = data.loadAll;
			indexLoading = false;
		});
	}
};

