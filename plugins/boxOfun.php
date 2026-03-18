<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
?>
<tr>
    <th class="content-head">Code of Conduct</th>
</tr>
<tr>
    <td class="content">
        <style>
        #body {
          background-color: #321313;
          position: relative;
          min-height: 650px;
          overflow: hidden;
        }
        /* First round!*/
        #firstblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          left: 0;
          top: 0;
        }
        #secblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          left: 60%;
          top: 250px;
        }
        #thirdblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          left: 140px;
          top: 180px;
        }
        #fourthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          right: 0;
          top: 0;
        }
        #fifthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          left: 40%;
          top: 400px;
        }
        #sixthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          left: 20%;
          top: 500px;
        }

        #scary
        {
          display: none;
          background-color:black;
          position:fixed;
          width:100%;
          height:100%;
          top:0px;
          left:0px;
          z-index:1;
        }
        </style>
        <div id="body">
            <div id="firstblock"></div>
            <div id="secblock"></div>
            <div id="thirdblock"></div>
            <div id="fourthblock"></div>
            <div id="fifthblock"></div>
            <div id="sixthblock"></div>
            <img id="scary" src="images/scarecrow-creepy-smile.gif" alt="BOO!" style="max-width:100%;max-height:100%;"/>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
        $(document).ready(function () {
            alert("Welcome to the Box-O-Fun!");
            alert("You think you're clever do you?");
            alert("Good, cause only GENIUSES can solve this little puzzle!");
            alert("The aim of the game is to make all of the boxes the same color, easy huh?");
            alert("Click on the boxes to make them change colors!");
            alert("But wait! You can only make them change colors once. You'll have to click every block in order to change the color a second time.");
            alert("Make sure you turn up your sound to enjoy the full potential of this lively game!");

            $('#firstblock').click(function () {
                $('#firstblock').css("background-color", "blue");
                var sound2 = new Audio("/sounds/bonk.mp3");
                sound2.play();
            });
            $('#secblock').click(function () {
                $('#secblock').css("background-color", "green");
                var sound2 = new Audio("/sounds/bonk.mp3");
                sound2.play();
            });
            $('#thirdblock').click(function () {
                $('#thirdblock').css("background-color", "red");
                var sound2 = new Audio("/sounds/bonk.mp3");
                sound2.play();
            });
            $('#fourthblock').click(function () {
                $('#fourthblock').css("background-color", "blue");
                var sound2 = new Audio("/sounds/bonk.mp3");
                sound2.play();
            });
            $('#fifthblock').click(function () {
                $('div').fadeOut('fast');
                $('#scary').delay(600).fadeIn('fast');
                var sound = new Audio("/sounds/scream.mp3");
                sound.play();
            });
            $('#sixthblock').click(function () {
                $('#sixthblock').css("background-color", "yellow");
                var sound2 = new Audio("/sounds/bonk.mp3");
                sound2.play();
            });
        });
        </script>
    </td>
</tr>
