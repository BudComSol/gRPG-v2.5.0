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
        }
        /* First round!*/
        #firstblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
        }
        #secblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          margin-left: 850px;
          margin-top: 250px;
        }
        #thirdblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          margin-top: 180px;
          margin-left: 140px;
        }
        #fourthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          margin-left: 1400px;
        }
        #fifthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          margin-left: 600px;
          margin-top: 400px;
        }
        #sixthblock {
          height: 100px;
          width: 100px;
          position: absolute;
          border: 5px red solid;
          margin-left: 400px;
          margin-top: 500px;
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
        <p>Welcome to the Box-O-Fun! The aim of the game is to make all of the boxes the same colour. Click on the boxes to make them change colours!</p>
        <div id="body" style="position:relative;min-height:650px;">
            <div id="firstblock"></div>
            <div id="secblock"></div>
            <div id="thirdblock"></div>
            <div id="fourthblock"></div>
            <div id="fifthblock"></div>
            <div id="sixthblock"></div>
            <img id="scary" src="images/scarecrow-creepy-smile.gif" alt="BOO!" style="max-width:100%;max-height:100%;"/>
        </div>
        <script>
        (function () {
            document.getElementById('firstblock').addEventListener('click', function () {
                document.getElementById('firstblock').style.backgroundColor = 'blue';
            });
            document.getElementById('secblock').addEventListener('click', function () {
                document.getElementById('secblock').style.backgroundColor = 'green';
            });
            document.getElementById('thirdblock').addEventListener('click', function () {
                document.getElementById('thirdblock').style.backgroundColor = 'red';
            });
            document.getElementById('fourthblock').addEventListener('click', function () {
                document.getElementById('fourthblock').style.backgroundColor = 'blue';
            });
            document.getElementById('fifthblock').addEventListener('click', function () {
                var scary = document.getElementById('scary');
                scary.style.display = 'block';
                scary.addEventListener('click', function () {
                    scary.style.display = 'none';
                }, { once: true });
            });
            document.getElementById('sixthblock').addEventListener('click', function () {
                document.getElementById('sixthblock').style.backgroundColor = 'yellow';
            });
        }());
        </script>
    </td>
</tr>
