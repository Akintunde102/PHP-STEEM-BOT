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
$scopes = $scopes = [
    'login', 'vote', 'comment'
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
// set the Token instance on the SDK instance.
$sdk->setToken($token);
$voter = 'you';
$tag = 'minnowsupport';
   $query = urlencode('{"tag":"'.$tag.'", "limit": "100"}');
$url = 'https://api.steemjs.com/get_discussions_by_query='.$query;
$json= file_get_contents($url);
$postData = json_decode($json,true);
$n = 0;
foreach ($postData as $item){ 
$v = $item["author"];
$link=  $item['permlink'];
$urllink = $item['parent_permlink'].'/@'.$item['author'].'/'.$item['permlink'];
	$votee = array();
	$a = 0;
	foreach ($item["active_votes"] as $itemVoter){
		$votee[$a] = $itemVoter['voter']; $a++;
	}
		$n++;	
	if (!in_array($voter,$votee)){

$body = 'Congratulations!,@'.$v.' Your post has been upvoted  via  `'.$tag.'` tag';
// upvoting / downvoting a post:
$vote = new Vote();
$op = 10;
$p = $op*100;
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
// the broadcast method magically accept any number of arguments, so broadcasting multiple operations is easily accomplished.
if ($sdk->broadcast($comment)){echo 'Comment Made Successfully<br/>';}
// the broadcast method magically accept any number of arguments, so broadcasting multiple operations is easily accomplished.
if ($sdk->broadcast($vote)){echo 'Vote  Made Successfully<br/><br/>';}
$file = 'today.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "Author : @$v <br/>
Post: https://steemit.com/$urllink  <br/>
Vote Percentage:  $op.'%'." <br/>
Tag: $tag
<hr/>";
// Write the contents back to the file
file_put_contents($file, $current);
sleep(25);
}
}
