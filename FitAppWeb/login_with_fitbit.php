<?php
/*
 * login_with_fitbit.php
 *
 * @(#) $Id: login_with_fitbit.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */

	$_SESSION['ses_token'] = "227V4K"; //user access token if force_oauth=1
	$_SESSION['ses_secret'] = "275689f032b27e5f4201dc3612957f99"; //user secret key if force_oauth=1
	$_SESSION['ses_authorized'] = true;
	$_SESSION["force_oauth"] = 1; //set to false to login and get new key details

	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = 1;
	$client->debug_http = 1;
	$client->server = 'Fitbit';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_fitbit.php';

	$client->client_id = '227V4K'; 
	$application_line = __LINE__; //server code do not change
	$client->client_secret = '275689f032b27e5f4201dc3612957f99';	//server secret code do not change
	


	

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Fitbit application registration page https://dev.fitbit.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri).' Make sure this URL is '.
			'not in a private network and accessible to the Fitbit site.';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
			$yesterday = date("Y-m-d", strtotime("today"));
				$success = $client->CallAPI(
					'https://api.fitbit.com/1/user/-/profile.json', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
					
				///1/user/-/activities/date/2010-04-02.json HTTP/1.1
				$success = $client->CallAPI(
					"https://api.fitbit.com/1/user/-/activities/date/$yesterday.json", 
					'GET', array(), array('FailOnAccessError'=>true), $activity);
					
					
				$success = $client->CallAPI(
					"https://api.fitbit.com/1/user/-/sleep/date/$yesterday.json", 
					'GET', array(), array('FailOnAccessError'=>true), $sleep);	
					
				
				///1/user/-/foods/log/date/	
				$success = $client->CallAPI(
					"https://api.fitbit.com/1/user/-/foods/log/date/$yesterday.json", 
					'GET', array(), array('FailOnAccessError'=>true), $food);						
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fitbit Connector</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->user->displayName), 
			' you have logged in successfully with Fitbit!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
		echo '<pre>', HtmlSpecialChars(print_r($activity, 1)), '</pre>';
		echo '<pre>', HtmlSpecialChars(print_r($sleep, 1)), '</pre>';
		echo '<pre>', HtmlSpecialChars(print_r($food, 1)), '</pre>';
		echo "<p>For Reference Keys: Token: ".$client->access_token." Secret: ".$client->access_token_secret. $user->fullName"</p>";
		
		$link = mysql_connect('localhost', 'un', 'pw');
		if (!$link) {
		    die('Could not connect: ' . mysql_error());
		}
		echo 'Connected successfully';
		
		$links = mysql_select_db("db", $link);
		$array = json_encode($activity);
		$sleep = json_encode($sleep);
		$food = json_encode($food);
		echo $data = "UPDATE fitbit SET value='".$array."', sleep='$sleep', food='$food' WHERE id='1'";
		$query = mysql_query($data);
		echo mysql_affected_rows();
		if (!$query) {
			die('Invalid query: ' . mysql_error());
}
		
		
		mysql_close($link);
?>
</body>
</html>
<?php
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<p>Error: <?php echo HtmlSpecialChars($client->error); ?></p>
</body>
</html>
<?php
	}

?>