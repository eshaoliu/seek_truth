<?
   $tid = $_GET['tid'];
   $uid = $_GET['uid'];
   $evidence = $_GET['evidence'];
   $mysql = new SaeMysql();
   try{	
	 $format1 ="select disagree from weibo where tid = %s";
	 $format2 ="UPDATE weibo SET disagree = disagree +1 where tid = %s";
	 $format3 ="UPDATE weibo SET flag=1 where tid = %s";
	 $format4 ="UPDATE weibo SET result=1 where tid = %s";
	 $format5 ="insert into evidence(tid,voter_uid,evidence,agree_disagree)values('%s','%s','%s','1')";
	 $format6 ="select count(*) from evidence where tid =%s and voter_uid =%s";
	 $format7 ="UPDATE usrinfo SET effective=effective+1 where uid in (select voter_uid from evidence where tid='%s' and agree_disagree=1)";
	 $format8 ="UPDATE usrinfo SET not_effective=not_effective+1 where uid in (select voter_uid from evidence where tid='%s' and agree_disagree=0)";
	 $sql1 = sprintf($format1 ,$tid);
	 $sql2 = sprintf($format2 ,$tid);
	 $sql3 = sprintf($format3 ,$tid);  
	 $sql4 = sprintf($format4 ,$tid);
	 $sql5 = sprintf($format5 ,$tid,$uid, $evidence);
	  $sql6 = sprintf($format6,$tid,$uid);
	 $num =$mysql -> getVar( $sql1);
	  $num1 =$mysql -> getVar( $sql6);
	 if($num1 == 0)
     {
		$result3 =$mysql -> runSql( $sql2);
		$mysql ->runSql($sql5);
		 if($num>=3)
		 {
			$result1 =$mysql -> runSql( $sql3);
			$result2 =$mysql -> runSql( $sql4);
			$sql7=sprintf($format7,$tid);
			$sql8=sprintf($format8,$tid);
			$mysql -> runSql( $sql7);
			$mysql -> runSql( $sql8);
		 }
	}
	else
	{
	     echo '<script type ="text/javascript"> alert("已投票,不能重复投票")</script>';  
	}
	}
	catch(exception $e){
	}
	$mysql->closeDb();
    echo '<meta http-equiv="refresh" content="0;url=weibolist.php">';
	
?>