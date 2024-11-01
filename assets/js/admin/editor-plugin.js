(function(){
    tinymce.create('tinymce.plugins.Plwl', {
        init : function(ed, url)
        {
            ed.addCommand('plwl_shortcode_editor', function(){
                ed.windowManager.open(
                    {
                        file: ajaxurl + '?action=plwl_shortcode_editor',
                        width: 900 + parseInt(ed.getLang('button.delta_width', 0)),
                        height: 500 + parseInt(ed.getLang('button.delta_height', 0)),
                        inline: 1
                    }, {
                        plugin_url : url
                    });
                
            });

            var assets_url = url.split('assets/');

            ed.addButton('plwl_shortcode_editor', {title: 'Image Gallery', cmd : 'plwl_shortcode_editor', image: assets_url[0] + 'assets/images/pw-logo.jpg'});
        }
    });
    tinymce.PluginManager.add('plwl_shortcode_editor', tinymce.plugins.Plwl);
})();
