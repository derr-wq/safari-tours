<?php require 'includes/boot.php';need_login();$b=$_SESSION['bid'];$can=!is_owner();
if($_SERVER['REQUEST_METHOD']==='POST'&&$can){
 $cid=(int)$_POST['customer_id'];$amt=round((float)$_POST['amount'],2);$d=$_POST['issue_date']?:date('Y-m-d');
 if(!q('SELECT 1 FROM customers WHERE customer_id=? AND business_id=?',[$cid,$b])->fetch())flash('Choose a customer.','err');
 elseif($amt<=0)flash('Enter an amount above zero.','err');
 else{$n='INV-'.str_pad(q('SELECT COALESCE(MAX(invoice_id),0)+1 FROM invoices')->fetchColumn(),5,'0',STR_PAD_LEFT);
  q('INSERT INTO invoices (customer_id,staff_id,invoice_number,service_description,amount,issue_date) VALUES (?,?,?,?,?,?)',[$cid,$_SESSION['uid'],$n,trim($_POST['service_description']),$amt,$d]);
  audit('Invoice created',null,$n.' - '.money($amt));flash("Invoice $n created.");}
 header('Location: invoices.php');exit;}
$cs=q('SELECT customer_id,full_name FROM customers WHERE business_id=? ORDER BY full_name',[$b])->fetchAll();
$rows=q('SELECT i.*,c.full_name cn,s.full_name sn FROM invoices i JOIN customers c ON c.customer_id=i.customer_id JOIN staff s ON s.staff_id=i.staff_id WHERE c.business_id=? ORDER BY i.invoice_id DESC',[$b])->fetchAll();
page_top('Invoices');
if($can):?><div class="card"><h2>Create an invoice</h2><?php if(!$cs):?><p>Add a customer first on the <a href="customers.php">Customers</a> page.</p><?php else:?>
<form method="post"><?=csrf()?><div class="row"><div><label>Customer</label><select name="customer_id" required><?php foreach($cs as $c):?><option value="<?=$c['customer_id']?>"><?=e($c['full_name'])?></option><?php endforeach;?></select></div>
<div><label>Amount (KSh)</label><input type="number" step="0.01" min="0.01" name="amount" required></div><div><label>Issue date</label><input type="date" name="issue_date" value="<?=date('Y-m-d')?>"></div></div>
<div class="row" style="grid-template-columns:1fr"><div><label>Service sold</label><input name="service_description" placeholder="e.g. 3-day Maasai Mara safari for 2 guests"></div></div>
<button class="btn">Create invoice</button></form><?php endif;?></div>
<?php else:?><div class="msg ok">You are logged in as owner, so you can view invoices. Log in with a staff account to issue them.</div><?php endif;?>
<div class="card"><table><tr><th>Number</th><th>Customer</th><th>Date</th><th class="n">Amount</th><th class="n">Paid</th><th class="n">Balance</th><th>Status</th><th></th></tr>
<?php foreach($rows as $r):?><tr><td title="<?=e($r['service_description'])?>"><?=e($r['invoice_number'])?></td><td><?=e($r['cn'])?></td><td><?=e($r['issue_date'])?></td>
<td class="n"><?=money($r['amount'])?></td><td class="n"><?=money($r['amount_paid'])?></td><td class="n"><?=money($r['amount']-$r['amount_paid'])?></td>
<td><span class="badge <?=$r['payment_status']?>"><?=$r['payment_status']?></span></td><td><?php if($can&&$r['payment_status']!=='Paid'):?><a class="btn x" href="receipts.php?invoice=<?=$r['invoice_id']?>">Record payment</a><?php endif;?></td></tr>
<?php endforeach;if(!$rows):?><tr><td colspan="8">No invoices yet.</td></tr><?php endif;?></table></div>
<?php page_bottom();
