var $notification = $('div#notification');
var $notificationParagraph = $('p#notification-text');

function notify(message){
    $notificationParagraph.html(message);
    $notification.fadeIn(500, function(){
        $(this).delay(1000).fadeOut(500);
    });
}