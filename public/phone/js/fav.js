var favLoading    = false;                                                                       
var favLoadAll    = false; 

var fav = function(){
	initTab(PAGE.FAV);
	var $contentList = $('.am-tabs-bd div[data-tab-panel-fav]');
	if($contentList.children('div').length == 0){
		if(favLoading === false ){
			favLoading = true;
			$contentList.append($('#loadingTpl').html()); 
			setTimeout(function(){
				$contentList.find('.loadmsg').remove();
				$contentList.append('<div>收藏</div>'); 
				favLoading = false;
			}, 1000);
		}
	}
};