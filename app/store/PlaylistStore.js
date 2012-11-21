    Ext.define('NTV.store.PlaylistStore', {
	extend: 'Ext.data.Store',
	requires: ['Ext.data.proxy.JsonP'],
	//requires:"Ext.data.proxy.LocalStorage",
	config: {
		model: 'NTV.model.Playlist',
		autoLoad: true,
		proxy: {
            type: 'jsonp',
            url:'http://briteskills.com/ntvApp/NTVRssPlaylist.php',
            
            reader: {
                type         :'json',
                rootProperty :'playlists',
                //totalProperty: 'meta.total_count'
            }
        },
	}
});
