<?php

// Encryption functions
define('IV_SIZE', mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

function encrypt ($key, $value) {
    $iv = mcrypt_create_iv(IV_SIZE, MCRYPT_DEV_URANDOM);
    $crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv);
    $combo = $iv . $crypt;
    $value = base64_encode($iv . $crypt);
    return $value;
}

function decrypt ($key, $value) {
    $combo = base64_decode($value);
    $iv = substr($combo, 0, IV_SIZE);
    $crypt = substr($combo, IV_SIZE, strlen($combo));
    $value = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $crypt, MCRYPT_MODE_CBC, $iv);
    return $value;
}

// Database functions
function saveToDB($db, $username, $message) {
    if ($stmt = $db->prepare("INSERT INTO items (username, message) VALUES(?, ?)")) {

        $stmt->bind_param("ss", $username, $message);

        $stmt->execute();

        return true;
    } else {
        return false;
    }
}

function getFromDB($db, $username) {
    if ($stmt = $db->prepare("SELECT message FROM items WHERE username=?")) {
        $return = "";

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $stmt->bind_result($return);

        $stmt->fetch();



        $stmt->close();

        return $return;
    } else {
        return false;
    }
}