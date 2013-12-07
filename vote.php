 <?  
   
    $mysql = new SaeMysql();
    try{	
     $format = "insert into weibo(tid,text,uid,time,agree,disagree,result,flag) values('%s','%s','%s','%s',0,0,'假象',0)";
     $format1 = "insert into usrinfo(uid,screen_name,description) values('%s','%s','%s')";
     $sql  = sprintf($format,$_GET['tid'],$_GET['text'],$_GET['uid'],$_GET['created_at']);
     $sql1 = sprintf($format1,$_GET['uid'],$_GET['screen_name'],$_GET['description']);
     $mysql -> runSql( $sql );
     $mysql -> runSql( $sql1); 
    }catch(exception $e){
    }
	/*if( isset($_GET['text']) ) {
	$ret = $c->update( $_GET['text'].'这是不是真的');	
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	}
	else {
		echo "<p>发送成功</p>";
	}
}*/
	 echo '<meta http-equiv="refresh" content="0;url=weibolist.php">';
?>
