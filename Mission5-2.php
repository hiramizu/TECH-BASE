<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mission5-1</title>
</head>

<body>
<?php
// データベースの設置
$dsn = 'データベース名 ';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS Mission5_1"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "date TEXT,"
. "pass TEXT"
.");";
$stmt = $pdo->query($sql);
    
// 投稿機能
   if(!empty($_POST["submit"])){   
         
         
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["hidden"]) && !empty($_POST["pass"])){
            $name= $_POST["name"];      
            $comment= $_POST["str"];       
            $pass= $_POST["pass"]; 
            $date= date("Y/m/d H:i:s");
            $sql = $pdo -> prepare("INSERT INTO Mission5_1(name,comment,date,pass) VALUES(:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment',$comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
         
        }elseif(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["hidden"])){
            $id = $_POST["hidden"];   
            $name = $_POST["name"];
            $comment = $_POST["str"];
            $pass = $_POST["pass"];
            $date= date("Y/m/d H:i:s");
            $sql = 'UPDATE Mission5_1 SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
            $stmt = $pdo ->prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment',$comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
        }

//削除機能       
    }elseif(!empty($_POST["delete"])){
        $deletenum= $_POST["deletenum"];
        $deletepass= $_POST["deletepass"];
        
        if(!empty($deletenum)&&!empty($deletepass)){
            $id = $_POST["deletenum"]; 
            $sql = 'select * from Mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                
                if($deletenum==$row['id']){
                
                    if($deletepass==$row['pass']){ 
                        $id = $_POST["deletenum"];
                        $sql = 'delete from Mission5_1 where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    
                    }elseif($deletepass!=$row['pass']){
                        echo "パスワードが違います。";
                    }
                
                }
            }
        }

//編集機能
    }elseif(!empty($_POST["edit"])){
        $editnum=$_POST["editnum"];
        $editpass= $_POST["editpass"];
        
        if(!empty($editnum)&&!empty($editpass)){
            $id = $_POST["editnum"]; 
            $sql = 'select * from Mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                
                if($editnum==$row['id']){
                    
                    if($editpass==$row['pass']){
                        $id = $_POST["editnum"];  
                        $sql = 'SELECT * FROM Mission5_1 WHERE id=:id ';
                        $stmt = $pdo->prepare($sql);                 
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                        $stmt->execute();                           
                        $results = $stmt->fetchAll(); 
                        foreach ($results as $row){ 
                            $editnumber = $row['id'];
                            $editname = $row['name'];
                            $editstr = $row['comment'];
                            $editpass = $row['pass'];
                        }
                    }elseif($editpass!=$row['pass']){
                             echo "パスワードが違います。";
                    }
                }
            }
    
        }
    
    }

?>


    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value = <?php if(!empty($editname)){ echo $editname;}?>><br>
    <input type="text" name="str" placeholder="コメント" value = <?php if(!empty($editstr)){ echo $editstr;}?>>
    <input type="hidden" name="hidden" value= <?php if(!empty($editnumber)){echo $editnumber;}?>> <br>
    <input type="text" name="pass" placeholder="パスワード" value = <?php if(!empty($editpass)){ echo $editpass;}?>>
    <input type="submit" name="submit"><br>
    <br>
    <input type="text" name="deletenum" placeholder="削除対象番号"><br>
    <input type="text" name="deletepass" placeholder="パスワード">
    <input type="submit" name="delete" value="削除"><br>
    <br>
    <input type="text" name="editnum" placeholder="編集対象番号"><br>
    <input type="text" name="editpass" placeholder="パスワード">
    <input type="submit" name="edit" value="編集">
    </form>
    
<?php

    
$sql = 'SELECT * FROM Mission5_1';
$stmt = $pdo -> query($sql);
$results = $stmt ->fetchALL();
foreach($results as $row){
    echo $row['id'].': ';
    echo $row['name'].', ';
    echo $row['comment'].', ';
    echo $row['date'].'<br>';
    echo "<hr>";             
}
?>
</body>
</html>