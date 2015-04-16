<?php

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

function getConnectionTwitter()
{
  $notweets = 30;
  $consumerkey = "pOc52HUiRnYylOowHSwOfZu15";
  $consumersecret = "iSOQdb6PVSWiVgcbyGtc6vMAHDXmqU1voLaWPJVM1M5DPb6Pob";
  $accesstoken = "393547027-IdVlJsW3tEYEzD7epNFtKBeilUcYVFFfDN6SvgxD";
  $accesstokensecret = "2KmE9AzhpDX32hyKx2Pa02IyWLYdgcgIm5jrASVX25F8L";
  $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
  return ($connection);
}

// requete API tivine
function getContentTivine()
{
  $clientID = '10989852';
  $url = 'http://94.23.253.36:8080/TiVineWS_V1.0/GetAllContentForPart';
  $url2 = $url . '0' . $clientID;
  $encodedKey = hash_hmac('sha512', $url2, '9ff46e3be37783a01171850140f4f22f');
  $data = array('part' => '0', 'clientID' => $clientID, 'encodedKey' => $encodedKey);
  $options = array(
		   'http' => array(
				   'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
				   'method'  => 'POST',
				   'content' => http_build_query($data),
				   ),
		   );
  $context  = stream_context_create($options);
  $res = file_get_contents('http://94.23.253.36:8080/TiVineWS_V1.0/GetAllContentForPart?part=0&clientId=10989852&encodedKey=' . $encodedKey, false, $context);
  return ($res);
}

// Recup des tweets par emission x 36
function 	get_tweets($result, $assos, $connection)
{
  $hashtags = array();
  for ($i = 0; isset($result->programs[$i]); ++$i) {
    for ($i2 = 0; isset($assos[$i2]); ++$i2)
      if($assos[$i2][0] == $result->programs[$i]->title) {
	$hashtags = $assos[$i2][1];
	$i2 = 890;
	break;
      }
    if ($i2 != 890)
      $hashtags = str_replace(CHR(32),"",$result->programs[$i]->title);	
    $tmp = $connection->get("https://api.twitter.com/1.1/search/tweets.json?q=" . $hashtags . "&count=50");
    for ($k = 0; $tmp->statuses[$k]->text != NULL; $k++)
      $tweets[$i][$k] = $tmp->statuses[$k]->text;
  }
  return($tweets);
}