<?php require 'includes/boot.php';need_login();$b=$_SESSION['bid'];$can=!is_owner();
if($_SERVER['REQUEST_METHOD']==='POST'&&$can){
 $iid=(int)$_POST['invoice_id'];$c=(int)round((float)$_POST['amount']*100);$m=trim($_POST['method']);$d=$_POST['date']?:date('Y-m-d');
 try{$pdo->beginTransaction();
  $i=q('SELECT i.* FROM invoices i JOIN customers c ON c.customer_id=i.customer_id WHERE i.invoice_id=? AND c.business_id=? FOR UPDATE',[$iid,$b])->fetch();
  if(!$i)throw new Exception('Choose an invoice.');
  $bal=(int)round(($i['amount']-$i['amount_paid'])*100);
  if($bal<=0)throw new Exception('This invoice is already fully paid.');
  if($c<=0)throw new Exception('Enter an amount above zero.');
  if($c>$bal)throw new Exception('The payment is more than the balance of '.money($bal/100).'.');
  $new=round($i['amount_paid']+$c/100,2);$status=$c===$bal?'Paid':'Partial';
  $n='RCT-'.str_pad(q('SELECT COALESCE(MAX(receipt_id),0)+1 FROM receipts')->fetchColumn(),5,'0',STR_PAD_LEFT);
  q('INSERT INTO receipts (receipt_number,invoice_id,staff_id,amount_paid,payment_method,receipt_date) VALUES (?,?,?,?,?,?)',[$n,$iid,$_SESSION['uid'],$c/100,$m,$d]);
  q('UPDATE invoices SET amount_paid=?,payment_status=? WHERE invoice_id=?',[$new,$status,$iid]);
  audit('Payment recorded on '.$i['invoice_number'],money($i['amount_paid']),money($new));
  $pdo->commit();flash("Receipt $n issued. Invoice is now $status.");
 }catch(Exception $e){if($pdo->inTransaction())$pdo->rollBack();flash($e->getMessage(),'err');}
 header('Location: receipts.php');exit;}
$open=q("SELECT i.invoice_id,i.invoice_number,i.amount,i.amount_paid,c.full_name FROM invoices i JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=? AND i.payment_status<>'Paid' ORDER BY i.invoice_id DESC",[$b])->fetchAll();
$rows=q('SELECT r.*,i.invoice_number,c.full_name cn FROM receipts r JOIN invoices i ON i.invoice_id=r.invoice_id JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=? ORDER BY r.receipt_id DESC LIMIT 50',[$b])->fetchAll();
page_top('Payments');
if($can):?><div class="card"><h2>Record a payment</h2><?php if(!$open):?><p>No unpaid invoices right now.</p><?php else:?>
<form method="post"><?=csrf()?><div class="row"><div><label>Invoice</label><select name="invoice_id"><?php foreach($open as $o):?><option value="<?=$o['invoice_id']?>" <?=(int)($_GET['invoice']??0)===(int)$o['invoice_id']?'selected':''?>><?=e($o['invoice_number'].' - '.$o['full_name'].' (balance '.money($o['amount']-$o['amount_paid']).')')?></option><?php endforeach;?></select></div>
<div><label>Amount paid (KSh)</label><input type="number" step="0.01" min="0.01" name="amount" required></div>
<div><label>Method</label><select name="method"><option>M-Pesa</option><option>Cash</option><option>Bank transfer</option><option>Card</option></select></div>
<div><label>Date</label><input type="date" name="date" value="<?=date('Y-m-d')?>"></div></div><button class="btn">Save payment</button></form><?php endif;?></div>
<?php else:?><div class="msg ok">You are logged in as owner, so you can view payments. Log in with a staff account to record them.</div><?php endif;?>
<div class="card"><h2>Recent receipts</h2><table><tr><th>Receipt</th><th>Invoice</th><th>Customer</th><th>Method</th><th>Date</th><th class="n">Amount</th></tr>
<?php foreach($rows as $r):?><tr><td><?=e($r['receipt_number'])?></td><td><?=e($r['invoice_number'])?></td><td><?=e($r['cn'])?></td><td><?=e($r['payment_method'])?></td><td><?=e($r['receipt_date'])?></td><td class="n"><?=money($r['amount_paid'])?></td></tr>
<?php endforeach;if(!$rows):?><tr><td colspan="6">No payments recorded yet.</td></tr><?php endif;?></table></div>
<?php page_bottom();
