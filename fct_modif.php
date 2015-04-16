<?php

require_once("./Oauth/twitteroauth.php");
require_once("./sql_request.php");
require_once("./request.php");
date_default_timezone_set('UTC');

// parse les tweets en mots
function 	search($tweets)
{
  $words = array();
  for ($i = 0; $i < 36; ++$i) {
    if (isset($tweets[$i]))
      for ($i2 = 0; isset($tweets[$i][$i2]); ++$i2) {
	preg_match_all("/[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+/", $tweets[$i][$i2], $tmp);
	for ($i3 = 0; isset($tmp[0][$i3]); ++$i3) {
	  $tmp[0][$i3] = strtolower($tmp[0][$i3]);
	  if (isset($words[$i][$tmp[0][$i3]]) != true)
	    $words[$i][$tmp[0][$i3]] = 1;
	  else
	    $words[$i][$tmp[0][$i3]] += 1;
	}
      }
    else
      $words[$i] = 0;
  }
  return ($words);
}

// tri les mots par occurences, tableau sous la forme {"mot", int(occurrence)}
function	tri_rapide($tab = '')
{
  if (count($tab) < 2)
    return ($tab);
  $start = array();
  $end = array();
  reset($tab);
  $key = key($tab);
  $pivot = array_shift($tab);
  foreach ($tab as $keys => $values)
    {
      if ($values > $pivot)
	$start[$keys] = $values;
      else
	$end[$keys] = $values;
    }
  $mid = array($key => $pivot);
  $start_rec = tri_rapide($start);
  $end_rec = tri_rapide($end);
  $tab_rec = array_merge($start_rec, $mid, $end_rec);
  return ($tab_rec);
}

// Filtre des mots
function 	filter($words)
{
  $j = 0;
  $count = 0;
  $new_tab = array();
  $dico = file_get_contents('dico.txt');
  preg_match_all("/[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+/", $dico, $wl);
  $count_dico = count($wl[0]);
  if ($words != 0)
    foreach ($words as $key => $value) {
      if ($j == 10)
	return ($new_tab);
      for ($i = 0; $i < $count_dico; $i++)
	if ($key == $wl[0][$i] && strlen($key) > 2) {
	  $new_tab[$key] = $value;
	  $j++;
	  if ($j == 10)
	    return ($new_tab);
	  break;
	}
    }
  return ($new_tab);
}

// Tableau emission / hashtag
function recup_csv()
{
  $array = array();
  $assos = array();
  $fic = fopen("showHashtags.csv", "r+");
  for ($i = 0; $tab = fgetcsv($fic,1024,';'); $i++)
    {
      $array = explode('"', $tab[0]);
      $assos[$i][0] = $array[1];
      $assos[$i][1] = str_replace('#', '', $array[3]);
    }
  return ($assos);
}

// lancement du programme
function activate_pgrm()
{
  $username = "rouis";
  $password = "next";
  $dbname = "tweets";
  $servername = "localhost";
  $conn = create_connection($servername, $username, $password, $dbname);
  $connection = getConnectionTwitter();
  $result = json_decode(getContentTivine());
  $assos = recup_csv();
  $tweets = get_tweets($result, $assos, $connection);
  $words = search($tweets);
  for ($i = 0; $i < 36; $i++) {
    $words[$i] = tri_rapide($words[$i]);
    $newtop[$i] = filter($words[$i]);
    delete_from_table($conn, $i);
    insert_into_table($conn, $i, $newtop[$i]);
  }
  disconnect($conn);
}

activate_pgrm();