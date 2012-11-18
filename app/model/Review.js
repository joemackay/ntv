Ext.define('NTV.model.Review', {
    extend: 'Ext.data.Model',
    config: {
        idProperty : 'id',
        fields: [
            {   name: 'id',             type: 'int' },
            {   name: 'videoid',        type: 'string' },
        	{   name: 'comments',       type: 'string' },
        ],
        validations: [
	        { type: 'presence', field: 'id' },
            { type: 'presence', field: 'comments', message: 'Please enter comments for this video.' }
        ]
    }    
});