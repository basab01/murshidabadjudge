<?php
	$user = 'root';
	$paswd = '';
	$host = 'localhost';
	//$database = 'fipinternational-digital';
	$database = 'psmdblocal';
	
	$mysqldb = new mysqli("$host", "$user", "$paswd", "$database");
	if(!$mysqldb) die('Can not connect!');
	
	$header = '3rd MURSHIDABAD INTERNATIONAL SALON 2018';
	$footer = 'Developed by : M. B. Technosoft Consultants (P) Ltd.';
	$year = '2018';
?>