<?php
$page = (isset($_GET['page']))? $_GET['page'] : 'DASHBOARD';

switch($page){
	
	case 'view_siswa':
	include "siswa/siswa.php";
	break;
	
	case 'view_jadwal':
	include "jadwal/jadwal.php";
	break;
	
	default:
	include "dashboard.php";
	
}
?>
