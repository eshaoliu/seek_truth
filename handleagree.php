<?
   $pid = $_GET['pid'];
   $uid = $_GET['uid'];
   $agree = $_GET['agree'];
   $disagree = $_GET['disagree'];
   $result = $_GET['result'];
   $flag = $_GET['flag'];
   $mysql = new SaeMysql();
//$format= "select count(*) from vote where uid = '%s' and pid ='%s'";
//$search= sprintf($format,$uid,$pid);
//echo $search;
//echo $mysql -> runSql( $serach );
     try{	
     $format = "insert into vote(tid,uid,agree,flag) values('%s','%s','1','0') on duplicate key update agree=agree+1";
     $sql  = sprintf($format,$pid,$uid);
         //echo $sql;
         //echo 'begin';
     $mysql -> runSql( $sql );
     echo "vote";
         //echo 'end';
     /*if( $mysql->errno() != 0 )
     {
        die( "Error:" . $mysql->errmsg() );
     }*/
    }catch(exception $e){
    }
?>