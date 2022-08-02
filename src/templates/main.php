<!DOCTYPE html>
<html>
<head>

<title>WeChat</title>

<meta name="generator" content="">
<meta name="version" content="<?=$version;?>">

<style type="text/css">
html body {
	font-size: 14px;
}

#container {
	width:500px;
	margin-top:150px;
}

#error {
	color:red;
}

#frm {
}

#footer {
	text-align:center;
	font-size:10px;
}
</style>

</head>

<body>


<div id="container">

	<div style="text-align:center;">
		<h1>WeChat-Tmall-Baidu-TsingHua</h1>
	</div>
	
	<?php if(isset($error_msg)){ ?>
	
	<div id="error">
		<p><?php echo strip_tags($error_msg); ?></p>
	</div>
	
	<?php } ?>
	
	<div id="frm">
	
	<!-- I wouldn't touch this part -->
	
		<form action="index.php" method="post" style="margin-bottom:0;">
			<input name="url" type="text" style="width:400px;" autocomplete="off" placeholder="https://" />
			<input type="submit" value="Go" />
		</form>
		
	<!-- [END] -->
	
	</div>
	
</div>

</body>
</html>
