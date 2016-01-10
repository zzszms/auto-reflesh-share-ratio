<?php
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Authors: Original Author ct ctnmb.com                                |
// |          version 20151219                                            |
// +----------------------------------------------------------------------+
//
include './config.inc.php'; 
include './include/db_mysql.class.php'; //直接引用sikemi原本的数据库链接文件
error_reporting(0);
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META name='AUTHOR' content='万世残天ctnmb.com'>
<title>分享率自助刷新</title>
</head>
<body>
<div style="text-align:center;padding:200px;">
<h1>荔香分享率自助刷新</h1>
<span style="font-size:5px;">Power By &copy; 万世残天</span>
<br>
<br>
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
输入UID:<input type="text" name="uid" />
<input type="submit" value="刷新"  />
</form>
<br>
欢迎加入荔香站讨论群：<a href="**********" target="_blank">************</a><br><br>
<a href="**************" target="_blank">自助刷新使用教程</a>
<br>
</body>
</html>
<?php
$uid = $_POST['uid'];
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
if(empty($_REQUEST['uid']))
{
	exit;
}
ctsx($uid);//这个function修改自sikemi的fuunction addtraffic(在announce.php里)，原function的作用就是计算和刷新分享率的，但是当没有upload or download 的时候不会自动刷新
function ctsx($uid){
	global $db,$tablepre;
        $credits_query=$db->query("select * from {$tablepre}common_member_count where uid={$uid}");
        $credit=mysql_fetch_assoc($credits_query);
		if(empty($credit['uid'])){
		die("<script>alert('请输入正确uid!');history.back();</script>");
		}//原class在sql错误的时候会直接显示出错误的sql语句，就是随便输入一个不存在的uid时就会显示出sql语句，所以加此判定屏蔽报错
        $up_credit=$credit['extcredits4'];
        $down_credit=$credit['extcredits5'];
		echo "<br><span style='text-align:center;'>用户uid：".$credit['uid']."&nbsp;&nbsp;&nbsp;上传量：".$up_credit."&nbsp;&nbsp;&nbsp;下载量：".$down_credit."&nbsp;&nbsp;&nbsp;分享率:".$credit['extcredits3']."</span></div>";
        if ($down_credit==0){
        $db->query("UPDATE {$tablepre}common_member_count SET extcredits3=99.99 WHERE uid={$uid}");
}       else{
        $ratio=$up_credit/$down_credit;
        if ($ratio>99.99){
        $db->query("UPDATE {$tablepre}common_member_count SET extcredits3=99.99 WHERE uid={$uid}");
}       else{
        $db->query("UPDATE {$tablepre}common_member_count SET extcredits3={$ratio} WHERE uid={$uid}");
}
}       mysql_free_result($credits_query);
}
?>