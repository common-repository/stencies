<html>
<head>
<style>

</style>
</head>
<?php 
//Load settings, etc
$stencies_user_css = get_option("stencies_user_css");
$stencies_auto = get_option("stencies_auto");
$stencies_user = get_option("stencies_user");
$stencies_pass = get_option("stencies_pass");

//Where are we?
$this_page = $_SERVER['PHP_SELF'].'?page='.$_GET['page'];

//If admin options updated (uses hidden field)
if ('process' == $_POST['stage']) {
	update_option('stencies_user_css', $_POST['user_css']);
	update_option('stencies_user', $_POST['stencies_user']);
	update_option('stencies_pass', $_POST['stencies_pass']);

	$stencies_user_css = get_option("stencies_user_css");
	$stencies_template= get_option("stencies_template");
	$stencies_auto = get_option("stencies_auto");
	$stencies_user = get_option("stencies_user");
	$stencies_pass = get_option("stencies_pass");
}
?>
<body>
<div class="container">
<div class="banner"><h1>Stencies settings</h1></div>
<div class="stencies_style">
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&updated=true">
<h2>Your custom CSS</h2>

   <textarea name="user_css" cols="100" rows="15" id="user_css"><?php  echo stripslashes($stencies_user_css); ?>
   </textarea>



<h2>Stencies user name</h2>
<p>If you want to make your own Stencies, you require a Stencies.com user name, click here to <a href="http://www.stencies.com/wp-login.php?action=register">register</a>,<br>
documentation on how to create your own Stencies will be made available within the next month or two.</p>
<p>
   <input name="stencies_user"  id="stencies_user" value="<?php  echo stripslashes($stencies_user); ?>">
</p>
<h2>Stencies password</h2>
<p>
   <input name="stencies_pass"  id="stencies_pass" value="<?php  echo stripslashes($stencies_pass); ?>">
</p>
<input type="hidden" name="stage" value="process" />
<input type="submit" name="button_submit" value="<?php _e('Update Options', 'stencies-template') ?> &raquo;" />
</form><br />


<div id="social">
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=249346278418654&amp;xfbml=1"></script><fb:like href="http://stencies.com/" send="true" layout="button_count" width="150" show_faces="true" font=""></fb:like>
<g:plusone size="small" href="http://stencies.com/"></g:plusone>

<!-- Place this tag after the last plusone tag -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></div>
<a href="http://twitter.com/stencies" class="twitter-follow-button" data-show-count="false">Follow @stencies</a>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
</div>
</div>
</body>
</html>
