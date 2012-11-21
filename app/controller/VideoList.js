Ext.define('NTV.controller.VideoList', {
    extend: 'Ext.app.Controller',
    requires : ['Ext.data.JsonP'],
    config: {
        refs: { //noteslistview
            videoListContainer  : "videolistcontainer",
            searchBar           : "searchbar",
            ntvTitleBar         : 'ntvtitlebar',
            aboutPage           : 'aboutpage',
            registerPage        : 'registerpage',
            loginPage           : 'loginpage',
            overlayMenu    : 'overlaymenupanel',
        },
        control: {
            'videolistcontainer #videosList' : {        //on the home page list
                        itemtap : 'onShowOverlayCommand'
            },
            'videolistcontainer #pVideosList' : {       //when you click on play list children
                        itemtap : 'onShowOverlayCommand'
            },
            'searchbar #searchResults' : {       //when you click on search results
                        itemtap : 'onShowOverlayCommand'
            },
            'videolistcontainer #playList' : {
                        itemtap : 'onShowPlaylistChildrenCommand' //when you click on play list 
            },
            'videolistcontainer #searchResults' : {
                        itemtap : 'onShowOverlayCommand'
            },
            videoListContainer  :  {
                    onMenuCommand : 'showMenuOverlay',
                    searchCommand : 'activateSearchBar',
                    onRefreshCommand: 'onRefreshListCommand',
            },
            searchBar   : {
                backToHomeCommand   : "onBackToHomeCommand",                 
            },
            aboutPage   : {
                backToHomeCommand: 'onBackToHomeCommand',
            },
            'overlaymenupanel' : {
                showAboutCommand     : 'showAboutPage',
                registerButtonCommand: 'showRegisterPage',
                loginButtonCommand   : 'showLoginPage',
                //ExitButtonCommand   : '',
            },
            'loginpage' : {
                backToHomeCommand: 'onBackToHomeCommand',
            },            
            'registerpage' : {
                backToHomeCommand: 'onBackToHomeCommand',
            },
            registerPage: {
                saveUserCommand   : "registerUser",
            },
            loginPage: {
                LoginUserCommand   : "onLoginUser",
            },
                        
        }
    },
    
    launch: function () {
        this.callParent();
        Ext.getStore("ListVideosStore").load();
        Ext.getStore("PlaylistStore").load();
    },
    init: function () {
        this.callParent();
    },
    handleOrientationChange : function(e) {
        var qr = Ext.getCmp('playerCont'), video;
        var orientation = Ext.Viewport.getOrientation();
        if(orientation == "landscape") {
            video = '<div id="video1"><iframe width="480" height="320" src="http://www.youtube.com/embed/'+ this.config.videoId +'?fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        } else if(orientation == "portrait"){
            video = '<div id="wrong-orientation">Please rotate your phone to view this video</div>';  
        } 
        qr.setHtml(video);        
    },
    
    showHomeScreenToolbar: function() {
        this.getApplication().getController('NtvTitleBar').enableHomeView();
    },
    
    /**slide transitions**/
    slideLeftTransition: { 
        type: 'slide', direction: 'left' 
    },
    slideRightTransition: { 
        type: 'slide', direction: 'right' 
    },
    
    /**sliding in a home page**/
    activateNotesList: function() {
        Ext.Viewport.animateActiveItem(this.getVideoListContainer(), this.slideRightTransition);
    },                                                                     
    activateSearchBar: function () {
        var searchBar = this.getSearchBar();
        //searchBar.setRecord(record); // load() is deprecated.
        Ext.Viewport.animateActiveItem(searchBar, this.slideLeftTransition);
    },

    showAboutPage: function () {
        console.log('showAboutPage');
        var me = this;
        //me.hide();
        var abtpage = this.getAboutPage();
        Ext.Viewport.animateActiveItem(abtpage, this.slideLeftTransition);
    },

    showRegisterPage: function () {
        var rgpage = this.getRegisterPage();
        Ext.Viewport.animateActiveItem(rgpage, this.slideLeftTransition);
    },

    showLoginPage: function () {
        var lgPage = this.getLoginPage();
        Ext.Viewport.animateActiveItem(lgPage, this.slideLeftTransition);
    },

    /**Go back home**/
    onBackToHomeCommand: function() {
        this.activateNotesList();   
    },
    
    /**command load Show overlay**/
    onShowOverlayCommand: function(me, index, target, record, e, eOpts) { 
        var me = this;
        me.getApplication().getController('ViewVideo').setCurrentId(index);
        me.getApplication().getController('ViewVideo').setCurrentStore('ListVideosStore');
        //this.getApplication().getController('ViewVideo').setCurrentStore(Ext.getStore);
        var popup = Ext.create('NTV.view.Overlay', {
            videoId          : record.get('videoid'),
            videoTitle       : record.get('title'),
            introText        : record.get('intro'),
            videoDescription : record.get('description'),
            videoThumbnail   : record.get('thumbnail'),
        });
        
        Ext.Viewport.add([popup]);
        popup.show();       
    },
    /**command load Show overlay**/
    showMenuOverlay: function(me) { 
        var me = this;
        //this.getApplication().getController('ViewVideo').setCurrentStore(Ext.getStore);
        var popup = Ext.create('NTV.view.OverlayMenu');
        
        Ext.Viewport.add([popup]);
        popup.show();       
    },
    onShowPlaylistChildrenCommand: function (me, index, target, record, e, eOpts) {
        var cont = this.getVideoListContainer();
        //this.getApplication().getController('MeetchaTitleBar').enableHomeView();
        this.getApplication().getController('ViewVideo').setCurrentStore('PlaylistChildrenStore');
        Ext.Viewport.setMasked({
            xtype: 'loadmask',
            message: 'Loading videos',
            indicator: true,
        });
        var me            = this,
            videosStore   = Ext.create('NTV.store.PlaylistChildrenStore'),
            videoId       = record.get('playlistid'),
            playlistTitle = record.get('title'),
            rUrl          = videosStore.getVideosURL(),
            newUrl        = rUrl + videoId;
        
        videosStore.getProxy().setUrl(newUrl);
        videosStore.load({
            callback: function() {
                Ext.Viewport.unmask();
                cont.setActiveItem(
                    {xtype: 'pvideolist', store: videosStore, pTitle: playlistTitle,}
                );
                //Ext.Viewport.animateActiveItem(cont.add({ xtype: 'videolist', store: videosStore}).show(), this.slideRightTransition);
            }
        });        
    },
    onRefreshListCommand: function (me, index, target, record, e, eOpts) {
        var cont = this.getVideoListContainer();
        //this.getApplication().getController('MeetchaTitleBar').enableHomeView();
        this.getApplication().getController('ViewVideo').setCurrentStore('ListVideosStore');
        Ext.Viewport.setMasked({
            xtype: 'loadmask',
            message: 'Loading videos',
            indicator: true,
        });
        var me            = this,
            videosStore   = Ext.create('NTV.store.ListVideosStore');//,
        
        videosStore.load({
            callback: function() {
                Ext.Viewport.unmask();
                cont.setActiveItem(
                    {xtype: 'videolist', store: videosStore}
                );
            }
        });        
    },
    /**saving an item - especially videos I like**/
    registerUser : function() {
        var regForm  = this.getRegisterPage();
        var newValues     = regForm.getValues();
        var currentRecord = regForm.getRecord();        
        var me = this;
        if(newValues.email && newValues.password) {
            Ext.Viewport.setMasked({
                xtype: 'loadmask',
                message: 'Sending data...',
                indicator: true,
            });
            Ext.data.JsonP.request({
                //url: 'http://briteskills.com/ntvApp/registerUser.php',
                url: 'http://localhost/Notes/ntvApp/registerUser.php',
                disableCaching: false,
                callbackKey: 'callback',
                params: {
                    email: newValues.email,
                    password: newValues.password,
                    password_conf: newValues.password_conf,
                },
                success: function(result) {
                    Ext.Viewport.unmask();
                    if(result.responseText !='-1') {
                        Ext.Msg.alert('Success', result.responseText);
                        //set user id from result and call loggedin class
                        regForm.setValues('');
                    } else {
                        Ext.Msg.alert('Failure', "Sorry registration was not possible");
                    }
                },
                failure: function(result) {
                    Ext.Viewport.unmask();
                    Ext.Msg.alert('Error ' + 'There was an error, please try again later');
                }
            });
            
        } else {
            Ext.Msg.alert('Please enter Email and Password.');
        }    
        
    },

    onLoginUser: function() {
        var loginForm = this.getLoginPage();
        var newValues = loginForm.getValues();
        var me = this;
        if(newValues.email && newValues.password) {
            Ext.Viewport.setMasked({
                xtype: 'loadmask',
                message: 'Logging in...',
                indicator: true,
            });
            Ext.data.JsonP.request({
                //url: 'http://briteskills.com/ntvApp/registerUser.php',
                url: 'http://localhost/Notes/ntvApp/loginUser.php',
                disableCaching: false,
                callbackKey: 'callback',
                params: {
                    email: newValues.email,
                    password: newValues.password,
                },
                success: function(result) {
                    Ext.Viewport.unmask();
                    if(result.responseText !='-1') {
                        Ext.Msg.alert('Success', result.responseText);
                        //set user id from result and call loggedin class
                        loginForm.setValues('');
                    } else {
                        Ext.Msg.alert('Failure', "Sorry registration was not possible");
                    }
                },
                failure: function(result) {
                    Ext.Viewport.unmask();
                    Ext.Msg.alert('Error ' + 'There was an error, please try again later');
                }
            });
            
        } else {
            Ext.Msg.alert('Please enter Username, Email and Password.');
        }
    }
});
//http://stackoverflow.com/questions/10840139/sencha-touch-2-accessing-config-values-while-creating-views?answertab=votes#tab-top
//login example: http://www.sencha.com/forum/showthread.php?205536-Pass-variable-from-one-panel-to-another-in-the-same-view