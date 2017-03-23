var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "application/x-httpd-php",
    indentUnit: 4,
    indentWithTabs: true,
    enterMode: "keep",
    tabMode: "shift"
});

var sendForm = function() {
    editor.save();

    var timeStart = new Date().getTime();

    $.ajax(
        '/eval',
        {
            type: 'post',
            dataType: 'json',
            data: {
                'code': $('#code').val()
            },
            beforeSend: function () {
                $('#loader').fadeIn();
                $('#result').addClass('text-muted');


            },
            complete: function (jqXHR, textStatus) {
                var timeDiff = new Date().getTime() - timeStart;

                setTimeout(function() {
                    $('#loader').fadeOut();

                    if (textStatus == 'success') {
                    }
                    $('#result')
                        .html(jqXHR.responseJSON.output)
                        .removeClass('text-muted');

                    $('.output').fadeIn();
                }, 500 - timeDiff);
            }
        }
    );
};

var saveForm = function() {
    editor.save();

    var timeStart = new Date().getTime();

    $.ajax(
        '/save',
        {
            type: 'post',
            dataType: 'json',
            data: {
                'code': $('#code').val()
            },
            beforeSend: function () {
                $('#loader').fadeIn();
            },
            complete: function (jqXHR, textStatus) {
                var timeDiff = new Date().getTime() - timeStart;

                setTimeout(function() {
                    $('#loader').fadeOut();

                    if (textStatus == 'success') {
                        var id = jqXHR.responseJSON.result;
                        $('#codeID').text(id);
                        document.location.href = '?#' + id;
                    }
                }, 500 - timeDiff);
            }
        }
    );
};

var id = document.location.href.split('?#',2)[1];
$('#codeID').text(id);
if (id) {
    var timeStart = new Date().getTime();

    $.ajax(
        '/load/' + id,
        {
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $('#loader').fadeIn();
            },
            complete: function (jqXHR, textStatus) {
                var timeDiff = new Date().getTime() - timeStart;

                setTimeout(function() {
                    $('#loader').fadeOut();

                    if (jqXHR.responseJSON.success) {
                        $('#codeID').text(id);
                        editor.setValue(jqXHR.responseJSON.code);
                    }
                    else {
                        $('#codeID').text(id + ' not found');
                    }
                }, 500 - timeDiff);
            }
        }
    );
}

var data = browserDetection();
var os = data.os;
$('body').attr('data-os', os);

if (os == 'osx') {
    KeyboardJS.on('command + enter', function() { sendForm(); });
    KeyboardJS.on('command + s', function() { saveForm(); return false; });

    $('.button-hotkey[data-os="windows"]').hide();
    $('.button-hotkey[data-os="mac"]').show();
}
else if (os == 'win' || os == 'linux') {
    KeyboardJS.on('ctrl + enter', function() { sendForm(); });
    KeyboardJS.on('ctrl + s', function() { saveForm(); return false; });
}

$('[data-toggle=tooltip]').tooltip();