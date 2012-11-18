Ext.define('NTV.model.VideoComment', {
    extend: 'Ext.data.Model',
    config: {
        idProperty : 'id',
        fields: [{
            name: 'video_comment_id',
            type: 'string'
        }, {
            name: 'video_id',
            type: 'string'
        }, {
            name: 'user_id',
            type: 'string'
        }, {
            name: 'user_name',
            type: 'string'
        }, {
            name: 'comment',
            type: 'string'
        }, {
            name: 'time',
            type: 'date',
            convert: function(meetupCommentTime) {
                return new Date(meetupCommentTime);
            }
        }],
        validations: [
            { type: 'presence', field: 'id' },
            { type: 'presence', field: 'user_name' },
            { type: 'presence', field: 'comment', message: 'Please enter a comment.' }
        ],
    }    
});