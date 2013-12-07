<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$msg  = $c->public_timeline();
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
//var_dump($c);
//print_r($ms);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>求真相</title>
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
	<!--<?=$user_message['screen_name']?>,您好！ 
	<h2 align="left">发送新微博</h2>
	<form action="" >
		<input type="text" name="text" style="width:300px" />
		<input type="submit" value="发布微博"/>
	</form>-->
<?php

?>
<input type=button value="刷新" onclick="location.reload()"/> 
<!--<div class="related-list relatedList-tips" style="height:405px">-->  
<?php if( is_array( $ms['statuses'] ) ):
	$mysql = new SaeMysql();
?>
<? //$id=0;?>
<div style='border:0px;padding:3px; PADDING:0px; width:auto; height:480px; LINE-HEIGHT: 20px; OVERFLOW: auto; '>
<?php foreach( $ms['statuses'] as $item ): ?>
<div style="padding:10px;margin:5px;border:1px solid #ccc">
	<?//=$item['text'];echo "<br />";?>
    <?php  
        if($item['text']=='转发微博') 
        {
            echo  '这是一条被转发的微博';
            echo  $item['retweeted_status']['text'];
        }  
        else
          echo $item['text'];
        echo "<br />";
    ?>
    <?php echo '发布于：';?>
    <?=$item['created_at'];echo "<br />";?>
    <?//=$item['id'];?>
    <?php echo '作者：';?>
    <?=$item['user']['name'];echo "<br />";?>
     <?
     $format = "select * from weibo where tid ='%s' ";
	 $search = sprintf($format,$item['idstr']);
	 $exist = $mysql -> getVar($search);
    ?>
	<? 
	$a =4;
	if (!$exist)
    {
	echo '<form action="vote.php" method="get"> 
	    <input type="hidden" name ="tid" value = "'.$item['idstr'].'"> 
		<input type="hidden" name ="text" value ="'.$item['text'].'" > 
		<input type="hidden" name ="uid" value="0" value ="'.$item['user']['idstr'].'"> 
		<input type="hidden" name ="created_at" value="0" value ="'.$item['created_at'].'">
		<input type="hidden" name ="screen_name" value="0" value ="'.$item['user']['screen_name'].'">
		<input type="hidden" name ="description" value="0" value ="'.$item['user']['description'].'">
		<input type="submit" name= "vote" value = "vote" >
    </form>'; 
   }
    else
	{
         $format1= "select agree from weibo where tid ='%s'";
		 $format2= "select disagree from weibo where tid ='%s'";
		 $search1= sprintf($format1,$item['idstr']);
		 $search2= sprintf($format2,$item['idstr']);
		 $agree= $mysql -> getVar($search1);
		 $disagree= $mysql -> getVar($search2);
		echo '<form style="display:inline;" action="handleagree.php" method="GET"> 
            <input type="hidden" name ="tid" value="'.$item['idstr'].'"> 
            <input type="submit" name="truth"  value="真相'.$agree.'">
			</form>
        <form style="display:inline;" action="handledisagree.php" method="GET">
            <input type="hidden" name ="tid" value="'.$item['idstr'].'"> 
            <input type="submit" name="rumor" value="谣言'. $disagree.'"> 
			</form>';
	}
    ?>
	<? 
	   $result="投票未结束！";
       if($agree > 10)
           $result="是真相";
       else if($disagree> 10)
           $result="是谣言";
       echo "<div>".$result."</div>";
    ?>
    <?php
    
 ?>
    
</div>

<?php endforeach; ?>
</div>

<?php
 $mysql->closeDb();
 endif; ?>

</body>
</html>
