//Dette scriptet skal h�ndtere behandlingen av info frem og tilbake og for�rsake autoupdate
function updatepage(str,responsediv){
    //document.getElementById(responsediv).style.display="block";
    //$cur = '#'+document.getElementById(responsediv);
    var rese = "#"+responsediv;
    $(rese).fadeIn(1000);
    document.getElementById(responsediv).innerHTML=str;
    document.getElementById(responsediv).style.display="block";
    document.getElementById(responsediv).style.color="red";
    //alert("Oppgave fullf�rt!");
}
$(document).ready(function(){
  $("#lfn").submit(function(event) {
      event.preventDefault();
      var $form = $(this).serialize(),url=$(this).attr('action');
      url +="?fned";
      $.post(url,$form,function(data){
          var obj = eval(data);
          alert(obj.text);
          if(typeof obj.redir == "undefined"){
              //Ikke gj�r noe
          }
          else{
              window.location.replace="http://mafia-no.net/" + obj.redir;
          }
      });
      /*var $form = $(this);
      $.post("handler.php",{});*/
  });
});