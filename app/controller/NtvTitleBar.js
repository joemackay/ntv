Ext.define('Notes.controller.NtvTitleBar', {
    extend: 'Ext.app.Controller',
    config: {
        stores: ['Notes.store.ListVideosStore'],
        refs: {
            'homeScreen': 'homescreen',
            'ntvTitleBar': 'ntvtitlebar',
            'homeButton': 'ntvtitlebar button[action=home]',
            'latestButton': 'ntvtitlebar button[action=latest]',
            'playlistButton': 'ntvtitlebar button[action=playlist]',
            'previousButton': 'ntvtitlebar button[action=previous]',
            'nextButton': 'ntvtitlebar button[action=next]',
            'playvideoButton': 'ntvtitlebar button[action=playvideo]',
            'descriptionButton': 'ntvtitlebar button[action=description]',
            'reviewsButton': 'ntvtitlebar button[action=reviews]',
            'backButton': 'ntvtitlebar button[action=back]',
            'searchButton': 'ntvtitlebar button[action=search]',
            'exitButton': 'ntvtitlebar button[action=exit]',
        },
        control: {
            'homeButton': {
                tap: 'onHomeClick'
            },
            'latestButton': {
                tap: 'onLatestClick'
            },
            'playlistButton': {
                tap: 'onPlaylistClick'
            },
            'previousButton': {
                tap: 'onPreviousClick'
            },
            'nextButton': {
                tap: 'onNextClick'
            },
            'playvideoButton': {
                tap: 'onPlayVideoClick'
            },
            'descriptionButton': {
                tap: 'onDescriptionClick'
            },
            'reviewsButton': {
                tap: 'onReviewsClick'
            },
            'backButton': {
                tap: 'onHomeClick'
            },
            'searchButton': {
                tap: 'onSearchButtonClick'
            },
            'exitButton': {
                tap: 'onExitButton'
            },
        }
    },
    /**
     * Handle the home button being clicked.
     */
    onHomeClick: function(loginSuccess) {
        var me = this;
        me.getApplication().getController('HomeScreen').showHomeScreen();
    },

    onLatestClick: function() {//show latest videos page
        
    },
    
    onPlaylistClick: function() {//show playlist videos page
        //console.log('showPlaylistCommand');  
        //this.fireEvent('showPlaylistCommand');
    },
    
    onPreviousClick: function() {//show previous page
        
    },
    
    onNextClick: function() {//show next page
        
    },
    
    onPlayVideoClick: function() {//show play video page
        
    },
    
    onDescriptionClick: function() {//show video description page
        
    },
    
    onBackButtonClick: function() {//go back to previous page
        
    },
    
    onSearchButtonClick: function() {//show search page
        
    },
    
    onExitButton: function() {
        
    },
    
    enableHomeView: function() {
        var me = this;

        me.getPreviousButton().hide();
        me.getPlayvideoButton().hide();
        me.getDescriptionButton().hide();
        me.getReviewsButton().hide();
        me.getNextButton().hide();
        me.getSearchButton().hide();
        me.getBackButton().hide();
        me.getLatestButton().show();
        me.getPlaylistButton().show();
        me.getExitButton().show();
        
        //me.getMeetchaTitleBar().setTitle('NTV Videos');
    },
    
    enableListView: function() {
        var me = this;

        me.getPreviousButton().hide();
        me.getPlayvideoButton().hide();
        me.getDescriptionButton().hide();
        me.getReviewsButton().hide();
        me.getNextButton().hide();
        me.getSearchButton().hide();
        me.getBackButton().hide();
        me.getLatestButton().show();
        me.getPlaylistButton().show();
        me.getExitButton().show();
        
        //me.getMeetchaTitleBar().setTitle('NTV Videos');
    },
    
    /*
     * Update the titlebar to show appropriately for single video view
     */
    enableVideoView: function(title) {
        var me = this;
        me.getPreviousButton().show();
        me.getPlayvideoButton().show();
        me.getDescriptionButton().show();
        me.getReviewsButton().show();
        me.getNextButton().show();
        me.getBackButton().show();
        me.getSearchButton().show();
        me.getLatestButton().hide();
        me.getPlaylistButton().hide();
        me.getNtvTitleBar().setTitle(title);
         
    },
    /*
     * Update the titlebar to show appropriately for search view
     */
    enableSearchView: function(title) {
        var me = this;
        
        me.getBackButton().show();
        me.getSearchButton().show();
        me.getPreviousButton().hide();
        me.getPlayvideoButton().hide();
        me.getDescriptionButton().hide();
        me.getReviewsButton().hide();
        me.getNextButton().hide();
        me.getLatestButton().hide();
        me.getPlaylistButton().hide();
        me.getNtvTitleBar().setTitle(title); 
    },

    /*
     * Handles the map button being clicked.
     */
    onMapClick: function() {
        var me = this;
        me.getMapButton().hide();
        me.getListButton().show();
        me.getApplication().getController('Map').onMapButtonClick();
    },
    /**
     * Handles the find button being clicked.
     */
    onFindClick: function() {
        var me = this;
        me.resetToListState();
        me.getApplication().getController('Find').onFindClick();
    },
    /*
     * Reset to list state.
     */
    onListClick: function() {
        this.resetToListState();
    },

    onLogoutButtonClick: function() {
        var me = this;
        me.getMeetchaTitleBar().hide();
        Ext.Viewport.removeAll();
        Ext.Viewport.add({
            fullscreen: true,
            xtype: 'panel',
            items: [{
                xtype: 'toolbar',
                docked: 'top',
                title: 'Logout',
                items: [{
                    text: 'back',
                    ui: 'back',
                    handler: this.onHomeClick,
                    scope: this
                }]
            }, {
                padding: 15,
                html: 
                '<b>Leaving so soon?</b><br/><br/>' + 
                'To fully logout you must leave Meetcha and proceed directly to Meetup.com<br/><br/>' + 
                'After clicking below if you wish to continue using Meetcha please exit and relaunch the application<br/><br/>' + 
                '<a class="logout-link" id="logout-link-id">Logout</a>'
            }]
        }).show();
        Ext.get('logout-link-id').on('tap', function() {
            Ext.Viewport.setMasked({
                xtype: 'loadmask',
                message: 'Clearing Authentication & Logging Out'
            });
            Ext.data.JsonP.request({
                url: serverUrl + 'php/logout.php',
                success: function() {
                    Ext.getBody().createChild({
                        tag: 'iframe',
                        src: 'http://meetup.com/logout/',
                        height: '0%',
                        width: '0%'
                    });
                    Ext.Function.defer(function() {
                        window.location.reload();
                    }, 5000);
                }
            });
        });
    },
    resetToListState: function() {
        var me = this;
        me.getMapButton().show();
        me.getListButton().hide();
        me.getApplication().getController('HomeScreen').goBackToLastState();
    },
    /*
     * When the upcoming meetup button is pressed.
     */
    onAtMeetupButton: function() {
        this.getApplication().getController('HomeScreen').showNextMeetup();
    }
});
