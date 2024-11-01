function tinyplugin() {
    return "[stencies-plugin]";
}

(function() {

    tinymce.create('tinymce.plugins.stenciesplugin', {

        init : function(ed, url){
            ed.addButton('stenciesplugin', {
                title : 'Insert Stencie from stencies.com',
                onclick : function() {
					document.forms.post.stencies_btn.click();
                },
                image: url + "/stencies_button.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Stencies button plugin',
                author : 'Henne Minnie',
                authorurl : 'http://stencies.com',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('stenciesplugin', tinymce.plugins.stenciesplugin);
    
})();