/**
 * Created by Resmedia on 28.08.16.
 */

CKEDITOR.on('instanceReady', function (ev) {
    ev.editor.on('paste', function (evt) {
        evt.data.dataValue = stripTags(evt.data.dataValue, // Clean all. Tag allowed list
            '<i>' +
            '<s>' +
            '<em>' +
            '<b>' +
            '<p>' +
            '<br>' +
            '<hr>' +
            '<ul>' +
            '<li>' +
            '<ol>' +
            '<a>' +
            '<td>' +
            '<tr>' +
            '<div>' +
            '<table>' +
            '<tbody>' +
            '<thead>' +
            '<strong>' +
            '<blockquote>'
        );
        evt.data.dataValue = evt.data.dataValue.replace(/&nbsp;/g, ' '); // remove spaces &nbsp
        evt.data.dataValue = evt.data.dataValue.replace(/<p><\/p>/g, '<br/>'); // Replace empty <p> to <br/>
        evt.data.dataValue = evt.data.dataValue.replace(/style=*/g, ''); // Remove all styles
        evt.data.dataValue = evt.data.dataValue.replace(/align=*/g, ''); // Remove all algins
        evt.data.dataValue = evt.data.dataValue.replace(/height=*/g, ''); // Remove all height
        evt.data.dataValue = evt.data.dataValue.replace(/width=*/g, ''); // Remove all width
    }, null, null, 9);
});

CKEDITOR.editorConfig = function (config) {
    config.allowedContent = true;
    config.disallowedContent = 'span';
    config.removeFormatTags = 'span;';
    config.uiColor = '#f2f2f2';
    config.scayt_autoStartup = false;
    config.format_tags = 'h3;h4;h5;pre';
    config.toolbarCanCollapse = true;

    config.toolbar = [
        {name: 'tools', items: ['Source', 'Maximize', 'RemoveFormat', 'ShowBlocks', 'Find', '-', 'Undo', 'Redo']},
        {
            name: 'clipboard',
            groups: ['clipboard', 'undo'],
        },
        {name: 'links', items: ['Link', 'Unlink', 'Anchor', '-', 'Image']},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar']},

        {
            name: 'basicstyles',
            groups: ['basicstyles', 'cleanup'],
            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-']
        },
        {
            name: 'paragraph',
            groups: ['list', 'indent', 'blocks', 'align'],
            items: [
                'JustifyLeft', 'JustifyCenter',
                'JustifyRight', 'JustifyBlock', '-',
                'NumberedList',
                'BulletedList', '-',
                'Outdent', 'Indent', '-',
                'Blockquote', 'CreateDiv', '-',
            ]
        },

        {name: 'styles', items: ['Format', 'FontSize']},
        {name: 'colors', items: ['TextColor', 'BGColor']},
    ];
};

function stripTags(input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
    let tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}