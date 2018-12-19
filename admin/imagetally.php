<?php
	require "../scripts/conn.php";
	
	define('APP', __DIR__);
	
	
	$query = 'select id, theme_id, user_id, name, title from images where theme_id=3 order by id';
	$result=$mysqldb->query($query);
	
	$list = [];
	
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$list[] = array('id'=>$row['id'],
						'theme_id'=>$row['theme_id'],
						'user_id'=>$row['user_id'],
						'name'=>$row['name'],
						'title'=>strtoupper($row['title'])
						);
	}
	
?>
<html>
<style>
table {margin:5% auto; border:1px solid #069; padding:0px; border-spacing:0px;}
table td, table th {padding:10px; border:1px solid #069;}
</style>
<body>
<div>
	<h2 style="text-align:center;">Display of Images</h2>
	<table border=1 width="600" style="">
	<tr>
		<th width="40">ID</th>
		<th width="40">Theme</th>
		<th width="40">User</th>
		<th width="120">File</th>
		<th width="260">Image</th>
		<th width="100">Title</th>
	</tr>
	<?php
		foreach($list as $ll):
	?>
	<tr>
		<td><?php echo $ll['id'];?></td>
		<td><?php echo $ll['theme_id'];?></td>
		<td><?php echo $ll['user_id'];?></td>
		<td><?php echo $ll['name'];?></td>
		<td><img src="../files/Digital/R-<?php echo $ll['user_id'];?>/<?php echo $ll['name'];?>" style="width:260px;" /></td>
		<td><?php echo $ll['title'];?></td>
	</tr>
	<?php
		endforeach;
	?>
	</table>
</div>
</body>
</html>