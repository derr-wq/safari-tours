<?php require 'includes/boot.php';need_login();if(!can_manage()){header('Location: dashboard.php');exit;}$b=$_SESSION['bid'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $n=trim($_POST['full_name']);$u=trim($_POST['username']);$pw=$_POST['password'];$r=$_POST['role']==='admin'?'admin':'staff';
 if($n===''||$u==='')flash('Enter the name and username.','err');
 elseif(strlen($pw)<6)flash('The password must have at least 6 characters.','err');
 elseif(q('SELECT 1 FROM staff WHERE username=?',[$u])->fetch())flash('That username is already taken.','err');
 else{q('INSERT INTO staff (business_id,full_name,username,email,password,role) VALUES (?,?,?,?,?,?)',[$b,$n,$u,trim($_POST['email']),password_hash($pw,PASSWORD_DEFAULT),$r]);audit('Staff account created',null,$u);flash("Staff account $u created.");}
 header('Location: staff.php');exit;}
$rows=q('SELECT * FROM staff WHERE business_id=? ORDER BY full_name',[$b])->fetchAll();
page_top('Staff');?>
<div class="card"><h2>Add a staff member</h2><form method="post"><?=csrf()?><div class="row"><div><label>Full name</label><input name="full_name" required></div><div><label>Username</label><input name="username" required></div>
<div><label>Email</label><input type="email" name="email"></div><div><label>Password</label><input type="password" name="password" required minlength="6"></div>
<div><label>Role</label><select name="role"><option value="staff">Staff</option><option value="admin">Admin</option></select></div></div><button class="btn">Add staff member</button></form></div>
<div class="card"><table><tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th></tr>
<?php foreach($rows as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['username'])?></td><td><?=e($r['email'])?></td><td><?=e(ucfirst($r['role']))?></td></tr><?php endforeach;?></table></div>
<?php page_bottom();
