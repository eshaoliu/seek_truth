<?
   $tid = $_GET['tid'];
   $mysql = new SaeMysql();
   try{	
	 $format ="UPDATE weibo SET agree = agree +1 where tid = %s";
	 $sql = sprintf($format ,$tid);
     $result =$mysql -> runSql( $sql );
    }catch(exception $e){
    }
    echo '<meta http-equiv="refresh" content="0;url=weibolist.php">';
?>