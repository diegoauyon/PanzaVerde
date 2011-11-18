<html>
    <head>
        <title>Example Application Landing Page</title>
    </head>
    <body>
        <h1>Example Application Landing Page</h1>
<?php
    
    $rpx_api_key = '08a14e2102ec137d76d6c5c926b120d7e25a742c';
	$token = $_POST['token'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS,
        array('token' =>  $token,
              'apiKey' => $rpx_api_key,));
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $profileString = curl_exec($curl);
    if (!$profileString){
        echo '<p>Curl error: ' . curl_error($curl);
        echo '<p>HTTP code: ' . curl_errno($curl);
    } else {
        $profile = json_decode($profileString);
        if (property_exists($profile, 'err')) {
            echo '<p>Engage error: ' . $profile->err->msg;
        } else {
            session_start();
            if (property_exists($profile->profile, 'displayName')) {
                $_SESSION['userName'] = $profile->profile->displayName;
            } else {
                $_SESSION['userName'] = '(Anonymous Coward)';
            }
            echo '<p>Hi there ' . $_SESSION['userName'] . '!';
        }

    }
    curl_close($curl);
?>
    </body>
</html>