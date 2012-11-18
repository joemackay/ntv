Ext.define('NTV.store.SearchVideosStore', {
	extend  : 'Ext.data.Store',
	requires: ['Ext.data.proxy.JsonP'],
	config  : {
		model     : 'NTV.model.Video',
		videosURL : 'http://briteskills.com/gdata/NTVRssSearchVideos.php?sc=',
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
