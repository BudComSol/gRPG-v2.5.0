<?php
include 'header.php';
?>

<tr>
    <td class="contenthead">Code of Conduct</td>
</tr>

<tr>
    <td class="contentprofile"><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>LegendaryThugs Box-O-Fun</title>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<link rel='stylesheet' href='boxOfun/style.css'/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<script>

$(document).ready(function(){
alert("Welcome to the Box-O-Fun!");
  alert("You think you're clever do you?");
  alert("Good, cause only GENIUSES can solve this little puzzle!");
  alert("The aim of the game is to make all of the boxes the same color, easy huh?")
  alert("Click on the boxes to make them change colors!")
  alert("But wait! You can only make them change colors once. You'll have to click every block in order to change the color a second time.");
  alert("Make sure you turn up your sound to enjoy the full potential of this lively game!");
  $('#firstblock').click(function(){
 
    $('#firstblock').css("background-color", "blue");
     var sound2 = new Audio("bonk.mp3");
  sound2.play();

});
  $('#secblock').click(function(){
    
  $('#secblock').css("background-color", "green");
   var sound2 = new Audio("bonk.mp3");
  sound2.play();

});
   $('#thirdblock').click(function(){
    
  $('#thirdblock').css("background-color", "red");
        var sound2 = new Audio("bonk.mp3");
  sound2.play();


});
   $('#fourthblock').click(function(){
    
  $('#fourthblock').css("background-color", "blue");
      var sound2 = new Audio("bonk.mp3");
  sound2.play();

   });
$('#fifthblock').click(function(){
    $('div').fadeOut('fast');
 $('#scary').delay(600).fadeIn('fast');
  var sound = new Audio("scream.mp3");
  sound.play();
   });
  $('#sixthblock').click(function(){
       var sound2 = new Audio("bonk.mp3");
  sound2.play();

  $('#sixthblock').css("background-color", "yellow");
   });
 
  
});

</script>

<body id="body">
  <div id="firstblock"> </div>
  <div id="secblock"> </div>
  <div id="thirdblock"> </div>
  <div id="fourthblock"> </div>
  <div id="fifthblock"> </div>
  <div id="sixthblock"> </div>
  <img id ="scary" src="scarecrow-creepy-smile.gif"/>
</body>
</html>
    </td>
</tr>

<?php
include 'footer.php';
?>
