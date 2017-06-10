<?php
  $host = 'localhost';
  $db = 'vesnasoftdb';
  $user = 'vesnasoftdb';
  $pass = 'vesnasoftdbuser0506';

  $con = mysqli_connect($host, $user, $pass, $db);

  mysqli_set_charset($con, "utf8");
