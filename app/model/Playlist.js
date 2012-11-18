Ext.define('NTV.model.Playlist', {
    extend: 'Ext.data.Model',
    config: {
        idProperty : 'id',
        fields: [
            {   name: 'id',             type: 'int' },
            {   name: 'playlistid',     type: 'string' },
            {   name: 'title',          type: 'string' },
            {   name: 'size',           type: 'int' },
            {   name: 'thumbnail',      type: 'string' },
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