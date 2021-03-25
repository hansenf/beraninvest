<?php

define('TIMEOUT', 1500);


//function session_timeout(){
    if(isset($_SESSION['user'])){
        if(isset($_SESSION['timeout'])){
            $timeNow = time();
            $timeOut = $_SESSION['timeout'];
            if($timeNow > $timeOut){
                header('Location: /customer/logout');
            }
            else{
                $_SESSION['timeout'] = $timeNow + constant('TIMEOUT');
            }
        }
        else{
            $_SESSION['timeout'] = time() + constant('TIMEOUT');
        }
    }
//}
