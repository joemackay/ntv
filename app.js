//<debug>
Ext.Loader.setPath({
    'Ext': 'sdk/src'
});
//</debug>

Ext.application({
    name        : 'NTV',
    controllers : ["VideoList", "ViewVideo", "SearchVideos", "User"],
    requires    : ['Ext.MessageBox'],
    models      : ["Video", "Playlist"],
    views       : ["About", "VideoListContainer", "VideoList", "PlayList", "ViewVideo", "Overlay", "OverlayComment", "OverlayMenu", "OverlaySocial", "PlayListChildren", "SearchBar", "SearchList", "Register", "Login"],
    stores      : ["ListVideosStore", "PlaylistStore", "PlaylistChildrenStore", "SearchVideosStore"],
    icon        : {
        57  : 'resources/icons/Icon.png',
        72  : 'resources/icons/Icon~ipad.png',
        114 : 'resources/icons/Icon@2x.png',
        144 : 'resources/icons/Icon~ipad@2x.png'
    },
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();
		var videoListContainer = {
            xtype: "videolistcontainer"
        };
        var videoList = {
            xtype: "videolist"
        };
        var playList = {
            xtype: "playlist"
        };
        var overlayPanel = {
			xtype: "overlaypanel"
		};
        var viewVideo = {
            xtype: "viewvideo"
        };
        var playlistChildren = {
            xtype: "playlistchildren"
        };
        var searchBar = {
            xtype: "searchbar"
        };
        var aboutPage = {
            xtype: "aboutpage"
        }
        var regPage = {
            xtype: "registerpage"
        }
        var loginPage = {
            xtype: "loginpage"
        }
        // Initialize the main view
        Ext.Viewport.add([videoListContainer, playList, videoList, viewVideo, playlistChildren, searchBar, aboutPage, loginPage, regPage]);
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function() {
                window.location.reload();
            }
        );
    }
});
