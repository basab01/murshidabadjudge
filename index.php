<?php
	session_start();
	//include_once "./config.php";
	include_once "./getview.php";
	$circuit = ''; $judge = ''; $country = '';
	if(isset($_SESSION['circuit']))
		$circuit = $_SESSION['circuit'];
	if(isset($_SESSION['judge']))
		$judge = $_SESSION['judge'];
	if(isset($_SESSION['country']))
		$country = $_SESSION['country'];
	if(!empty($judge))
	//echo 'Circuit : '.$circuit.' and Judge '.$judge;
	//print_r(get_loaded_extensions());
?>
<html>
<head>
<title>System Admin</title>
<style>
* {box-sizing:border-box; margin:0; padding:0; font-family:verdana,arial,helvetica,sans-serif;}
body {width:90%; background:#fff; margin:auto; padding:2%;}
#header {width:100%; height:48px; background:#eee; margin:0 auto; border-radius:16px 16px 0 0;-moz-border-radius:16px 16px 0 0; -moz-box-shadow : 0px 0px 5px #333; -webkit-box-shadow : 0px 0px 5px #333; box-shadow : 0px 0px 5px #333; border-top:1px solid white;}
#header h1 {font-size:18px; font-weight:normal; text-transform:uppercase; letter-spacing:6px; color:blue; text-align:center; line-height:48px;}
#header .ad {color:black; width:20%; display:table; float:right; text-align:center; cursor:pointer;}
#header .ad:hover {color:red; font-size:large;}

#content {width:100%; min-height:490px; background:white; color:#333; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#content select {padding:5%; border:none; border:1px solid #999; border-radius:6px; -moz-border-radius:6px; width:100%;}
#circuit {width:15%; margin:0 auto;}
#circuit .myselect {margin:30% 0 0 0;}
#content .mbutton{background-image:url('./images/designall1.png'); background-position:center center; background-repeat:no-repeat; width:30px; height:30px; border:none;background-color:none;}

#comp {width:15%; margin:0 auto; text-align:center;}/*position:absolute; top:70%; left:43%;*/
#comp input[type^=button] {padding:5%; border-radius:6px; -moz-border-radius:6px; background:#cfc;}

#judge {width:15%; min-height:340px; margin:0 auto; text-align:center;}
#judge .jname {margin:10% 0 0 0;}
#judge .countryName {margin:10% 0 0 0;}
#judge button {padding:5%; margin:5%; border-radius:6px; -moz-border-radius:6px;}

#disp {width:70%; margin:0 auto;}
#disp p {text-align:center; font-size:small;}

#footer {width:100%; height:18px; background:#666; color:white; -moz-box-shadow : 0px 1px 2px #000; -webkit-box-shadow : 0px 1px 2px #000; box-shadow : 0px 1px 2px #000;}
#footer h1 {font-size:14px; font-weight:normal; text-transform:uppdercase; letter-spacing:6px; text-align:center; line-height:18px;}
</style>


<script src = "./scripts/jquery-1.8.0.min.js"></script>
<script>
	function populate_circuit(selector){
		$(selector).empty();
		var option = $(document.createElement('option'));
		option.text('Select Salon').attr('value','');
		$(selector).append(option);
		selectedCircuit = "<?php echo $circuit; //echo $_SESSION['circuit']; ?>";
		
		$.ajax({
			type : 'get',
			url : 'circuit_pop.php',
			dataType : 'json',
			success : function(data){
				$.each(data,function(index,entry){
					var option = $(document.createElement('option'));
					if(selectedCircuit == entry.circuit_code){
						option.text(entry.circuit).attr('value',entry.circuit_code).attr('selected','selected');
					}else{
						option.text(entry.circuit).attr('value',entry.circuit_code);
					}
					$(selector).append(option);
				});
			}
		});	
	}
	
	function populate_country(selector)
	{
		$(selector).empty();
		var option = $(document.createElement('option'));
		option.text('Select Country').attr('value','');
		$(selector).append(option);
		var selectedCountry = "<?php echo $country; ?>";
		
		$.ajax({
			type : 'get',
			url : 'country_pop.php',
			dataType : 'json',
			success : function(data){
				$.each(data, function(i,j){
					var option = $(document.createElement('option'));
					if(selectedCountry == j.country_code){
						option.text(j.country_name).attr('value', j.country_code).attr('selected','selected');
					}else{
						option.text(j.country_name).attr('value',j.country_code);
					}
					$(selector).append(option);
				});
			}
		});
	}
	
	function judge_logout(){
		jury_sess_id = $('#judge .logout_button').attr('name');
		circuit_id = $('#judge .logout_button').attr('title');
		$.get('jurydelete.php?jury_session_id='+jury_sess_id+'&circuit_session_id='+circuit_id+'', function(data){
				getVal = data.split('|');
				$.each(getVal,function(index,value){
					$("#disp").html('<p>Logged Out</p>');
				});
		});	
	}
	
	
	$(function(){
		$('#header').html('<h1><?php echo $header; ?></h1>');
		$('#header').append('<div class=ad><a href="./admin/award_menu.php">Admin</a></div>');
		$('#footer').html('<h1><?php echo $footer; ?></h1>');
		populate_circuit('#circuit .myselect');
		populate_country('#judge .countryName');
		$('#judge .jname').hide();
		$('#judge .countryName').hide();
		$('#disp').empty();


		var selected_circuit = "<?php echo $circuit; //$_SESSION['circuit']; ?>";
		var selected_judge = "<?php echo $judge; //$_SESSION['judge']; ?>";
		var selected_country = "<?php echo $country; ?>";
		
			
			
			if(selected_circuit != ''){
				$.ajax({
					type:'get',
					url:'judge_pop.php?ccode='+selected_circuit+'',
					dataType:'json',
					success:function(data){
							$('#judge .jname').empty();
							var option = $(document.createElement('option'));
							option.text('Select Judge').attr('value','');
							$('#judge .jname').append(option);
							
							
							$('#judge .jname').show('slow');
							$.each(data, function(i,j){
								var option = $(document.createElement('option'));
								if(selected_judge == j.judge_code){
									option.text(j.judge).attr('value',j.judge_code).attr('selected','selected');
								}else{
									option.text(j.judge).attr('value',j.judge_code);
								}
								$('#judge .jname').append(option);
							});
					},
				});
				$('#judge .jname').show('slow');
				$('#judge .countryName').show('slow');
					
					
					
				if(selected_judge != ''){				
					$jury_logout = $("&nbsp;<br><button class='logout_button' name="+selected_judge+" id='logout_button' title="+selected_circuit+">LogOut</button>");
					$('#judge .logout').empty();
					$('#judge .logout').append($jury_logout);
					
					$('#judge .logout').click(function(){
						judge_logout();						
					});
					
				}
			}
			
			
			
			
			

		$('#circuit .myselect').change(function(){
			var selected = $(this).val();
			$("#disp").empty();
			if(selected != ''){
				$.ajax({
					type:'get',
					url:'judge_pop.php?ccode='+selected,
					dataType:'json',
					success:function(data){
							$('#judge .jname').empty();
							var option = $(document.createElement('option'));
							option.text('Select Judge').attr('value','');
							$('#judge .jname').append(option);

							$('#judge .jname').show('slow');
							$.each(data, function(i,j){
								var option = $(document.createElement('option'));
								option.text(j.judge).attr('value',j.judge_code);
								$('#judge .jname').append(option);
							});
							$('#judge .countryName').show('slow');
					},
				});
				
				
				
				
				//$('#judge .jname').change(function(){
				$('#judge .countryName').change(function(){
					var selected_circuit = "<?php echo $circuit; //$_SESSION['circuit']; ?>";
					var selected_judge = "<?php echo $judge; //$_SESSION['judge']; ?>";
					var selected_country = "<?php echo $country; ?>";
					
					var country=$(this).val(); var circuit = $('#circuit .myselect').val(); var judge = $('#judge .jname').val();
					
					if(judge == ''){
						judge = selected_judge;
					}
					
					if(circuit == ''){
						circuit = selected_circuit;
					}
					
					if(country == ''){
						country = selected_country;
					}
					
					
					if(judge != ''){
						$jury_logout = $("&nbsp;<br><button class='logout_button' name="+judge+" id='logout_button' title="+circuit+">LogOut</button>");
						$('#judge .logout').empty();
						$('#judge .logout').append($jury_logout);
						 $.ajax({
						 		type:'get',
								url:'juryselect.php?ccode='+circuit+'&judge_code='+judge+'&country_code='+country,
								dataType:'json',
								success:function(data){
									$.each(data,function(i,j){
										var txt = 'Judge Code : '+j.judge_code+' and Circuit Code '+j.circuit_code;
										//$('#judge .message').text(txt);
									});
								},
						 });
					}
				});
				
				
				$('#judge .logout').click(function(){
					judge_logout();
				});
			
				
			}else{
				$('#judge .jname').hide('slow');
				$('#judge .logout').empty();
				$('#disp').empty();
			}
		});
	});
</script>
</head>

<body>
<div id='header'></div>

<div id='content'>
	<div id='circuit'>
	<select class='myselect'></select>
	</div>
	<div id='judge'>
		<select class='jname'></select>
		<br />
		<select class='countryName'></select>
		<div class='logout'></div>
		<p class='message'></p>
		<div id='disp'></div>
	</div>
	
	<div id='comp'>
		<a href='awardmarks.php'><input type = 'button' name = 'award' value = 'Award'></a>
		<a href='selection.php'><input type = 'button' name = 'select' value = 'Select'></a>
	</div>
</div>

<div id='footer'></div>
</body>
</html>