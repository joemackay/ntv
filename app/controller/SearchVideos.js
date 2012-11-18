Ext.define('NTV.controller.SearchVideos', {
    extend: 'Ext.app.Controller',    
    config: {
        refs: { //noteslistview
        	videoListContainer	: "videolistcontainer",
            videoList           : "videolist",
            searchBar           : "searchbar",
            searchField         : "searchbar #video_search_field",
            searchButton        : 'ntvtitlebar button[action=search]',
        },
        control: {
            searchBar : {
                goCommand : "onShowSearchResultsCommand",                 
            },
        },
    },
    
    launch: function () {
        this.callParent();        
    },
    init: function () {
        this.callParent();
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

    /**Go back home**/
    onBackToHomeCommand: function() {
    	this.activateNotesList();	
    },
    
    /**command load Show overlay**/
    onShowSearchResultsCommand: function () {
        var qr = Ext.getCmp('video_search_field').getValue();
            
	    var cont = this.getSearchBar();
	    Ext.Viewport.setMasked({
            xtype: 'loadmask',
            message: 'Loading videos',
            indicator: true,
        });
	    var me            = this,
            searchStore   = Ext.create('NTV.store.SearchVideosStore'),
            queryString   = qr,
            rUrl          = searchStore.getVideosURL(),
            newUrl        = rUrl + queryString;
        
        searchStore.getProxy().setUrl(newUrl);
        searchStore.load({
            callback: function() {
                Ext.Viewport.unmask();
                cont.setActiveItem(
                    {xtype: 'searchlist', store: searchStore, pTitle: queryString,}                    
                );                
            },
        });        
	}
});
//http://stackoverflow.com/questions/10840139/sencha-touch-2-accessing-config-values-while-creating-views?answertab=votes#tab-top
//login example: http://www.sencha.com/forum/showthread.php?205536-Pass-variable-from-one-panel-to-another-in-the-same-view