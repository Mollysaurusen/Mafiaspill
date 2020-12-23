/*
* TODO: Will this be a legitimate feature in the game and should it stay here or delete it?
*  While it would be handy to quickly rightclick and send a message, enter profile or other things, will this be
*  actively used?
* */
$(document).ready(function() {
  $(document).bind('click', function(event) {
    $('div.custom-menu').remove();
  });
  $('a.user_menu').bind('contextmenu', function(event) {
    event.preventDefault();
    let id = $(this).attr('data-id');
    let user = $(this).attr('data-user');
    $('body').
    append('<div class="custom-menu"><ul><li><a href="profil.php?id=' + id +
        '">Gå til Profil</a></li><li><a href="innboks.php?ny&usertoo=' +
        user +
        '">Send melding</a></li><li><a href="bank.php?til=' + user +
        '">Send penger</a></li></ul></div>');
    $('div.custom-menu').
    css({top: event.pageY + 'px', left: event.pageX + 'px'});
  });
  $('#ct').on('click', function() {
    window.location.href = 'https://mafia.werzaire.net/chat.php';
  });
});