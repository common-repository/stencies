<?php
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Transfer-Encoding: text");
header("Content-Type: text/css");
require( '../../../wp-load.php' );
$stencies_custom_css = get_option("stencies_custom_css");
$stencies_user_css = get_option("stencies_user_css");
echo stripslashes( $stencies_custom_css);
echo stripslashes( $stencies_user_css);
 ?>

