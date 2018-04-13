<?php
	function get_rows_no($table,$id,$conn)
	{
		/**************************************************
			Date : 26.02.2014
			Employee_code : T00015
			Name : Basab Jyoti Bhattacharjya
			Company : M. B. Technosoft Consultants (P) Ltd.
		***************************************************/
		$query = 'select '.$id.' from '.$table.'';
		$result = $conn->query($query);
		return $result->num_rows;
	}