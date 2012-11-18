Ext.define('NTV.controller.User', {
    extend: 'Ext.app.Controller',
    config: {
        models: ['User'],
        currentUser: {
            id: -1
        },
        views: ['VideoListContainer']
    },
    launch: function() {
        var me = this;
        NTV.model.User.load(1, {
            success: function(rec) {
                var id         = rec.get('id'),
                    app        = me.getApplication(), 
                    videoListController = app.getController('VideoList');
                if (id == -1) {
                    videoListController.showLoginPage();
                } else {
                    me.setCurrentUser({
                        id: id
                    });
                    //videoListController.decideWhatToDo();
                    //app.getController('Find').initializeSettings();
                }
            }
        });
    }
});
