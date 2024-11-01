<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stencies -  Stencils for posts and pages</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>

<? 
$pt_url = "http://stencies.com";
$user_site=substr($_GET['site'], 7);
//$pt_url = "http://dev.posttemplates.com" ;

//get the local path to the plugins directory
$path = substr($_SERVER['SCRIPT_NAME'], 0, -15);


 ?>
<link rel='stylesheet' type='text/css' href='<?=$pt_url?>/shared_files/stencies_main_style.css' />
<!--[if IE 6]>
	<link rel='stylesheet' type='text/css' href='<?=$pt_url?>/wp-content/plugins/stencies/stencies_style_ie6.css' />
<![endif]-->

<!--[if IE 7]>
	<link rel='stylesheet' type='text/css' href='<?=$pt_url?>/wp-content/plugins/stencies/stencies_style_ie7.css' />
<![endif]-->
<link rel='stylesheet' type='text/css' href='<?=$path?>/selector_style.css' />
<script>


function vvinsertAtCursor(myField, myValue) {
//IE support
if (document.selection) {
myField.focus();
sel = document.selection.createRange();
sel.text = myValue;
}
//MOZILLA/NETSCAPE support
else if (myField.selectionStart || myField.selectionStart == '0') {
var startPos = myField.selectionStart;
var endPos = myField.selectionEnd;
myField.value = myField.value.substring(0, startPos)
+ myValue
+ myField.value.substring(endPos, myField.value.length);
} else {
myField.value += myValue;
}
}

function insertAtCursor(myField, myValue) {
myField.value = myField.value.replace("<!--insertstencies-->", myValue)

}
// calling the function
</script>
</head>
<body>



<script type="text/javascript">
$(document).ready(function() {
$.getJSON("<?=$pt_url ?>/api/get_category_index/?callback=?&site=<?=$user_site?>", function(data){
  	$.each(data.categories, function(i,data){	
var div_data = "<li><a href=\"?slug=/category/"+data.slug+"\" >"+data.title+"</a></li>";
	$(div_data).appendTo("#cats");
  });
});


<? 
$catslug=$_GET["slug"];
if($catslug!=""){
	$catslug ="".$catslug."/";
	$baseurl= $pt_url ;
	$endurl = "?json=10&callback=?";
}
$url=$baseurl.$catslug.$endurl;

?>  
//$.ajaxSetup({ cache:false });
$.getJSON("<?=$url?>", function(data){
  	$.each(data.posts, function(i,data){	
var div_data = "<div class=\"sel_btn\"><form class=\"sel_form\" action=\"/\" method=\"get\" name=\"piglets\"><textarea class=\"sel_ta\" name=\"myfield\" cols=\"\" rows=\"\">"+data.content+"</textarea><div class=\"title\">"+data.title+"</div><input type=\"submit\" onclick=\"window.opener.switchEditors.go('content', 'html');var thePostValue = opener.document.getElementById('content').value;var thePost = opener.document.getElementById('content');insertAtCursor(opener.document.getElementById('content'), this.form.myfield.value);window.opener.switchEditors.go('content', 'tinymce');return false;\" class=\"button button-highlighted\" value=\"Insert this into your post\" name=\"button_submit\"></form>  </div>"+data.content;
	$(div_data).appendTo("#posts");
  });
});

 });

</script>

<div id="header"><img src="<?=$pt_url ?>/images/stencies_logo.jpg" width="200" height="91" align="left"/>
<ul id="cats"><li><a href=\"?slug=/author/<?=$user_site?>" >Your own stencies</a></li></ul>
</div>
<div id="posts"></div>


</body>
</html>
