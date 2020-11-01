<?php
if ($cn = odbc_connect("Access_db", "", "")) {
    print "Access接続ok！"."<br>";
} else {
    print "Access接続error！"."<br>";
}

if ($cn2 = mysqli_connect("localhost","root","root","test")) {
    print "Mysql接続ok！"."<br>";
} else {
    print "Mysql接続error！"."<br>";
}

mysqli_set_charset($cn2, "utf8");

$asql = "select * FROM tableb02"; 
$rs = odbc_exec($cn, $asql);
$writtendata = 0;
$errordata = array();
while(1){
    $po = odbc_fetch_array($rs);
	print_r($po);
    if (!$po) break;
    foreach ($po as $key => $value) $po[$key] = mb_convert_encoding($value, "UTF-8", "sjis-win");
	$stmt=mysqli_prepare($cn2,"INSERT INTO tableb02 
    (ID,class,num,date,teacherID)
    VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param
    ($stmt,'iiiss',
    $po["ID"], $po["class"], $po["num"],$po["date"], $po["teacherID"];
    $rs2 = mysqli_stmt_execute($stmt);

    if ($rs2) {
		$writtendata++;
        print("成功！"."<br>");
    } else {
        $errordata[] = $po["ID1"];
    }
}
echo $writtendata." data is written<br>";
if (!$errordata) {
	echo "no error";
}else {
	echo "error in: ";
	print_r($errordata);
}
odbc_close($cn);
mysqli_stmt_close($stmt);
mysqli_close($cn2);

$endTime = microtime(true);
echo "runtime = ".(($endTime-$startTime))." s";
?>
