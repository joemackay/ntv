Ext.define('NTV.model.Video', {
    extend: 'Ext.data.Model',
    config: {
        idProperty : 'id',
        fields: [
            {   name: 'id',             type: 'int' },
            {   name: 'videoid',        type: 'string' },
        	{   name: 'title',          type: 'string' },
            {   name: 'intro',          type: 'string' },
            {   name: 'description',    type: 'string' },
            {   name: 'views',          type: 'string' },
            {   name: 'duration',       type: 'string' },
            {   name: 'url',            type: 'string' },
            {	name: 'thumbnail',      type: 'string' },
        ],
        validations: [
	        { type: 'presence', field: 'id' },
            { type: 'presence', field: 'title', message: 'Please enter a title for this note.' }
        ]
    },
    description: function() {
        var d = this.data,
        names = [
            d.firstname,
            (!d.middlename? "" :d.middlename),
            d.lastname
        ];
        return names.join("");
        
    }    
});