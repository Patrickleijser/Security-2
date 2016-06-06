<?php

header('Content-Type: application/json');
$return = array();

require_once("functions.php");

$servername = "127.0.0.1";
$username = "root";
$password = "123abc";
$database = "sec2";

// Create connection
$db = new mysqli($servername, $username, $password);
$db->select_db($database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Post functions
if(isset($_POST)) {
    $key = pack('H*', hash('sha256', $_POST['password']));
    $username = $_POST['username'];
    $message = encrypt($key, $_POST['message']);

    if($_POST['encrypt']) {
        $return['type'] = 'encrypt';

        if($_POST['username'] == '')
            $return['msg'] = 'Vul een gebruikersnaam in.';

        elseif($_POST['password'] == '')
            $return['msg'] = 'Vul een wachtwoord in.';

        elseif($_POST['message'] == '')
            $return['msg'] = 'Vul een geheim bericht in.';

        elseif(saveToDB($db, $username, $message)) {
            $return['msg'] = 'Bericht is succesvol opgeslagen met encryptie!';
        }

    } else {
        $return['type'] = 'decrypt';

        if($_POST['username'] == '')
            $return['msg'] = 'Vul een gebruikersnaam in.';

        elseif($_POST['password'] == '')
            $return['msg'] = 'Vul een wachtwoord in.';

        else {
            $message = getFromDB($db, $username);
            $return['decryptedMessage'] = decrypt($key, $message);
            $return['msg'] = 'Bericht is succesvol opgehaald.';
        }
    }

}

$db->close();
echo json_encode($return);