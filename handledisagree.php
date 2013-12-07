<?
   $tid = $_GET['tid'];
   $mysql = new SaeMysql();
   try{	
	 $format ="UPDATE weibo SET disagree = disagree +1 where tid = %s";
	 $sql = sprintf($format ,$tid);
     $result =$mysql -> runSql( $sql );
     var_dump($result);
    }catch(exception $e){
    }
    echo '<meta http-equiv="refresh" content="0;url=weibolist.php">';
?>