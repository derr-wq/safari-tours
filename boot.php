<?php
session_start();date_default_timezone_set('Africa/Nairobi');
require __DIR__.'/../config/db.php';
if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(16));
if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals($_SESSION['csrf'],$_POST['csrf']??''))die('Session expired. Go back and refresh the page.');
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function money($n){return 'KSh '.number_format((float)$n,2);}
function csrf(){return '<input type="hidden" name="csrf" value="'.$_SESSION['csrf'].'">';}
function q($sql,$p=[]){global $pdo;$s=$pdo->prepare($sql);$s->execute($p);return $s;}
function flash($m=null,$t='ok'){if($m!==null){$_SESSION['f']=[$m,$t];return;}$f=$_SESSION['f']??null;unset($_SESSION['f']);return $f;}
function need_login(){if(empty($_SESSION['uid'])){header('Location: login.php');exit;}}
function is_owner(){return ($_SESSION['type']??'')==='owner';}
function can_manage(){return is_owner()||($_SESSION['role']??'')==='admin';}
function audit($act,$old=null,$new=null){$o=is_owner();q('INSERT INTO audit_logs (staff_id,owner_id,activity,old_value,new_value) VALUES (?,?,?,?,?)',[$o?null:$_SESSION['uid'],$o?$_SESSION['uid']:null,$act,$old,$new]);}
function page_top($title){need_login();$cur=basename($_SERVER['SCRIPT_NAME']);
$nav=['dashboard.php'=>['Dashboard','&#128202;'],'customers.php'=>['Customers','&#128101;'],'invoices.php'=>['Invoices','&#129534;'],'receipts.php'=>['Payments','&#128179;']];
if(can_manage())$nav['staff.php']=['Staff','&#128188;'];
$first=explode(' ',trim($_SESSION['name']))[0];$h=(int)date('G');$g=$h<12?'Good morning':($h<17?'Good afternoon':'Good evening');?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> - Safari Tours</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<aside><div class="brand">Safari Tours<small>Business performance</small></div><nav>
<?php foreach($nav as $f=>$n):?><a href="<?=$f?>" class="<?=$cur===$f?'on':''?>"><span class="ic"><?=$n[1]?></span><?=$n[0]?></a><?php endforeach;?></nav>
<div class="who"><?=e($_SESSION['name'])?><small><?=is_owner()?'Owner':e(ucfirst($_SESSION['role']))?></small><a href="logout.php">Log out</a></div></aside>
<main><div class="topbar"><div><span class="hello"><?=$g?>, <?=e($first)?> &#128075;</span><span class="date"><?=date('l, j F Y')?></span></div><div class="avatar"><?=e(strtoupper(substr($_SESSION['name'],0,1)))?></div></div>
<h1><?=e($title)?></h1>
<?php if($m=flash()):?><div class="msg <?=$m[1]?>"><?=e($m[0])?></div><?php endif;}
function page_bottom(){echo '</main></body></html>';}