<?php
 
// Establish MySQL DB connection.  
class ConnectDBHaniIMS
{
    var $auto_init = true;
    function init($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName)
    {
        $user_agent = explode('/', $_SERVER['HTTP_USER_AGENT']);
        if ($user_agent[0] == 'Mozilla') {
            return new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);
        }
    }
}
