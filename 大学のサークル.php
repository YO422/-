<?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS mis5"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date TEXT,"
            . "pass TEXT"
            .");";
            $stmt = $pdo->query($sql);

        
        if ( !empty($_POST["editpass"]) )
        {
            $editpass = $_POST["editpass"];

            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                if( $row['pass'] == $editpass )
                {
                    $key = 0;
                }
            }
        }
        
        if( isset($key) )
        {
            $editnum = $_POST["editnum"];

            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                if($row['id'] == $editnum)
                {
                    $editnum2 = $row['id'];
                    $editname2 = $row['name'];
                    $editcomment2 = $row['comment'];
                }
            }
            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
        }
        
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>大学のサークル</title>
</head>
<body>
    大学のサークル
    <form action="" method="post">
        
        <input type="hidden" name="editnum3" value=
        "<?php 
            if( isset ($editnum) )
            {
                echo $editnum2;
            }
        ?>"><br>
        
        <input type="text" name="name" placeholder="名前" value=
        "<?php
            if( isset ($editnum) )
            {
                echo $editname2;
            }
        ?>"><br>
        
        <input type="text" name="str" placeholder="コメント" value=
        "<?php
            if( isset ($editnum) )
            {
                echo $editcomment2;
            }
        ?>"><br>
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit"><br><br>
        
        <input type="number" name="num" placeholder="削除対象番号"><br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br><br>
        
        <input type="number" name="editnum" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    
    <?php
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        if( !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"]) && empty($_POST["editnum3"]) )
        {
            $sql = $pdo -> prepare("INSERT INTO mis5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $name = $_POST["name"];
            $comment = $_POST["str"];
            $date = date("Y年m月d日 H時i分s秒");
            $pass = $_POST["pass"];

            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

            $sql -> execute();
                      
            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
        }

        else if( !empty($_POST["num"]) && !empty($_POST["delpass"]) )
        {
            $num = $_POST["num"];
            $delpass = $_POST["delpass"];

            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                if( $row['pass'] == $delpass )
                {
                    if( $row['id'] == $num )
                    {
                        $key1 = 0;
                    }
                }
            }

            if( isset($key1) )
            {
                $id = $num;
                $sql = 'delete from mis5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
        }
        else if( !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["editnum3"]) && !empty($_POST["pass"])  )
        {
            $newnum = $_POST["editnum3"];
            $newname = $_POST["name"];
            $newstr = $_POST["str"];
            $newpass = $_POST["pass"];
            
            $id = $newnum; //変更する投稿番号
            $name = $newname;
            $comment = $newstr; //変更したい名前、変更したいコメントは自分で決めること
            $date = date("Y年m月d日 H時i分s秒");
            $pass = $newpass;

            $sql = 'UPDATE mis5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'SELECT * FROM mis5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row)
            {
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
        }
        else
        {
        }
    ?>
    
</body>
</html>