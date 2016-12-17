$(function(){
    'use strict';

    var
        MESSAGES_URL                = "/messages",
        ACTIVITIES_URL              = "/activities",
        MESSAGES_UPDATE_INTERVAL    = 5000;

    var Request = (function() {

        function Request() {

        }

        Request.prototype.get = function(url, callbackDone, callbackFail) {
            $.ajax({
                method: "GET",
                url: url,
                cache: false,
                dataType: "json"
            })
            .done(function(data) {
                callbackDone(data);
            })
            .fail(function() {
                callbackFail();
            });
        };

        Request.prototype.post = function(url, data, callbackDone, callbackFail) {
            $.ajax({
                method: "POST",
                url: url,
                data: data,
                cache: false,
                dataType: "json"
            })
            .done(function(data) {
                callbackDone(data);
            })
            .fail(function() {
                callbackFail();
            });
        };

        return Request;
    })();

    var request = new Request();

    function successGetMessage(response) {
        var text = '<ul>';

        for(var i = 0; i < response.length; i++){
            var role = response[i].role === 'sender' ? 'sender' : 'receiver';

            text +=
                '<li class="' + role + '">' +
                    '<div class="clearfix">' +
                        '<h4 class="m-top-zero pull-left text-primary">' +
                            '<span>' + (role === 'sender' ? 'To: ' : 'From: ') + '</span>' + response[i].name +
                        '</h4>' +
                    '</div>' +
                    '<p class="message-body">' + response[i].text + '</p>' +
                    '<div class="clearfix">' +
                        '<strong class="pull-right"><small><u>' + response[i].sent_on + '</u></small></strong>' +
                    '</div>' +
                '</li>';
        }

        text += '</ul>';

        $('#messsage-container-inner').html(text);
    }

    function errorGetMessage() {
        var error = '<p class="text-danger">There was an error while fetching messages.</p>';
        $('#messsage-container-inner').html(error);
    }

    function getMessages(){
        request.get(MESSAGES_URL, successGetMessage, errorGetMessage);
    }

    getMessages();
    setInterval(getMessages, MESSAGES_UPDATE_INTERVAL);

    function successGetActivities(response){
        console.log(response);
        var text = '<ul>';

        for(var i = 0; i < response.length; i++){
            text +=
                '<li class="' + (response[i].type === 'victory' ? 'victory' : 'loss') + '">' +
                    '<div class="clearfix">' +
                        '<h4 class="m-zero m-right-xs pull-left">' + response[i].name + '</h4>' +
                        '<strong class="pull-right"><small>' + response[i].time + '</small></strong>' +
                    '</div>' +
                '</li>';
        }

        text += '</ul>';

        $('#log-container-inner').html(text);
    }

    function errorGetActivities() {
        var error = '<p class="text-danger">There was an error while fetching last activities.</p>';
        $('#log-container-inner').html(error);
    }

    $('.btn-message').click(function() {
        $('#messages-container').toggleClass('open');
    });

    $('.btn-log').click(function() {
        request.get(ACTIVITIES_URL, successGetActivities, errorGetActivities);
        $('#log-container').toggleClass('open');
    });
});