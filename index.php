<?php

session_start();

include_once "libraries/vendor/autoload.php";

$google_client = new Google_Client();

$google_client->setClientId('306327846263-1nf0ni542ttol8tsmnktmecqm937bcal.apps.googleusercontent.com'); //Define your ClientID

$google_client->setClientSecret('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'); //Define your Client Secret Key

$google_client->setRedirectUri('http://localhost:3000/index.php'); //Define your Redirect Uri

$google_client->addScope('email');

$google_client->addScope('profile');

if (isset($_GET["code"])) {
  $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

  if (!isset($token["error"])) {
    $google_client->setAccessToken($token['access_token']);

    $_SESSION['access_token'] = $token['access_token'];

    $google_service = new Google_Service_Oauth2($google_client);

    $data = $google_service->userinfo->get();

    $current_datetime = date('Y-m-d H:i:s');

    echo "<pre>";
    print_r($data);
    echo "</pre>";

    $_SESSION['first_name'] = $data['given_name'];
    $_SESSION['last_name'] = $data['family_name'];
    $_SESSION['email_address'] = $data['email'];
    $_SESSION['profile_picture'] = $data['picture'];
    $_SESSION['user_id'] = $data['id'];
  }
}


$login_button = '';

// echo $_SESSION['access_token'];

if (!$_SESSION['access_token']) {
  $login_button = '<a href="' . $google_client->createAuthUrl() . '"><img src="asset/sign-in-with-google.png" /></a>';
}

?>

<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Login with Google in PHP</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>
  <div class="container">
    <br />
    <h2 align="center">Login using Google Account with PHP</h2>
    <br />
    <div class="panel panel-default">
      <?php
      if (!empty($_SESSION['access_token'])) {
        echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
        echo '<img src="' . $_SESSION['profile_picture'] . '" class="img-responsive img-circle img-thumbnail" />';
        echo '<h3><b>Name : </b>' . $_SESSION["first_name"] . ' ' . $_SESSION['last_name'] . '</h3>';
        echo '<h3><b>Email :</b> ' . $_SESSION['email_address'] . '</h3>';
        echo '<h3><b>ID :</b> ' . $_SESSION['user_id'] . '</h3>';
        echo '<h3><a href="logout.php">Logout</h3></div>';
      } else {
        echo '<div align="center">' . $login_button . '</div>';
      }
      ?>
    </div>
  </div>
</body>

</html>