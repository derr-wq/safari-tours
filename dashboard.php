<?php require 'includes/boot.php';need_login();$b=$_SESSION['bid'];
$t=q('SELECT COALESCE(SUM(i.amount),0) inv,COALESCE(SUM(i.amount_paid),0) paid,COUNT(*) n FROM invoices i JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=?',[$b])->fetch();
$st=q("SELECT i.payment_status s,COUNT(*) n FROM invoices i JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=? GROUP BY i.payment_status",[$b])->fetchAll(PDO::FETCH_KEY_PAIR);
$mo=array_reverse(q("SELECT DATE_FORMAT(r.receipt_date,'%b %Y') m,SUM(r.amount_paid) s FROM receipts r JOIN invoices i ON i.invoice_id=r.invoice_id JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=? GROUP BY DATE_FORMAT(r.receipt_date,'%Y-%m'),m ORDER BY DATE_FORMAT(r.receipt_date,'%Y-%m') DESC LIMIT 6",[$b])->fetchAll());
$ps=q('SELECT s.full_name,COUNT(*) n,SUM(i.amount) t FROM invoices i JOIN staff s ON s.staff_id=i.staff_id WHERE s.business_id=? GROUP BY s.staff_id,s.full_name ORDER BY t DESC',[$b])->fetchAll();
$rc=q('SELECT r.receipt_number,r.amount_paid,r.receipt_date,c.full_name FROM receipts r JOIN invoices i ON i.invoice_id=r.invoice_id JOIN customers c ON c.customer_id=i.customer_id WHERE c.business_id=? ORDER BY r.receipt_id DESC LIMIT 5',[$b])->fetchAll();
$out=$t['inv']-$t['paid'];$rate=$t['inv']>0?round($t['paid']/$t['inv']*100):0;$mx=max(array_column($mo,'s')?:[1]);
page_top('Dashboard');?>
<div class="grid"><div class="stat hero"><i class="si">&#128176;</i><span>Collected so far</span><b><?=money($t['paid'])?></b></div>
<div class="stat"><i class="si">&#129534;</i><span>Total invoiced (<?=$t['n']?> invoices)</span><b><?=money($t['inv'])?></b></div>
<div class="stat"><i class="si">&#9203;</i><span>Outstanding balance</span><b><?=money($out)?></b></div>
<div class="stat"><i class="si">&#128200;</i><span>Share of invoiced money collected</span><b><?=$rate?>%</b></div></div>

<div class="split">
<div class="card"><h2>Collection rate</h2><div class="ring" style="--p:<?=$rate?>"><b><?=$rate?>%</b></div><p class="center">of invoiced money has been collected</p></div>
<div class="card"><h2>Invoices by status</h2><?php foreach(['Pending','Partial','Paid'] as $s):?><div class="srow"><span class="badge <?=$s?>"><?=$s?></span><b><?=$st[$s]??0?> invoices</b></div><?php endforeach;?></div>
</div>

<div class="split">
<div class="card"><h2>Money collected per month</h2><?php if(!$mo):?><p>No payments recorded yet.</p><?php endif;foreach($mo as $r):?><div class="bar"><span><?=e($r['m'])?></span><i style="width:<?=max(3,round($r['s']/$mx*60)) ?>%"></i><?=money($r['s'])?></div><?php endforeach;?></div>
<div class="card"><h2>Recent payments</h2><?php if(!$rc):?><p>No payments recorded yet.</p><?php endif;foreach($rc as $r):?><div class="srow"><div><?=e($r['full_name'])?><small><?=e($r['receipt_number'])?> &middot; <?=e(date('j M Y',strtotime($r['receipt_date'])))?></small></div><b><?=money($r['amount_paid'])?></b></div><?php endforeach;?></div>
</div>

<div class="card"><h2>Invoices issued per staff member</h2><table><tr><th>Staff</th><th class="n">Invoices</th><th class="n">Value</th></tr>
<?php foreach($ps as $r):?><tr><td><?=e($r['full_name'])?></td><td class="n"><?=$r['n']?></td><td class="n"><?=money($r['t'])?></td></tr><?php endforeach;if(!$ps):?><tr><td colspan="3">No invoices issued yet.</td></tr><?php endif;?></table></div>
<?php page_bottom();