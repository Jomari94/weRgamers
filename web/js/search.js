$('#messages-link').on('click', function () {
    var ventana = open(urlConversations, "ventana", "width=600,height=640,toolbar=0,titlebar=0");
});

$('#notifications-link').on('click', function () {
    $("#modal").modal("show");
    $.ajax({
        method: 'post',
        url: urlNotifications,
    });
});

var users = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: urlUsers,
        wildcard: '%QUERY'
    }
});

var games = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: urlGames,
        wildcard: '%QUERY'
    }
});

$('.typeahead').typeahead({
    hint: true,
    minLength: 1,
}, {
    name: 'users',
    source: users,
    displayKey: 'username',
    limit: 10,
    templates: {
        header: '<h4 class="name">Users</h4>',
        // pending: '<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>',
        suggestion: function(data) {
            html = '<div class="media">';
            html += '<div class="media-left"><a href ="' + userLink + data.id + '"><img class="img-suggestion img-rounded media-object" src=' + data.avatar + ' /></a></div>'
            html += '<div class="media-body">';
            html += '<p class="media-heading"><a href ="' + userLink + data.id + '">' + data.username + '</a></p>';
            html += '<div class="user-search-view row"><span class="col-xs-5">' + data.karma + ' Karma</span><span class="col-xs-7">' + data.followers + ' Followers</span></div>';
            html += '</div></div>';
            return html;
        }
    }
}, {
    name: 'games',
    source: games,
    displayKey: 'name',
    limit: 10,
    templates: {
        header: '<h4 class="name">Games</h4>',
        suggestion: function(data) {
            html = '<div class="media">';
            html += '<div class="media-left"><a href ="' + gameLink + data.id + '"><img class="img-suggestion media-object" src=' + data.cover + ' /></a></div>'
            html += '<div class="media-body">';
            html += '<p class="media-heading"><a href ="' + gameLink + data.id + '">' + data.name + '</a></p>';
            html += '<div class="user-search-view row"><span class="col-xs-5">Score: ' + data.score + '</span><span class="col-xs-7">' + data.reviews + ' Reviews</span></div>';
            html += '</div></div>';
            return html;
        }
    }
});
