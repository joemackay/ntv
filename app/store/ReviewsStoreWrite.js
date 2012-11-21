Ext.define('NTV.store.ReviewsStore', {
	extend: 'Ext.data.Store',
	requires: ['Ext.data.proxy.JsonP'],
	//requires:"Ext.data.proxy.LocalStorage",
	config: {
		model: 'NTV.model.Video',
		autoLoad: true,
		proxy: {
            type: 'jsonp',
            url:'http://localhost/Notes/ntvApp/saveReviews.php',
            
            reader: {
                type:'json',
                rootProperty:'videos',
                //totalProperty: 'meta.total_count'
            }
        },        
	}
});
