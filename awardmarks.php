<?php
	session_start();
	require_once "./scripts/conn.php";
	
	if(isset($_SESSION['judge']) AND isset($_SESSION['circuit'])){
		$judge_code = $_SESSION['judge'];
		$cir_code = $_SESSION['circuit'];
		$button_values = array();
		if(isset($_SESSION['country']))
			$country_code = $_SESSION['country'];
		
		$sql="select * from circuit_judge_view where ccode = '".$cir_code."' and judge_code='".$judge_code."'";
		$result = $mysqldb->query($sql);
		list($circuit,$ccode,$jname,$jcode) = $result->fetch_row();
		
		$sql = "select country from country_master where country_id = ".$country_code;
		$result = $mysqldb->query($sql);
		list($country_name) = $result->fetch_row();
		
		$sql = "select btn_value from button_details where btn_type = 2";
		$result = $mysqldb->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$button_values[] = $row['btn_value'];
		}
		
		/* Section List */
		$sql = 'select section_name, section_code from section_list';
		$result = $mysqldb->query($sql);
		$sections = [];
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$sections[] = array($row['section_name'],$row['section_code']);
		}
	}

?>
<html>
<head>
<title>Selection Marks</title>

<style>
* {box-sizing:border-box; margin:0; padding:0; font-family:verdana,arial,helvetica,sans-serif;}
body {width:90%; background:#fff; margin:auto; padding:2%;}
#header {width:100%; height:48px; background:#eee; margin:0 auto; border-radius:16px 16px 0 0;-moz-border-radius:16px 16px 0 0; -moz-box-shadow : 0px 0px 5px #333; -webkit-box-shadow : 0px 0px 5px #333; box-shadow : 0px 0px 5px #333; border-top:1px solid white; display:table;}
#header h1 {font-size:18px; font-weight:normal; text-transform:uppercase; letter-spacing:6px; color:blue; text-align:center; line-height:48px;}
#header .ad {color:black; width:20%; display:table; float:right; text-align:center; cursor:pointer;}
#header .ad:hover {color:red; font-size:large;}

#content {width:100%; min-height:490px; background:white; color:#333; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#content select {padding:5%; border:none; border:1px solid #999; border-radius:6px; -moz-border-radius:6px; width:100%;}
#content .top {display:table; width:100%; height:65%; clear:both;}
#content .bottom {display:table; width:100%; }
#circuit {width:15%; margin:0 auto;}
#circuit .myselect {margin:30% 0 0 0;}
#content .mbutton{background-image:url('./images/designall1.png'); background-position:center center; background-repeat:no-repeat; width:30px; height:30px; border:none;background-color:none;}
#content .judge_name {display:table; float:left; padding:1% 0 0 4%; color:#033; font-weight:bold;}
#content .circuit_name {display:table; float:right; padding:1% 4% 0 0; color:#93f; font-weight:bold;}
#content .section_name {display:table; float:right; padding:1% 4% 0 0; color:#39f; font-weight:bold;}
#content .sec {color:#303;}

#disp {color:red; font-weight:bold; font-size:120%;}




#comp {width:100%; margin:0 auto; text-align:center; background:none; display:table; margin:0 0 0 0;}
#comp input[type^=button] {padding:0.5%; border-radius:6px; -moz-border-radius:6px; background:#ddd;}

#judge {width:15%; margin:0 auto; text-align:center;}
#judge .jname {margin:10% 0 0 0;}
#judge button {padding:5%; margin:5%; border-radius:6px; -moz-border-radius:6px;}

#disp {width:30%; margin:0 auto; text-align:center; height:25px;}
#disp p {text-align:center; font-size:small;}
#marks {width:50%; border:1px solid red; display:table; height:20px;}

#get_photo {width:600px; height:500px; margin:2% auto; border-radius:15px; -moz-border-radius:15px; border:1px solid black; background:#fefefe; line-height:220px; font-size:300%; text-align:center; color:blue;}
#imgid {width:40%; margin:1% auto; font-size:120%; text-align:center; color:green;}


#footer {width:100%; height:18px; background:#666; color:white; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#footer h1 {font-size:14px; font-weight:normal; text-transform:uppdercase; letter-spacing:6px; text-align:center; line-height:18px;}
#no_display {display:none;}
</style>

<style>
* {box-sizing:border-box; margin:0; padding:0; font-family:verdana,arial,helvetica,sans-serif;}
body {width:100%; background:#000; margin:auto; padding:0;}
#header {width:100%; height:26px; background:#eee; margin:0 auto; border-radius:16px 16px 0 0;-moz-border-radius:16px 16px 0 0; -moz-box-shadow : 0px 0px 5px #333; -webkit-box-shadow : 0px 0px 5px #333; box-shadow : 0px 0px 5px #333; border-top:1px solid white; display:table;}
#header h1 {font-size:18px; font-weight:normal; text-transform:uppercase; letter-spacing:6px; color:blue; text-align:center; line-height:26px;}
#header .ad {color:black; width:20%; display:table; float:right; text-align:center; cursor:pointer;}
#header .ad:hover {color:red; font-size:large;}

#content {width:100%; min-height:580px; background:white; color:#333; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#content select {padding:5%; border:none; border:1px solid #999; border-radius:6px; -moz-border-radius:6px; width:100%;}
#content .top {display:table; width:100%; height:80%; clear:both; background:#000;}
#content .bottom {display:table; width:100%; }
#circuit {width:15%; margin:0 auto;}
#circuit .myselect {margin:30% 0 0 0;}
#content .mbutton{background-image:url('./images/designall1.png'); background-position:center center; background-repeat:no-repeat; width:30px; height:30px; border:none;background-color:none;}
#content .judge_name {display:table; float:left; padding:0 0 0 4%; color:#033; font-weight:normal;}
#content .circuit_name {display:table; float:right; padding:0 4% 0 0; color:#93f; font-weight:normal;}
#content .section_name {display:table; float:right; padding:0 4% 0 0; color:#39f; font-weight:normal;}
#content .sec {color:#303;}

#disp {color:red; font-weight:bold; font-size:120%; }




#comp {width:100%; margin:0 auto; text-align:center; background:none; display:table; margin:0 0 0 0;}
#comp input[type^=button] {padding:0.5%; border-radius:6px; -moz-border-radius:6px; background:#ddd;}

#judge {width:15%; margin:0 auto; text-align:center;}
#judge .jname {margin:10% 0 0 0;}
#judge button {padding:5%; margin:5%; border-radius:6px; -moz-border-radius:6px;}

#disp {width:30%; margin:1% auto; text-align:center; height:15px; color:yellow; display:block;}
#disp p {text-align:center; font-size:small;}
#marks {width:50%; border:1px solid red; display:table; height:20px;}

#get_photo {width:800px; height:500px; margin:0 auto; border-radius:15px; -moz-border-radius:15px; border:0px solid yellow; background:none; line-height:220px; font-size:300%; text-align:center; color:blue; clear:both;} /*#fefefe*/
#imgid {width:40%; margin:0% auto; font-size:120%; text-align:center; color:black;}

#addmin {width:40%; margin:0% auto; font-size:120%; text-align:center; color:orange;}
#addmin li {display:inline-block;width:120px;}

#footer {width:100%; height:18px; background:#666; color:white; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#footer h1 {font-size:14px; font-weight:normal; text-transform:uppdercase; letter-spacing:6px; text-align:center; line-height:18px;}
#no_display {display:none;}
#stat {float:right; display:table; margin-top:-30px; margin-right:5px;}
</style>

<script src = "./scripts/jquery-1.8.0.min.js"></script>
<script>
	$(function(){
		$('#header').html('<h1><?php echo $header; ?></h1>');
		$('#footer').html('<h1><?php echo $footer; ?></h1>');
		var $section_list = $('#getsession').clone();
		$('#circuit').append($section_list);
		$('#getsession').attr('class','myselect');
		
		var buttons = $('#secondpg').clone();
		$('#comp').append(buttons);
		var judge_session = "<?php echo $jname; ?>";
		var circuit_session = "<?php echo $circuit; ?>";
		var country_name = "<?php echo '('.$country_name.')'; ?>";
		$('#content .judge_name').html('Welcome '+judge_session+' '+country_name);
		$('#content .circuit_name').html('Salon : '+circuit_session);
		$('#get_photo').hide();
		
		$('#getsession').change(function(){
			var section = $(this).val();
			$('#disp').show();
			$('#imgid').empty().show();
			if(section != ''){
				$.ajax({
					type:'get',
					url:'foto_no_display_award.php?section='+section,
					dataType:'json',
					success:function(data){
						$.each(data, function(i,j){
							var section = 'Section : '+j.section_name+'';
							$('#content .section_name').html(section);
							//$('#get_photo').html(j.actual_imgid);
							
							$('#disp').empty().append(j.award_marks);
							$('#nxt').attr('name',j.next_image);
							$('#imgid').empty().html(j.image_srl_no);
							
							/* img path generate */
							var kk = j.new_name;
							
							var author = parseInt(kk.substr(5,4));
							author = 'R-'+author;
							var section = parseInt(kk.substr(9,1));
							
							if ( section < 3 ){
								var thm = 'Print';
							}else{
								var thm = 'Digital';
							}
							
							var path = 'files/'+thm+'/'+author+'/'+j.actual_imgid;
							var getHtml = '<img src="'+path+'" '+j.flag+' />';
							$('#get_photo').html(getHtml);
							$('#get_photo').attr('name',j.end_no);
							$('#get_photo').attr('title',j.start_no);
							$('#get_photo').attr('class',j.section_code);
							$('#imgid').attr('title',j.actual_imgid);
							img_stat();
						});
					},
				});
				$('#circuit').hide();
				$('#get_photo').show();
			}
			
		});
		
		
		var routine_work = function(){
			$('#get_photo').hide();
			$('#disp').empty().hide();
			$('#circuit').show();
			$('#imgid').empty().hide();
		};
		
		
		
		
		
		
		var img_stat = function () {
			var foto_no = $('#imgid').html();
			var section = $('#get_photo').attr('class');
			var circuitcode = "<?php echo $_SESSION['circuit']; ?>";
			
			
			
			
			$.ajax({
				type:'get',
				url:'imagestataward.php',
				data:{
					'section':section,
					'image_no':foto_no,
					'circuitid':""+circuitcode+""
				},
			
				dataType:'json',
				success:function(data){
					$.each ( data, function (i,j ){
					
						var present = 0;
						var total = 0;
						present = j.present_no;
						present=present+1;
						total = j.total_img;
						var st = '';
						st = present+'/'+total;
						$('#stat').empty().html(st);
					});
				},
				error:function(jqXHR, status, error,data){
					console.log('status :',status,'error : ',error);
				}
			});
			
		};
		
		
		
		
		
		
		
		
		var get_last_marked_image = function(){
			var section = $('#get_photo').attr('class');
			$.ajax({
				type:'get',
				url:'lastpicdisplay_award.php',
				data:{
					'section':section
				},
				dataType:'json',
				success:function(data){
					$.each(data,function(i,j){
						$('#disp').html(j.award_marks);						
						$('#nxt').attr('name',j.next_image);
						$('#prv').attr('name',j.prev_image);
						$('#get_photo').html(j.actual_imgid);
						$('#imgid').empty().html(j.present_image);
						
						var kk = j.new_name;
							
							var author = parseInt(kk.substr(5,4));
							author = 'R-'+author;
							var section = parseInt(kk.substr(9,1));
							
							if ( section < 3 ){
								var thm = 'Print';
							}else{
								var thm = 'Digital';
							}
							var path = 'files/'+thm+'/'+author+'/'+j.actual_imgid;
							var getHtml = '<img src="'+path+'" '+j.flag+' />';
							$('#get_photo').html(getHtml);
							$('#imgid').attr('title',res[1]);
					});
				},
				error:function(jqXHR, status, error,data){
					console.log('status :',status,'error : ',error);
				}
			});
		};
	
	
	
		var next_prv = function(selector){
			var foto_no = $(selector).attr('name');
			var end_foto_no = $('#get_photo').attr('name');
			var section = $('#get_photo').attr('class');
			
			$.ajax({
				type:'get',
				url:'next_marks_award.php',
				data:{
					'section':section,
					'foto_no':foto_no
				},
				dataType:'json',
				success:function(data){
					$.each(data,function(i,j){
						$('#disp').html(j.award_marks);						
						$('#nxt').attr('name',j.next_image);
						$('#prv').attr('name',j.prev_image);
						//$('#get_photo').html(j.actual_imgid);
						$('#imgid').empty().html(j.present_image);
						
						/* img path generate */
							var kk = j.new_name;
							var author = parseInt(kk.substr(5,4));
							author = 'R-'+author;
							var section = parseInt(kk.substr(9,1));
							
							if ( section < 3 ){
								var thm = 'Print';
							}else{
								var thm = 'Digital';
							}
							var path = 'files/'+thm+'/'+author+'/'+j.actual_imgid;
							
							/*var img = new Image();
							img.src = path;
							var width = ''; var height = '';
							if ( img.naturalHeight <= img.naturalWidth){
								width = '100%'; height = '';
							}else{
								height = '100%'; width = '';
							}*/
							var getHtml = '<img src="'+path+'" '+j.flag+' />';
							$('#get_photo').html(getHtml);
							
							$('#imgid').attr('title',j.actual_imgid);
							img_stat();
					});
				},
				error:function(jqXHR, status, error,data){
					console.log('status :',status,'error : ',error);
				}
			});
		};
		
		
		
	
		var button_val = function(selector){
			var marks = $(selector).attr('name');
			var section = $('#get_photo').attr('class');
			var foto_no = $('#imgid').html();
			
			$.ajax({
				type:'get',
				url:'picmarks_award.php',
				data:{
					'section':section,
					'marks':marks,
					'image_no':foto_no
				},
				dataType:'json',
				success:function(data){
					$.each(data,function(i,j){
						$('#disp').empty().append(j.marks);
					});
				},
			});
		};
		
		$('input[type="button"]').click(function(){
			var buttonId = $(this).attr('id');
			var section = $('#getsession').val();
			
			var chk = /^btn\d{1,}$/;
			if(chk.test(buttonId) && section != ''){
				button_val('#'+buttonId);
				$("#nxt").trigger('click');
			}else{
				chk = /nxt|prv/;
				if(chk.test(buttonId)){
					next_prv('#'+buttonId);
				}
			}
			
		});
		$('#backprv').click(function(){
			routine_work();
		});
		
		$('#lastid').click(function(){
			get_last_marked_image();
		});
		
		
		function admintick (){
			var section = $('#get_photo').attr('class');
			var circuit = "<?php echo $_SESSION['circuit']; ?>";
			var image_id = $('#imgid').html();
			var gtype = 'Award';
			
			if ( section != '' && circuit != '' && image_id != '' ) {
				$.ajax({
					type:'get',
					url:'marks_admin.php',
					data:{
						circuitcode:circuit,
						sectioncode:section,
						imageid:image_id,
						type:gtype
					},
					dataType:'json',
					success:function(data){
						$('#addmin').empty();
						$.each ( data, function (i,j){
							var count = parseInt(j.count);
							if ( count <= 3 ) {
								var dts = j.data;
								
								$.each ( dts, function (k,p) {
									var info = '';
									info = $("<li />", {
										html:p.judge + '<img src="./images/success.gif" border=0 />'
									});
									$('#addmin').append(info);
								});
							}
							
							if(count == 3 ){
								$("#nxt").trigger('click');
							}
							
						});
					}
				});
			}
		}
		if ( judge_session == 'Admin' ){
			setInterval(admintick,3000);
		}
	});
	
</script>
</head>

<body>
<div id='header'>
</div>

<div id='content'>
	<div class='judge_name'></div>
	<div class='circuit_name'></div>
	<div class='section_name'></div>
	<div class='top'>
		<div id='circuit'></div>
		<div id='imgid'></div>
		<div id='get_photo'></div>
		<?php 
		 if ( $_SESSION['judge'] != 'admin' ):
		 ?>
		<br /><br /><div id='disp'></div>
		<?php else :?>
		<br /><br /><div id='addmin'></div>
		<?php endif; ?>
	</div>
	<div class='bottom'>
		<div id='comp'></div>
		<div id='stat'></div>
	</div>
</div>

<div id='footer'></div>

<div id='no_display'>
	<div id='marks'>
	
	</div>
	<select id="getsession">
		<option value=''>Section List</option>
		<?php
			foreach( $sections as $section ):
		?>
		<option value="<?php echo $section[1]; ?>"><?php echo $section[0]; ?></option>
		<?php
			endforeach;
		?>
	</select>
	
	<div id="secondpg">
		<input type="button" id="lastid" value="Last Image Marked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" id="prv" value="<<" class="btt">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
			foreach($button_values as $value)
			{
				$st = '';
				$st .= '<input type = "button" name = "'.$value.'" id = "btn'.$value.'" value = "'.$value.'" class = "mbutton">'."&nbsp;\n";
				print $st;
			}
			
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" id="nxt" value=">>" class="btt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" id="backprv" value="Section List">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='./index.php'><input type="button" id="goindex" value="Back"></a>
		<div id="cov"></div>
	</div>
	
	<div id='photo'></div>
</div>
</body>
</html>