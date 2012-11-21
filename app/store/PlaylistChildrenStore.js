Ext.define('NTV.store.PlaylistChildrenStore', {
	extend  : 'Ext.data.Store',
	requires: ['Ext.data.proxy.JsonP'],
	config  : {
		model     : 'NTV.model.Video',
		videosURL : 'http://briteskills.com/ntvApp/NTVRssPlaylistDetails.php?playlistId=',
		proxy     : {
            type    : 'jsonp',
            url     : this.videosURL + '0',
            reader  : {
                type         :'json',
                rootProperty :'videos',
                //totalProperty: 'meta.total_count'
            }
        },        
	}
});
