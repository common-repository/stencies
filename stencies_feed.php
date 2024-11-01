<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Stencies -  Stencils for posts and pages</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<?php 
/*
 still need to figure out to get this to work  also need to make jquery wordpress friendly 
wp_enqueue_script("jquery"); 
<script src="/<? echo WPINC ?>/js/jquery/jquery.js" type="text/javascript"></script>

*/?>
<link rel='stylesheet' type='text/css' href='<? echo WP_PLUGIN_URL?>/stencies/css/selector_style.css' />
<link rel="stylesheet" media="screen" href="<? echo WP_PLUGIN_URL?>/stencies/css/superfish.css" /> 
<? 
$pt_url = "http://stencies.com";
$site=substr(site_url(),7);
$user=urlencode(get_option("stencies_user"));
$pass=urlencode(get_option("stencies_pass"));
$page_size=10;
if($_GET['page']){
$current_page =$_GET['page'];}else{
$current_page=1;}

//get the local path to the plugins directory
$path = substr($_SERVER['SCRIPT_NAME'], 0, -15);

function remove_qs_key($url, $key) {
  $url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $url);
  return $url;
}

$qstring = remove_qs_key($_SERVER['QUERY_STRING'],'page');


?>  
<link rel='stylesheet' type='text/css' href='<? echo WP_PLUGIN_URL?>/stencies/css/stencies_main_style.css' />
<link rel='stylesheet' type='text/css' href='<? echo WP_PLUGIN_URL?>/stencies/selector_style.css' />
<script>


function insertAtCursor(myValue, cssValue, id) {
	parent.document.getElementById('content').value = parent.document.getElementById('stencies_val').value.replace("[!--insertstencies--!]", myValue)
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	var data = {
		action: 'stencies_insert_css',
		theStyle: cssValue,
		theId: id 
	};
	
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		parent.document.forms.post.submit(); // save the post on parent window to get access to post CSS can remove and do via JS
		self.parent.TB_remove();
});
}

function loadcss(id){
  filename='http://stencies.com/?feed=cssmainfeed&id='+id;
  $('head').append('<link rel="stylesheet" href="'+filename+'" type="text/css" />');
 }

function caseInsensitiveSort(a, b) 
{ 
   var ret = 0;
   a = a.toLowerCase();b = b.toLowerCase();
   if(a > b) 
      ret = 1;
   if(a < b) 
      ret = -1; 
   return ret;
}

function browserComp(brse){
	//brse ='ie4,ie5,ie6,ff2,ff3';
	var mySplitResult =  new Array();
	mySplitResult = brse.split(",");
	var checker = "0";
	mySplitResult=mySplitResult.sort(caseInsensitiveSort);
	var browsers ="";
	for(i = 0; i < mySplitResult.length; i++){
		// get the name of the image to show
		if(checker!=mySplitResult[i].substring(0,2)){
				if(checker!=0){browsers=browsers+('">')};
					browsers=browsers+'<img class="qtip" src="<? echo WP_PLUGIN_URL?>/stencies/images/';	
					browsers=browsers+mySplitResult[i].substring(0,2)+'.png" width="16" height="16"  title="';
					switch (mySplitResult[i].substring(0,2))
						{
						case 'ie':
						  browsers=browsers+"Internet Explorer: ";
						  break;
						case 'ff':
						  browsers=browsers+"Firefox: ";
						  break;
						case 'sa':
						  browsers=browsers+"Safari: ";
						  break;
						case 'ch':
						  browsers=browsers+"Chrome: ";
						  break;
						case 'op':
						  browsers=browsers+"Opera: ";
						   break;
						}
				}
		browsers=browsers+mySplitResult[i].substring(2)+' ';
		checker=mySplitResult[i].substring(0,2);
		
	}
	return browsers;
}

</script>
</head>
<body>


<script type="text/javascript">

$(document).ready(function() {
	parent.stencies_resize_thickbox();
	var applicationList = [];

$.getJSON("<?=$pt_url ?>/api/get_category_index/?callback=?&site=<?=$site?>&login=<?=$user?>:<?=$pass?>");
$.getJSON("<?=$pt_url ?>/api/get_category_index/?callback=?&site=<?=$site?>", function(data){

  	$.each(data.categories, function(i,data){	
	applicationList[i] = [];
	applicationList[i]['id'] = data.id;
	applicationList[i]['slug'] = data.slug;
	applicationList[i]['title'] = data.title;
	applicationList[i]['parent'] = data.parent;
  });
 // sort
  applicationList.sort(compareName);
  $.each(applicationList, function(index, value) {
	  	   var div_data = "<li class=\"n_id_"+applicationList[index]['id']+"\"><a href=\"?feed=getstencies&site=<?=$site?>&user=<?=$user?>&slug=/category/"+applicationList[index]['slug']+"\" >"+applicationList[index]['title']+"</a><ul class=\"p_id_"+applicationList[index]['id']+"\"></ul></li>"; 

	  	   var div2_data = "<li class=\"n_id_"+applicationList[index]['id']+"\"><a href=\"?feed=getstencies&site=<?=$site?>&user=<?=$user?>&slug=/category/"+applicationList[index]['slug']+"\" >"+applicationList[index]['title']+"</a></li>"; 
  
  
   if (applicationList[index]['parent']==0){
		$(div_data).appendTo("#nav");
	   	   } else{
		     var nid= applicationList[index]['parent'];
		   $('.p_id_'+nid).append(div2_data); 
		   
		   }
});
});
// sorting function
function compareName(a, b)
{
  if (a.parent < b.parent) return -1;
  if (a.parent > b.parent) return 1;
  return 0;
}


/*CSS ajax client insert */


<? 
$catslug=$_GET["slug"];
if($catslug!=""){
	$catslug ="".$catslug;
	$baseurl= $pt_url ;
	$endurl = "?json=$page_size&custom_fields=css,browsers,credit,creditURL&page=$current_page&callback=?";
}
$url=$baseurl.$catslug.$endurl;


?>  
//$.ajaxSetup({ cache:false });
$.getJSON("<?=$url?>", function(data){
	
	var page_size=<?=$page_size?>;
	var total_pages=data.pages;
	var total_count=data.count_total;
	var next_page=<?=$current_page+1?>;
	var prev_page=<?=$current_page-1?>;
	var current_page=<?=$current_page?>;
	var prev = '';
	var next = '';

	if(current_page>1){
		var prev='<a class="bck" href="?<?=$qstring?>&page='+prev_page+'">&laquo; previous</a>';
	}
	if(current_page<total_pages){
		var next='<a class="fwd" href="?<?=$qstring?>&page='+next_page+'">next &raquo;</a>';
	}
	var paging=prev+' <span class="this-page">page '+current_page+" of "+total_pages+"</span> "+next;
	if(total_count>page_size){
	 $("#paging").html(paging);
	}

  	$.each(data.posts, function(i,data){
		var browsers="";
		if(data.custom_fields.browsers){
			var brse = String (data.custom_fields.browsers);
			browsers=browserComp(brse)+'">';
		}
		
		if(data.custom_fields.credit  && data.custom_fields.creditURL){
			cretxt= '<strong>Credit: </strong><a href="'+data.custom_fields.creditURL+'">'+data.custom_fields.credit+'</a>';
		}else if(data.custom_fields.credit==null && data.custom_fields.creditURL){
			cretxt= '<strong>Credit URL: </strong><a href="'+data.custom_fields.creditURL+'">'+data.custom_fields.creditURL+'</a>';}else if(data.custom_fields.credit && data.custom_fields.creditURL==null){
			cretxt= '<strong>Credit URL: </strong>'+data.custom_fields.credit;}
			else{cretxt='';}

		
var div_data = "<div class=\"post-type\"><div class=\"sel_btn\"><form class=\"sel_form\" action=\"/\" method=\"get\" name=\"piglets\"><textarea class=\"sel_ta\" name=\"myfield\" cols=\"\" rows=\"\">"+data.content+"</textarea><div class=\"title\">"+data.title+"</div><textarea name=\"newCss\"class=\"hidden\">"+data.custom_fields.css+"</textarea><input class=\"button\" type=\"submit\" value=\"Insert into Post\" onclick=\";this.src='http://stencies.com/images/spinner.gif';parent.switchEditors.go('content', 'html');insertAtCursor(this.form.myfield.value,this.form.newCss.value,this.form.id.value);parent.switchEditors.go('content', 'tinymce');return false;\"  id=\"button_submit\" name=\"button_submit\" ><input type=\"hidden\" id=\"id\" name=\"id\" value=\""+data.id+"\"></form></div><div class=\"inwrp\">"+data.content+"</div><div class=\"meta\"><p>"+data.excerpt+"</p></div><div class=\"metabot\"><div class=\"credit\">"+cretxt+"</div><div class=\"brwrs\"><img src=\"<? echo WP_PLUGIN_URL?>/stencies/images/0.png\" width=\"1\" height=\"32\" />"+browsers+"</div></div>";
	$(div_data).appendTo("#posts");
	loadcss(data.id)
	if(i==2){$('.spinner').hide();};
  });

});
 });

</script>

<div id="header"><img src="<? echo WP_PLUGIN_URL?>/stencies/images/small_logo.png"  align="left"/>

<ul id="nav" class="sf-menu sf-js-enabled sf-shadow"><li><a href="?feed=getstencies&slug=/author/<?=urlencode($user)?>&site=<?=$site?>&user=<?=$user?>" >Your own stencies</a></li></ul>
<!-- Place this tag where you want the +1 button to render -->
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
</div>
<div id="paging" class="pagebar"></div>
<div id="posts">
<div class="spinner">
<img src="<? echo WP_PLUGIN_URL?>/stencies/images/spinner.gif"/></div>
</div>

</body>
</html>
