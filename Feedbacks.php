<?php
if(! $_POST["contents"]) die( "404");
    $connect=mysqli_connect( "localhost", "id", "pw", "db") or
        die( "500");
   session_start();
   mysqli_set_charset($connect, "utf8");
   echo(mysqli_query($connect, 'INSERT INTO Feedbacks (content, model, version, id) VALUES ("'. mysqli_real_escape_string($connect, $_POST["contents"]).'","'.mysqli_real_escape_string($connect,$_POST["model"]).'","'.mysqli_real_escape_string($connect,$_POST["version"]).'",'.mt_rand(7, 5000000).')') ? "ok" : mysqli_error($connect));
   mysqli_close($connect);
?>
