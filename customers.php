<?php require 'includes/boot.php';need_login();$b=$_SESSION['bid'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $a=$_POST['do']??'';$id=(int)($_POST['id']??0);
 if($a==='save'){$f=[trim($_POST['full_name']),trim($_POST['email']),trim($_POST['phone']),trim($_POST['address'])];
  if($f[0]==='')flash('Enter the customer name.','err');
  elseif($id){q('UPDATE customers SET full_name=?,email=?,phone=?,address=? WHERE customer_id=? AND business_id=?',[...$f,$id,$b]);flash('Customer updated.');}
  else{q('INSERT INTO customers (business_id,full_name,email,phone,address) VALUES (?,?,?,?,?)',[$b,...$f]);flash('Customer added.');}}
 if($a==='del'){
  if(q('SELECT COUNT(*) FROM invoices WHERE customer_id=?',[$id])->fetchColumn()>0)flash('This customer has invoices, so they cannot be deleted.','err');
  else{q('DELETE FROM customers WHERE customer_id=? AND business_id=?',[$id,$b]);flash('Customer deleted.');}}
 header('Location: customers.php');exit;}
$ed=isset($_GET['edit'])?q('SELECT * FROM customers WHERE customer_id=? AND business_id=?',[(int)$_GET['edit'],$b])->fetch():null;
$s=trim($_GET['s']??'');$like="%$s%";
$rows=q('SELECT * FROM customers WHERE business_id=? AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ?) ORDER BY full_name',[$b,$like,$like,$like])->fetchAll();
page_top('Customers');?>
<div class="card"><h2><?=$ed?'Edit customer':'Add a customer'?></h2><form method="post"><?=csrf()?><input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?=$ed['customer_id']??0?>">
<div class="row"><div><label>Full name</label><input name="full_name" required value="<?=e($ed['full_name']??'')?>"></div><div><label>Phone</label><input name="phone" value="<?=e($ed['phone']??'')?>"></div>
<div><label>Email</label><input type="email" name="email" value="<?=e($ed['email']??'')?>"></div><div><label>Address</label><input name="address" value="<?=e($ed['address']??'')?>"></div></div>
<button class="btn"><?=$ed?'Save changes':'Add customer'?></button> <?php if($ed):?><a class="btn s" href="customers.php">Cancel</a><?php endif;?></form></div>
<div class="card"><form method="get" style="display:flex;gap:10px;margin-bottom:12px"><input name="s" placeholder="Search by name, phone or email" value="<?=e($s)?>"><button class="btn s">Search</button></form>
<table><tr><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th></th></tr>
<?php foreach($rows as $r):?><tr><td><?=e($r['full_name'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['email'])?></td><td><?=e($r['address'])?></td>
<td class="n"><a class="btn s x" href="?edit=<?=$r['customer_id']?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete this customer?')"><?=csrf()?><input type="hidden" name="do" value="del"><input type="hidden" name="id" value="<?=$r['customer_id']?>"><button class="btn d x">Delete</button></form></td></tr>
<?php endforeach;if(!$rows):?><tr><td colspan="5">No customers found. Add your first customer above.</td></tr><?php endif;?></table></div>
<?php page_bottom();
