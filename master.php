<?php
$conn2=odbc_connect("db_access","","");
if($conn2){
  print("Accessに接続した！<br>");
}else{
  print("Access-Error!<br>");
}
$conn=mysqli_connect("localhost","root","root","db_mysql");
if($conn2){
  print("MySQLに接続した！<br>");
}else{
  print("MySQL-Error!<br>");
}
mysqli_set_charset($conn, "utf8");
if($_POST["find"]){
    echo"読み取り";
    echo $_POST["ID"];
    $research="select * from tableb01 where id = '".$_POST["ID"]."'";
    /* $stmt = mysqli_prepare($conn2,$research);
    $rs = mysqli_stmt_execute($stmt);*/
    $rs = mysqli_query($conn,$research);
    print_r($rs);
    //mysqli_resultを返すint $current_field ;int $field_count;array $lengths;int $num_rows;
    if ($rs) {
	    echo"探しました!";
  	} else {
	    echo"失敗ちゃった！";   
	  }
   $result=mysqli_fetch_array($rs, MYSQLI_ASSOC);
   print_r($result);
   foreach($result as $key => $value){
       echo $key. "is".$value."<br>";
       if ($key == 'name' or $key == 'email') {
           $_POST[$key] = $value;
       }
       if ( $result == "" ) break;
   }
    /*
    $_POST['name'] = $result['name'];    
    $_POST['email'] = $result['email'];  
    
    ↓ACCESSから読み取る
    $rs2=odbc_exec($conn2,$research);
    $result2=odbc_fetch_array($rs2);
    foreach($result2 as $key2 => $value2){
       if ($key2 == 'name' or $key2 == 'email') {
           $_POST[$key2] = $value2;
       }
    }
    */
}
if($_POST["sso"]=="no"){
        $_POST["sso"]=1;
    }else{
        $_POST["sso"]=null;
    }
print("<br>".$_POST["sso"]);    

if($_POST["register"]){
    echo"登録あるいは更新";
    echo "<br>";
    echo $_POST["ID"];
    echo "<br>";
    $research="select * from tb00 where id = '".$_POST["ID"]."'"; 
    $rs = mysqli_query($conn,$research);
    print_r($rs);
    echo "<br>"; 
    echo 'fieldcount is '. $rs -> field_count . "<br>";
    echo 'numrows is '. $rs -> num_rows . "<br>";
   
    if($rs -> num_rows == 0){//新規登録,class
        $insert ="insert into tb00 (id, name, pass, email,flg) values (?,?,?,?,?)";
        //'$_POST['ID']','$_POST['name']','$_POST['pw']','$_POST['email']')" ;
        $stmt = mysqli_prepare($conn,$insert);
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $_POST["ID"], $_POST['name'],$_POST['pw'],$_POST['email'],$_POST["sso"]);
        $exec=mysqli_stmt_execute($stmt);
        if($exec){
            echo"新規登録した！";
        }else{
            echo"新規登録できませんでした。";
        }
     }else{
         $update="update tb00 set name='".$_POST['name']."',pass='".$_POST['pw']."',email='".$_POST['email']."'
         where id='".$_POST["ID"]."'";
         $doupdate = mysqli_query($conn,$update);
         if($doupdate){
             echo"更新した！";
         }else{
             echo"更新できませんでした。";
         }
     }
}

if($_POST["delete"]){
    echo "<script>var confirmdelete = confirm(\"削除でよろしいですか\");</script>";
    //echo "<script> if (confirmdelete == true) confirmdelete = Number('1');</script>";
    $cfmdlt = "<script>document.write(confirmdelete);</script>";//47
    echo "confirm is [" . $cfmdlt . "], type is [". gettype($cfmdlt) ."]<br>";
    var_dump($cfmdlt);echo"<br>";
    //$boolean = strtolower($cfmdlt) == 'true' ? true : false;
    //var_dump($boolean);
    
    if($cfmdlt[0]=="t"){
    echo"delete<br>";
    $delete="DELETE FROM tb00 WHERE id='".$_POST["ID"]."'" ;
    $dodelete = mysqli_query($conn,$delete);
        if($dodelete){
             echo"削除した！";
        }else{
             echo"削除できませんでした。";
        }
    }else{
     echo"cancel";
    }
}
odbc_close($conn);
mysqli_stmt_close($conn2);
mysqli_close($conn2);
?>

<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width'>
    <title>マスター</title>
    <style>
    table,tr,td,th{ 
    　border-collapse: collapse;
      border-top: 5px solid #7fffd4;
      border-bottom: 5px solid #e6e6fa;
      border-right: 5px solid #adff2f;
      border-left: 5px solid #ffc0cb;
      line-height: 1.5;
    }
    th
    { background-color: #ffff7a;
    }
    button
    { background-color: #e0ebaf;
      border: 5px solid #afeeee;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      color: #000000;
      display: inline-block;
      font-size: 13px;
      text-align: center;
      text-decoration: none;
      transition: 0.2s ease-in-out;
      -o-transition: 0.2s ease-in-out;
      -moz-transition: 0.2s ease-in-out;
      -webkit-transition: 0.2s ease-in-out;
    }
    .style1{
     white-space: nowrap; 
    }
    .cc{
     vertical-align:middle;
     text-align: center;
    }
    .button{
     background-color: #e0ebaf;
     border-style: none;
     font-family:"MS Pゴシック;"
    }
    </style>
</head>
<body>
    <form method="POST" name="masterform" action="new-master.php" >
<!--method="POST" name="masterform" action="formpost.php"-->
      <table style="width:400px" align="center">
	<tr>
	  　<th colspan=2 align="center">
	      マスターDBの管理
	    </th> 
　　　　<tr>
　　　　　　<td class="style1 cc">
	     	ID(半角数字)
	    </td>
	    <td class="style1">
	    	<input type="text" name="ID" pattern="^[0-9A-Za-z]+$" value="<?=$_POST['ID']?>" required>
		    <input type="submit" class="button" name="find" value="マスターから読み取る">
	    </td>
	</tr>
	<tr>
	    <td class="style1 cc">
		名前(全角)
	    </td>
	    <td class="style1">
		<input type="text" name="name" value="<?=$_POST['name']?>">  pass　<input type="text" name="pw" > 
　　　　　　</td>
	<tr>	
	    <td class="style1 cc">
		SSOにアカウント
	    </td>
	    <td class="style1">
		<input type="radio" name="sso" value="yes">あり   <input type="radio" name="sso" value="no">ない
	　　</td>
	</tr> 
	<tr>
	    <td class="style1 cc">
		E-mail
	    </td>
	    <td class="style1">
		<input type="text" name="email" style="width:350px" value="<?=$_POST['email']?>">  
　　　　　　</td>
	</tr> 
	<tr>
	    <td colspan=2 class="style1">
	     ・SSOにアカウントが登録されている場合<br>
　　          SSOにアカウントありを選択し，IDに教職員コードを入力しPass欄は空欄とする。<br>
             ・SSOにアカウントが未登録の場合<br>
　　          SSOにアカウントなしを選択し，IDおよびPassを入力する。<br>	
	    </td>
	</tr>
	<tr>	
	    <td colspan=2 style="text-align:right;" class="style1">
	        <input type="submit" class="button" name="register" value="新規登録＆更新">　
		    <input type="submit" class="button" name="delete" value="マスターから削除" >
	    </td>
	</tr> 
　　    </table>
　　</form>
</body>
</html>
