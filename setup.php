<?php require 'config/db.php';
if($pdo->query('SELECT COUNT(*) FROM owners')->fetchColumn()>0)die('Setup already done. Delete setup.php, then go to <a href="login.php">login</a>.');
$pw=password_hash('ChangeMe@2026',PASSWORD_DEFAULT);
try{$pdo->beginTransaction();
$pdo->prepare('INSERT INTO owners (full_name,email,phone,password) VALUES (?,?,?,?)')->execute(['Safari Tours Owner','owner@safaritours.co.ke','0700000000',$pw]);$o=$pdo->lastInsertId();
$pdo->prepare('INSERT INTO businesses (owner_id,business_name,location,contact) VALUES (?,?,?,?)')->execute([$o,'Safari Tours Ltd','Nairobi','0700000000']);$b=$pdo->lastInsertId();
$pdo->prepare('INSERT INTO staff (business_id,full_name,username,email,password,role) VALUES (?,?,?,?,?,?)')->execute([$b,'System Admin','admin','admin@safaritours.co.ke',$pw,'admin']);
$pdo->commit();echo 'Done. Accounts created. Delete setup.php, then <a href="login.php">log in</a>.';
}catch(Exception $e){$pdo->rollBack();echo 'Failed: '.htmlspecialchars($e->getMessage());}
