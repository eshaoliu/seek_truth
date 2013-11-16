<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
//$msg  = $c->user_timeline($uid);
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新浪微博V2接口演示程序-Powered by Sina App Engine</title>
    <script tppe="text/javascript">
        function agree( pid,uid)
        {
            alert(pid);
            alert(uid);
            window.location.href("./handleagree.php");
        }
        function disagree(pid, uid)
        {
            alert(pid);
            alert(uid);
            window.location.href("./handlwedisagree.php");                                 
        }
    </script>
</head>

<body>
	<?=$user_message['screen_name']?>,您好！ 
	<h2 align="left">发送新微博</h2>
	<form action="" >
		<input type="text" name="text" style="width:300px" />
		<input type="submit" />
	</form>
<?php
if( isset($_REQUEST['text']) ) {
	$ret = $c->update( $_REQUEST['text'] );	//发送微博
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "<p>发送成功</p>";
	}
}
?>

<!--<div class="related-list relatedList-tips" style="height:405px">-->  
<?php if( is_array( $ms['statuses'] ) ):
	$mysql = new SaeMysql();
?>
<div style='border:0px;padding:3px; PADDING:0px; width:auto; height:480px; LINE-HEIGHT: 20px; OVERFLOW: auto; '>
<?php foreach( $ms['statuses'] as $item ): ?>
<div style="padding:10px;margin:5px;border:1px solid #ccc">
	<?=$item['text'];echo "<br />";?>
    <?php echo '发布于：';?>
    <?=$item['created_at'];echo "<br />";?>
    <?//=$item['id'];?>
    <?php echo '作者：'?>
    <?=$item['user']['name'];?>
    <form action="handleagree.php"> <input type="hidden" name ="pid" value="<?echo $item['idstr'];?>"> 
    <input type="hidden" name ="uid" value="<?echo $item['user']['idstr'];?>"> 
    <input type="hidden" name ="agree" value="0" > 
    <input type="hidden" name ="disagree" value="0"> 
    <input type="hidden" name ="result" value="0">
    <input type="hidden" name ="flag" value="0"> 
    <input type="submit" name="agree" value="truth" />
    </form>
    <form action="handledisagree.php"> <input type="hidden" name ="pid" value="<?echo $item['idstr'];?>"> 
    <input type="hidden" name ="uid" value="<?echo $item['user']['idstr'];?>"> 
    <input type="hidden" name ="agree" value="0"> 
    <input type="hidden" name ="disagree" value="0"> 
    <input type="hidden" name ="result" value="0">
    <input type="hidden" name ="flag" value="0"> 
    <input type="submit" name ="disagree" value="rumor"/>
    </form>
    <?
        $format= "select * from `vote` where uid = `%s` and pid =`%s`";
        $search= sprintf($format,$item['user']['idstr'],$item['idstr']);
        echo $search;
        $result= $mysql -> getVar($search);    
        var_dump($result);
        $agree = $result['agree'];
        echo $agree;
        $disagree = $result['disagree'];
        
    ?>
    <input type="button" value ="agree <?echo $agree;?>">
    <input type="button" value ="oppose <?echo $disagree;?>">
     <?php
     try{	
     $format = "insert into weibo(tid,text,uid,time) values('%s','%s','%s','%s')";
     $format1 = "insert into usrinfo(uid,screen_name,description) values('%s','%s','%s')";
     $sql  = sprintf($format,$item['idstr'],$item['text'],$item['user']['idstr'],$item['created_at']);
     $sql1 = sprintf($format1,$item['user']['idstr'],$item['user']['screen_name'],$item['user']['description']);
     $mysql -> runSql( $sql );
     $mysql -> runSql( $sql1);
    }catch(exception $e){
    }
 ?>
    
</div>

<?php endforeach; ?>
</div>

<?php
 $mysql->closeDb();
 endif; ?>

</body>
</html>
