Ext.define('NTV.store.ListVideosStore', {
	extend: 'Ext.data.Store',
	requires: ['Ext.data.proxy.JsonP'],
	//requires:"Ext.data.proxy.LocalStorage",
	config: {
		model     : 'NTV.model.Video',
		autoLoad  : true,
		proxy     : {
            type    : 'jsonp',
            url     :'http://briteskills.com/ntvApp/NTVRss.php',
            reader  : {
                type:'json',
                rootProperty:'videos',
                //totalProperty: 'meta.total_count'
            }
        },
	}
});
