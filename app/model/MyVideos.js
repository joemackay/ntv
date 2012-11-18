Ext.define('NTV.model.Videos', {
    extend: 'Ext.data.Model',
    config: {
        idProperty : 'id',
        fields: [
            {   name: 'id',             type: 'int' },
            {   name: 'videoid',        type: 'int' },
            {   name: 'dateCreated',    type: 'date',    dateFormat: 'c' },
            {   name: 'title',          type: 'string' },
            {   name: 'desccription',   type: 'string' },
            {   name: 'url',            type: 'string' },
            {   name: 'thumb',          type: 'string' },
        ],
        validations: [
            { type: 'presence', field: 'id' },
            { type: 'presence', field: 'dateCreated' },
            { type: 'presence', field: 'title', message: 'Please enter a title for this note.' }
        ]
    }    
});