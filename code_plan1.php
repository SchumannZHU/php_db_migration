<?php
function read_data_from_access ($startID, $step, $cn) {
	$asql = "select * FROM table01 where ID1>=" . (string)$startID . " and ID1<" . (string)($startID + $step); 
	$rs = odbc_exec($cn, $asql);
	$strkeys = array("ID", "name", "IP", "logintime");
	$wdata = 0;
	while(1){
		$po = odbc_fetch_array($rs);
		if (!$po) break;
		$postr = "";
		foreach ($po as $key => $value) {
			$value = mb_convert_encoding($value, "UTF-8", "sjis-win");
			if (in_array($key, $strkeys)) {
        			//COMMAND: $postr is a data set
				$postr .= "'".$value."'";
			} elseif( $value === '' ) {			
				$postr .= "NULL";
			} else {
				$postr .= $value;
			}
			if ($key != "manager") $postr .= ",";
		}
		if ($wdata == 0) {
			$sql = "INSERT INTO tb01_backup (Access_ID,ID,class,name,logintime,IP,flg,manager) ".
				"VALUES (" . $postr . ")";
		} else {
			$sql .= ",(" . $postr . ")";
		}
		$wdata++;
	}
	return array($sql, $wdata);
}

set_time_limit(0);
$startTime = microtime(true);
if ($cn_acs = odbc_connect("Access_db", "", "")) {
    print "Access接続ok！"."<br>";
} else {
    print "Access接続error！"."<br>";
}
if ($cn_msq = mysqli_connect("localhost","root","root","test")) {
    print "Mysql接続ok！"."<br>";
} else {
    print "Mysql接続error！"."<br>";
}
mysqli_set_charset( $cn_msq, "utf8" );
$data_num = 0;
$IDstart = 1;
$IDend = 200000;
$rwStep = 5000;
while ($IDstart < $IDend) {
	$read_data = read_data_from_access ($IDstart, $rwStep, $cn_acs);
	$rs2 = mysqli_query( $cn_msq, $read_data[0] );
	if ($rs2) {
		$data_num += $read_data[1];
	} else {
		echo "error from " . (string)$IDstart . " to " . (string)($IDstart + $rwStep) . "<br>";
	}
	$IDstart += $rwStep;
}
echo $data_num . " data is written <br>";
odbc_close($cn_acs);
mysqli_stmt_close($stmt);
mysqli_close($cn_msq);

$endTime = microtime(true);
echo "runtime = ".(($endTime-$startTime)*1000)." ms";
?>
