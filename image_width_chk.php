<?php
	
		function check_imgwidth ( $code, $section_id, $mysqldb )
		{
			/*
			$chold = [];
			$chold = explode('-',$code[6]);
			$thm = '';
			
			if ( $chold[2] < 3 ) $thm = 'Print';
			else $thm = 'Digital';
			*/
			$new_name=$code[6]; $thm = '';
			
			$query='select user_id, name from images where new_name="'.$new_name.'"';
			
			$result = $mysqldb->query($query);
			list($user_id,$name)= $result->fetch_row();
			
			if ( $section_id < 3 ) $thm = 'Print';
			else $thm = 'Digital';
			
			$artist = intval ( $user_id );		
			$imgname = $name;		
			$path = 'files/'.$thm.'/'.'R-'.$user_id.'/'.$imgname;
			
			list($width, $height ) = getimagesize ( $path );
			if ( $width <= $height )
			{
				return 'height="100%"';
			}
			else 
			{
				if ( $width/$height < 1.34 )
				{
					return 'height="100%"';
				}
				else
				{
					return 'width="100%"';	
				}
			}
		}
?>