<?php
  $host_name = 'db5016077830.hosting-data.io';
  $database = 'dbs13095086';
  $user_name = 'dbu3394684';
  $password = 'Heroes123789!';  

  $link = new mysqli($host_name, $user_name, $password, $database);

  if ($link->connect_error) {
    die('<p>Failed to connect to MySQL: '. $link->connect_error .'</p>');
  } else {
    echo '<p>Connection to MySQL server successfully established.</p>';
  }
?>