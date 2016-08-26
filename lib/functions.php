<?php
function adminLoggedIn()
{
    if(isset($_SESSION['user_id'])){
        return true;
    }

    return false;
}

function redirect($url)
{
    $url = "location:".$url;
    header($url);
}

function sanitizeInput($var)
{
    $var = trim($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    if(get_magic_quotes_gpc())
        $var = stripslashes($var);	
    return $var;
}

function uploadPhoto($file,$mime,$id)
{
    $save_url = "resources/uploads/images/".$id.".jpg";
    move_uploaded_file($file, $save_url);

    processImage($save_url,$mime);
}

function processImage($saveUrl,$mime)
{
    $typeok = TRUE;

    switch($mime){
        case "image/gif":   $src = imagecreatefromgif($saveUrl); break;
        case "image/jpeg":  // Both regular and progressive jpegs
        case "image/pjpeg": $src = imagecreatefromjpeg($saveUrl); break;
        case "image/png":   $src = imagecreatefrompng($saveUrl); break;
        default:            $typeok = FALSE; break;
    }

    if($typeok){
        list($w, $h, $type, $attr) = getimagesize($saveUrl);

        // the size i want saved images to be
        $tw = 500;
        $th = 500;

        $tmp = imagecreatetruecolor($tw, $th);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array(array(-1, -1, -1),
            array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
        imagejpeg($tmp, $saveUrl);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}

function capitalise($str)
{
    $restOfLetters = substr($str, 1);
    return $str[0].strtolower($restOfLetters); 
}

function logNotifications($notifs)
{
    foreach($notifs as $notification){
        $_SESSION[$notification] = true;
    }
}

function notificationExists($notif)
{
    if(isset($_SESSION[$notif]))
        return true;
    return false;
}

function removeNotification($notif)
{
    unset($_SESSION[$notif]);
}

function logoutUser()
{
    unset($_SESSION['user_id']);
}
?>