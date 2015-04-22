//smarty压缩xss修复插件
fis.config.set('modules.optimizer.tpl', 'html-compress');

fis.config.set('settings.smarty.left_delimiter', '{');
fis.config.set('settings.smarty.right_delimiter', '}');

fis.config.merge({
    roadmap : {
        path : [
            {
		        reg : 'map.json',
		        release : 'ph_application/third_party/Smarty-3.1.11/configs/$&'
		    }
        ]
    }
});


