Ext.define('NTV.model.User', {
    extend: 'Ext.data.Model',
    config: {
        idProperty: 'id',
        fields: [{
            name: 'id',
            type: 'string'
        }],
        proxy: {
            type: 'jsonp',
            url: 'http://localhost/Notes/php/checkUser.php',
            reader: {
                type: 'json',
                rootProperty: 'results'
            }
        }
    }
});
