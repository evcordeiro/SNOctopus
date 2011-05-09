<?php 
require_once 'lib/access.class.php';
require_once 'lib/db/sno_db_interface.php';
require_once 'lib/functions.php';

$user = new flexibleAccess();

/*
ajax.php Option list
0 : New Feed [Fabio]
1 : Toggle Networkid / Feed active status [Tom]
2 : Check if url exists for feeds [Fabio]
3 : Add Facebook Account
4 : Toggle global active status for network

*/


$values = $_GET['values'];
$id = $user->userData['user_id'];

//setNewFeedMap($feedUrl, $networkId, $activeState = 1)

switch($_GET['option']){
	case "0":
		echo $values ."    " . $id;
		$networks = sno_db_interface::getNetworkIdArrayFromUserId($id);
		foreach($networks as $nid){
			sno_db_interface::setNewFeedMap($value,$nid);
		}
	break;
	case "1":
		$val = json_decode(base64_decode($values), true);
		$networkId = $val[1];
		$feedUrl = $val[0];
		
		$state = $_GET['state'];
		if($state)
			$state = 0;
			else
		$state = 1;
		
		sno_db_interface::setNewFeedMap($feedUrl, $networkId,$state);
			
		
		echo $state;
	break;
	case "2":
		$arr = findFeedURL($values);
		echo $arr['verify'];

		if($arr['verify']){
			
			$query = "SELECT * FROM networks WHERE user_id = ?";
			$networks = sno_db_interface::executePreparedQueryN($query,array($id));
			
			$feedUrl = $arr['url'];
			foreach($networks as $network){
					$networkId = $network['network_id'];
					sno_db_interface::setNewFeedMap($feedUrl, $networkId);
			}
		}


	break;
	case "3":
		require_once('lib/db/sno_db_interface.php');
		$graph_url = "https://graph.facebook.com/".$values['id']."?".$values['access_token'];
		$user = json_decode(file_get_contents($graph_url));
		
		echo sno_db_interface::setNewNetwork($id, "facebook" , $user->username, $values, true );

	break;
	
	case "4":
		require_once('lib/db/sno_db_interface.php');
		$state = $_GET['state'];
		if($state)
			$state = 0;
		else
		$state = 1;
		$netId = $values;
		echo sno_db_interface::updateActiveStatus($netId, $state);
	break;
	case "5":
		$networkId = $values;
		sno_db_interface::deleteNetwork($networkId);
	break;		

}




?>