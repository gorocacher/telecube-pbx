<?php

namespace Telecube;


class Common{

	function set_pref($name, $value){
		global $Db, $dbPDO;
		$q = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($q,array($value, $name),$dbPDO);
	}

	function get_pref($name){
		global $Db, $dbPDO;
		$q = "select value from preferences where name = ?;";
		$res = $Db->pdo_query($q, array($name), $dbPDO);
		return $res[0]['value'];
	}

	function get_file_perm($fp){
		return substr(sprintf('%o', fileperms($fp)), -4);
	}

	function get_set_version_pref($prefname, $defval){
		global $Db, $dbPDO;

		$q = "select * from preferences where name = ?;";
		$res = $Db->pdo_query($q,array($prefname),$dbPDO);
		if(isset($res[0]['name']) && $res[0]['name'] == $prefname){
			return $res[0]['value'];
		}else{
			$q = "insert into preferences (name, value) values (?,?);";
			$Db->pdo_query($q,array($prefname, $defval),$dbPDO);
			return $defval;
		}
	}

	function git_commit_id_from_log($arr){
		// get the commit id
		$commit_id = str_replace("commit ", "", $arr[0]);
		$commit_id = trim($commit_id);
		return $commit_id;
	}

	function system_update($com, $v){
		global $Db, $dbPDO;
		
		$com = exec($com);

		$qu = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($qu,array($v, 'current_version_system'),$dbPDO);

		return true;
	}
	
	function db_update($q, $v){
		global $Db, $dbPDO;

		$Db->pdo_query($q,array(),$dbPDO);

		$qu = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($qu,array($v, 'current_version_db'),$dbPDO);

		return true;
	}

	function random_password( $length = 8, $incspecialchars = false ) {
	    $chars = "bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ123456789";
	    if($incspecialchar){
		    $chars .= "!@#$%^&*()_-=+;:,.?";
	    }
	    $password = substr( str_shuffle( $chars ), 0, $length );
	    return $password;
	}

	// debugging helper
	function ecco($s){
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}
}
?>