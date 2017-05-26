var socket = io.connect(listener);
socket.emit('join', username);
socket.emit('switchRoom', room);

$("#message-field").keyup(function(event){
    if(event.keyCode == 13){
        $("#send-button").click();
    }
});

$('#send-button').on('click', function(){
    if ($('#message-field').val() != '') {
        socket.emit('chat message', JSON.stringify({name: username, message: $('#message-field').val()}));
        $('#message-field').val('');
    }
});

socket.on('chat message', function(msg){
    msg = JSON.parse(msg);
    if (msg.name == username) {
        $('#messages').append($('<div>').html('<img class="img64 img-circle" src="' + avatar + '"><p>' + msg.message + '</p>').addClass('message-view sender'));
    } else {
        var avatarUser = $('#' + msg.name).attr('src');
        $('#messages').append($('<div>').html('<img class="img64 img-circle" src="' + avatarUser + '"><p>' + msg.message + '</p>').addClass('message-view nosender'));
    }
    scrollToBottom();
});

socket.on('joined', function(msg){
    $('#messages').append($('<div>').text(msg + ' se ha conectado').addClass('chat-member-status alert alert-success'));
    $('.member-status.' + msg).css({
        backgroundColor: '#00ff00'
    });
    scrollToBottom();
});

socket.on('leave', function(msg){
    $('#messages').append($('<div>').text(msg + ' se ha desconectado').addClass('chat-member-status alert alert-danger'));
    $('.member-status.' + msg).css({
        backgroundColor: '#ff0000'
    });
    scrollToBottom();
});

socket.on('people connected', function(connected){
    connected = JSON.parse(connected);
    connected.forEach(function(item, index) {
        $('.member-status.' + item).css({
            backgroundColor: '#00ff00'
        });
    });
});

$(document).on('ready', function () {
    $('#message-field').focus();
});

function scrollToBottom() {
    $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
}
