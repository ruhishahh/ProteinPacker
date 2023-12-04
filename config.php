<?php

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function get_connection()
{
    static $connection;

    if (!isset($connection))
    {
        
        $connection = mysqli_connect('mariadbprj', 'admindatabaseprj', 'hamouda', 'my_databaseprj')
        or die(mysqli_connect_error());
        
    }
    if ($connection == false)
    {
        echo "Unable to connect to database<br/>";
        echo mysqli_connect_error();
    }

    return $connection;
}

?>