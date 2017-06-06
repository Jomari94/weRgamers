var socket = io.connect(listener);
socket.emit('join', username);
socket.emit('switchRoom', room);

var timeout;
var typing = false;
var usersTyping = [];

function stopTyping() {
    typing = false;
    socket.emit('stop typing');
}

$("#message-field").keyup(function(event){
    if(event.keyCode == 13){
        $("#send-button").click();
    }
});

$("#message-field").keydown(function(event){
    if (event.keyCode != 13 && !typing) {
        typing = true;
        socket.emit('typing');
        clearTimeout(timeout);
        timeout = setTimeout(stopTyping, 1500);
    }
});

$('#send-button').on('click', function(){
    if ($('#message-field').val() != '') {
        socket.emit('chat message', JSON.stringify({name: username, message: $('#message-field').val()}));
        $.ajax({
            url: urlSended,
            type: 'POST',
            data: {group: room, user: userId, message: $('#message-field').val()}
        });
        $('#message-field').val('');
    }
});

socket.on('chat message', function(msg){
    msg = JSON.parse(msg);
    if (msg.name == username) {
        $('#messages').append($('<div>')
            .html('<img class="img64 img-circle" src="' + avatar + '"><div class="chat-message-content"><p>' + msg.message + '</p><div>')
            .addClass('chat-message-view sender'));
    } else {
        var avatarUser = $('#' + msg.name).attr('src');
        $('#messages').append($('<div>')
            .html('<img class="img64 img-circle" src="' + avatarUser + '"><div class="chat-message-content"><p>' + msg.name + '</p><p>' + msg.message + '</p><div>')
            .addClass('chat-message-view nosender'));
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

socket.on('typing', function(user) {
    usersTyping.push(user);
    if (usersTyping.length == 1) {
        $('#typing').text(usersTyping[0] + mensajes.oneTyping);
    } else if (usersTyping.length > 1) {
        $('#typing').text(usersTyping[0] + mensajes.and + (usersTyping.length - 1) + mensajes.moreTyping);
    } else if (usersTyping.length == 0) {
        $('#typing').text('');
    }
});

socket.on('stop typing', function(user) {
    usersTyping.splice(usersTyping.indexOf(user), 1);
    if (usersTyping.length == 1) {
        $('#typing').text(usersTyping[0] + mensajes.oneTyping);
    } else if (usersTyping.length > 1) {
        $('#typing').text(usersTyping[0] + mensajes.and + (usersTyping.length - 1) + mensajes.moreTyping);
    } else if (usersTyping.length == 0) {
        $('#typing').text('');
    }
});

$(document).on('ready', function () {
    $('#message-field').focus();
    scrollToBottom();
});

function scrollToBottom() {
    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
}
