<?php
	
		function check_imgwidth ( $code )
		{
			$chold = [];
			$chold = explode('-',$code[6]);
			$thm = '';
			
			if ( $chold[2] < 3 ) $thm = 'Print';
			else $thm = 'Digital';
			
			$artist = intval ( $chold[1] );		
			$imgname = $code[5];		
			$path = 'files/'.$thm.'/'.'R-'.$artist.'/'.$imgname;
			
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