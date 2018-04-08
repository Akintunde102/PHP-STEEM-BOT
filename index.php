<?php
ini_set('max_execution_time', 6000);

include('vendor/autoload.php');

// alias the Config class.

use SteemConnect\Config\Config;
use SteemConnect\Client\Client;
use SteemConnect\Operations\Comment;
use SteemConnect\Operations\CommentOptions;
use SteemConnect\Operations\Vote;

// oauth client id.
$clientId = 'your-app';
// oauth client secret.
$clientSecret = 'client-secret';
// return url.
$returnUrl = 'https://localhost/bb';

// list of required scopes.
$scopes = [
    'login', 'offline', 'vote', 'comment', 'comment_delete',
    'comment_options', 'custom_json', 'claim_reward_balance',
];

// starts the configuration object, passing the client id and secret.
$config = new Config($clientId, $clientSecret);
// configure the return / callback URL.
$config->setReturnUrl($returnUrl);
// set the reqired scopes.
$config->setScopes($scopes);

// set the return/callback URL, so SteemConnect will redirect
// users back to your application.
$config->setReturnUrl('https://localhost/bb');



$sdk = new Client($config);

// get the URL to send the users that will authorize your app.
$redirectUrl = $sdk->auth()->getAuthorizationUrl();


if (empty($_GET['code'])){
// now redirect the user:
header('Location: '.$redirectUrl);
}



// exchanging the authorization code by a access token.
$token = $sdk->auth()->parseReturn();







$myfile = fopen("token.txt", "w") or die("Unable to open file!");
fwrite($myfile, $token);
fclose($myfile);


// set the Token instance on the SDK instance.
$sdk->setToken($token);


$chosen = array("user1","user-2");


$voter = 'upvoter';
$loyal = 'upvoter';

   $query = urlencode('{"tag":"'.$voter.'", "limit": "100"}');
$url = 'https://api.steemjs.com/get_discussions_by_blog?query='.$query;
$json= file_get_contents($url);
$postData = json_decode($json,true);

$n = 0;
foreach ($postData as $item){ 


if ($voter == $item["author"] && $n < 1){
	$votee = array();
	$a = 0;
	foreach ($item["active_votes"] as $itemVoter){
		
		if (in_array($itemVoter['voter'],$chosen)){$votee[$a] = $itemVoter['voter']; $a++;}
		
		$n++;	
		
	}

}

}

foreach ($votee as $v){

 $query = urlencode('{"tag":"'.$v.'", "limit": "100"}');
$url = 'https://api.steemjs.com/get_discussions_by_blog?query='.$query;
$json= file_get_contents($url);
$postData2 = json_decode($json,true);
$n2 =0;
foreach ($postData2 as $item2){ 


if ($v == $item2["author"] && $n2 < 1){
    $link=  $item2['permlink'];
	
	

	$d =0;
	$zlist = array();
	foreach ($item2["active_votes"] as $z){
		
		$zlist[$d] = $z['voter'];
		
		$d++;	
		
	}
	
	
$n2++;	

}






}

if (!in_array($voter,$zlist)){
	
	
	
$body = 'Congratulations!,@'.$v;





	
	


// upvoting / downvoting a post:
$vote = new Vote();
$p = 45*100;
$vote
    ->account($voter)
    ->on($v,$link)
    ->upVote($p);
    
$comment = new Comment();
$comment
    ->reply($v,$link)
	->permlink($link)
	->author($voter)
    ->body($body);
	
  

echo $v.'<br/>';

// the broadcast method magically accept any number of arguments, so broadcasting multiple operations is easily accomplished.
if ($sdk->broadcast($vote)){echo 'Vote  Made Successfully<br/><br/>';}
// the broadcast method magically accept any number of arguments, so broadcasting multiple operations is easily accomplished.
if ($sdk->broadcast($comment)){echo 'Comment Made Successfully<br/>';}

sleep(25);
}




}


