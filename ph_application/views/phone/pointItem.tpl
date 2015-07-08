<script type="text/template" id="pointItem">
<%#dataList%>
<%#date%><p class="point_date"><%date%></p><%/date%>
<%#pointList%>
<div class="am-panel am-panel-default">
	<div class="am-panel-bd">
    	<div class="point_title">
    		<button type="button" class="am-btn am-btn-default am-round  <%#voted%>voted<%/voted%><%^voted%>point_vote<%/voted%>" pid="<%id%>">
    			<i class="upvote-arrow"></i>
    			<span class="vote-count"><%rank%></span>
    	    </button>
    		<div class="point_name">
    			<span class="pop"><%title%></span>
    			<span class="point_level">
    				<i class="point_new"><%#isNew%>新<%/isNew%></i>
					<%#star%>
						<i class="star"></i>
					<%/star%>
					<%#androidUrl%>
						<span class="am-icon-android" link="<%androidUrl%>"></span>
					<%/androidUrl%>
					<%#iosUrl%>
						<span class="am-icon-apple" link="<%iosUrl%>"></span>
					<%/iosUrl%>
					<%#pcUrl%>
						<span class="am-icon-windows" link="<%pcUrl%>"></span>
					<%/pcUrl%>
					<%#homeUrl%>
						<span class="am-icon-home" link="<%homeUrl%>"></span>
					<%/homeUrl%>
    			</span>
    		</div>
    	</div>
    	<div class="point_content pop">
    		<%brief%>
    	</div>
    	<ul data-am-widget="gallery" class="am-gallery am-avg-sm-3 am-gallery-default" data-am-gallery="{ pureview: true }">
			<li><div class="am-gallery-item"><img src='{uri name="{$IMG_PATH}demo.jpg"}' /></div></li>
  			<li><div class="am-gallery-item"><img src='{uri name="{$IMG_PATH}demo.jpg"}' /></div></li>
  			<li><div class="am-gallery-item"><img src='{uri name="{$IMG_PATH}demo.jpg"}' /></div></li>
  		</ul>
		<div class="point_reply">
			<a href="{site_url('idea/detail?id=<%id%>')}"><p><span class="am-icon-comments"><%comment_num%></span></p></a>
		</div>
		<div class="point_footer">
			<%#creator%>
				<div class="user-image">C</div>
				<img src='{uri name="{$IMG_PATH}user.png"}' /><span></span>
			<%/creator%>
			<%#hunter%>
				<div class="user-image">H</div>
				<img src='{uri name="{$IMG_PATH}user.png"}' />
			<%/hunter%>
		    <%#upvoters%>
				<img src='{uri name="{$IMG_PATH}user.png"}' />
			<%/upvoters%>
		</div>
    </div>
</div>
<%/pointList%>
<%/dataList%>
</script>