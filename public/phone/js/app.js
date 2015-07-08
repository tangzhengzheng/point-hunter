//@require public/common/js/hogan-2.0.0.min.js

var pointHunter = function(){
	var PAGE = {                                                                    //限速状态查询类型
		INDEX   : 'index',         //主页
		NEW     : 'new',           //新点子
		FAV     : 'fav'            //收藏
	};
	var currentPage   = PAGE.INDEX;
	__inline('index.js');
	__inline('new.js');
	__inline('fav.js');
	
	//滑屏加载滚动
    $(window).scroll(function(){  
    	var scrollTop = this.scrollY + $(this).height();
    	switch(currentPage){
			case PAGE.NEW:
				var $contentList = $('.am-tabs-bd div[data-tab-panel-new]');
				var height = $contentList.height();
				var diffHeight = height - scrollTop;
				if (diffHeight < parseInt(height * 0.3) &&  !newLoadAll) {
				}
				break;
			case PAGE.FAV:
				var $contentList = $('.am-tabs-bd div[data-tab-panel-fav]');
				var height = $contentList.height();
				var diffHeight = height - scrollTop;
				if (diffHeight < parseInt(height * 0.3) &&  !favLoadAll) {
					
				}
				break;
			default:
				var $contentList = $('.am-tabs-bd div[data-tab-panel-index]');
				var height = $contentList.height();
				var diffHeight = height - scrollTop;
				if (diffHeight < parseInt(height * 0.3) &&  !indexLoadAll) {
					getIndexData($contentList);
				}
				break;
    	}
    });

	var initRouter = function(event){
		var tab = location.hash.replace('#!','');
		var page = tab == '' ? PAGE.INDEX : tab;
		switch(page){
			case PAGE.NEW:
				newPoint();
				break;
			case PAGE.FAV:
				fav();
				break;
			default:
				index();
				break;
		}
	};

	var initTab = function(type){
		currentPage = type;
		$('.am-tabs-nav li').removeClass('am-active');
		$('.am-tabs-bd div').removeClass('am-active');
		$('.am-tabs-nav li a[tab="#!'+type+'"]').parent('li').addClass('am-active');
		$('.am-tabs-bd div[data-tab-panel-'+type+']').addClass('am-active');	
	}
	
	var pop = function(event){
		var androidLink = $(event.currentTarget).parents('.am-panel-bd').find('.am-icon-android').attr('link');
		var appleLink = $(event.currentTarget).parents('.am-panel-bd').find('.am-icon-apple').attr('link');
		var windowsLink = $(event.currentTarget).parents('.am-panel-bd').find('.am-icon-windows').attr('link');
		var homeLink = $(event.currentTarget).parents('.am-panel-bd').find('.am-icon-home').attr('link');
		if(typeof(androidLink) == 'undefined'){
			$('#androidLink').hide();
		}else{
			$('#androidLink').show().find('a').attr('href', androidLink);
		}
		if(typeof(appleLink) == 'undefined'){
			$('#appleLink').hide();
		}else{
			$('#appleLink').show().find('a').attr('href', appleLink);
		}
		if(typeof(windowsLink) == 'undefined'){
			$('#windowsLink').hide();
		}else{
			$('#windowsLink').show().find('a').attr('href', windowsLink);
		}
		if(typeof(homeLink) == 'undefined'){
			$('#homeLink').hide();
		}else{
			$('#homeLink').show().find('a').attr('href', homeLink);
		}
		$('#my-alert').modal('open');
	};
	
	var vote = function(event){
		var num = $(event.currentTarget).find('.vote-count').html();
		$(event.currentTarget).find('.vote-count').html(++num);
		$(event.currentTarget).addClass('voted').removeClass('point_vote');
		var recommentUrl = base_url + 'ajax/recomment?p='+$(event.currentTarget).attr('pid');
		ph_ajax(recommentUrl, null, function(data){});
	};
	
	var changeTab = function(event){
		var tab = $(event.currentTarget).attr('tab');
		location.hash = tab;
	};
	
	/**
	 * ajax封装
	 * @param {Object} url
	 * @param {Object} data
	 * @param {Object} callback
	 */
	var ph_ajax = function(url, data, successcb, errorcb, timeout, method){
		if(typeof timeout == 'undefined' || '' == timeout){
			timeout = 10000;
		}
		var params = {
            type: 'GET',
            url: url,
            dataType: 'json',
            timeout: timeout,
            cache: false,
            success: successcb,
            data: data
        }
		if(typeof errorcb == 'function'){
			params.error = errorcb;
		}
		if(typeof method != 'undefined' && '' != method){
			params['type'] = method;
		}
        $.ajax(params);
    };
	
	var init = function(){
		initRouter();
		window.onhashchange = initRouter;
		$('.am-tabs-bd div').on('click', '.pop', pop).on('click', '.point_vote', vote);
		$('.tab').click(changeTab);
		$('#my-alert').find('a').click(function(){
			$('#my-alert').modal('close');
		});
	};
	
	init();
}();