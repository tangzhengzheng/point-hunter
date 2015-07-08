<link rel="import" href="head.tpl?__inline">
<div data-am-widget="tabs" class="am-tabs am-tabs-d2" id="indexTab">
  <ul class="am-tabs-nav am-cf">
    <li class="am-active">
      <a href="[data-tab-panel-index]" class="tab" tab="#!index">{lang('index')}</a>
    </li>
    <li>
      <a href="[data-tab-panel-new]" class="tab" tab="#!new">{lang('newPoint')}</a>
    </li>
    {if $uid != ''}
    <li>
      <a href="[data-tab-panel-fav]" class="tab" tab="#!fav">{lang('favorite')}</a>
    </li>
    {/if}
  </ul>
<div class="am-tabs-bd">
  <div data-tab-panel-index class="am-tab-panel am-active">
  </div>
  <div data-tab-panel-new class="am-tab-panel">
  </div>
  <div data-tab-panel-fav class="am-tab-panel">
  </div>
</div>
</div>
{require name="{$JS_PATH}app.js"}
<link rel="import" href="loading.tpl?__inline">
<link rel="import" href="pointItem.tpl?__inline">
<link rel="import" href="pointMore.tpl?__inline">
<link rel="import" href="footer.tpl?__inline">