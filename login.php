<?php require 'includes/boot.php';
if(!empty($_SESSION['uid'])){header('Location: dashboard.php');exit;}
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=trim($_POST['id']??'');$pw=$_POST['password']??'';$s=null;
 if(strpos($id,'@')!==false){
  $u=q('SELECT * FROM owners WHERE email=?',[$id])->fetch();
  if($u&&password_verify($pw,$u['password'])){
   $b=q('SELECT business_id FROM businesses WHERE owner_id=? ORDER BY business_id LIMIT 1',[$u['owner_id']])->fetchColumn();
   $s=['uid'=>$u['owner_id'],'type'=>'owner','name'=>$u['full_name'],'role'=>'owner','bid'=>$b];}
 }else{
  $u=q('SELECT * FROM staff WHERE username=?',[$id])->fetch();
  if($u&&password_verify($pw,$u['password']))
   $s=['uid'=>$u['staff_id'],'type'=>'staff','name'=>$u['full_name'],'role'=>$u['role'],'bid'=>$u['business_id']];}
 if($s){session_regenerate_id(true);$_SESSION=$s+['csrf'=>bin2hex(random_bytes(16))];header('Location: dashboard.php');exit;}
 $err='Wrong email, username or password.';
}?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Log in - Safari Tours</title><style>
:root{--ink:#16261f;--ac:#1d3b2f;--oc:#c47f17;--gold:#f0c36d;--gr:#2f7a55;--mu:#5f6f66}
*{box-sizing:border-box}
html,body{height:100%;margin:0}
body{font:15px/1.55 "Segoe UI",system-ui,sans-serif;color:var(--ink);overflow:hidden;
  background:linear-gradient(180deg,#0f2a20 0%,#1d3b2f 38%,#b8641c 78%,#f0a43a 100%)}
.stage{position:fixed;inset:0;perspective:1100px;overflow:hidden}
.world{position:absolute;inset:0;transform-style:preserve-3d}
.layer{position:absolute}
.sun{position:absolute;left:50%;bottom:30%;width:230px;height:230px;margin-left:-115px;border-radius:50%;
  background:radial-gradient(circle at 35% 30%,#fff3c4,#f7c35a 45%,#e8892a 100%);
  box-shadow:0 0 90px 40px rgba(247,195,90,.45);transform:translate(calc(var(--mx,0)*-6px),calc(var(--my,0)*-3px));animation:sunpulse 6s ease-in-out infinite}
@keyframes sunpulse{50%{box-shadow:0 0 120px 60px rgba(247,195,90,.6)}}
.hill{left:-6%;right:-6%;bottom:-8vh;height:54vh}
.hill svg{width:100%;height:100%;display:block}
.h1{transform:translate(calc(var(--mx,0)*-10px),calc(var(--my,0)*-5px))}.h1 path{fill:#2c5a43}
.h2{transform:translate(calc(var(--mx,0)*-22px),calc(var(--my,0)*-9px))}.h2 path{fill:#1f4533}
.h3{transform:translate(calc(var(--mx,0)*-40px),calc(var(--my,0)*-14px))}.h3 path{fill:#12301f}
.tree{position:absolute;bottom:8vh;width:190px}
.tree.t1{left:7%;transform:translate(calc(var(--mx,0)*-48px),0)}
.tree.t2{right:9%;width:150px;bottom:9vh;transform:translate(calc(var(--mx,0)*-30px),0)}
.tree path,.tree rect{fill:#0a2016}
.cube{position:absolute;width:54px;height:54px;transform-style:preserve-3d;animation:spin 14s linear infinite}
.cube i{position:absolute;inset:0;border:1px solid rgba(240,195,109,.55);background:rgba(240,195,109,.10)}
.cube i:nth-child(1){transform:translateZ(27px)}.cube i:nth-child(2){transform:rotateY(90deg) translateZ(27px)}
.cube i:nth-child(3){transform:rotateY(180deg) translateZ(27px)}.cube i:nth-child(4){transform:rotateY(-90deg) translateZ(27px)}
.cube i:nth-child(5){transform:rotateX(90deg) translateZ(27px)}.cube i:nth-child(6){transform:rotateX(-90deg) translateZ(27px)}
.cube.c1{left:12%;top:18%}
.cube.c2{right:14%;top:28%;width:38px;height:38px;animation-duration:19s;animation-direction:reverse}
.cube.c3{left:22%;bottom:30%;width:30px;height:30px;animation-duration:11s}
@keyframes spin{from{transform:rotateX(20deg) rotateY(0)}to{transform:rotateX(20deg) rotateY(360deg)}}
.wrap{position:relative;z-index:5;height:100%;display:grid;place-items:center;padding:20px}
.card3d{width:410px;max-width:94vw;transform-style:preserve-3d;transition:transform .12s ease-out;will-change:transform}
.panel{position:relative;transform-style:preserve-3d;padding:34px 32px 28px;border-radius:22px;
  background:linear-gradient(145deg,rgba(255,255,255,.93),rgba(255,255,255,.82));
  border:1px solid rgba(255,255,255,.6);
  box-shadow:0 30px 60px rgba(0,0,0,.45),0 0 0 1px rgba(255,255,255,.2) inset,0 -6px 0 var(--oc) inset}
.panel>*{transform:translateZ(34px)}
.panel .glare{position:absolute;inset:0;border-radius:22px;pointer-events:none;transform:none;
  background:radial-gradient(circle at var(--gx,30%) var(--gy,0%),rgba(255,255,255,.55),transparent 55%);mix-blend-mode:overlay}
.logo{width:62px;height:62px;margin:0 auto 10px;border-radius:18px;display:grid;place-items:center;font-size:30px;
  background:linear-gradient(145deg,var(--ac),var(--gr));color:var(--gold);box-shadow:0 12px 24px rgba(29,59,47,.45);transform:translateZ(60px)}
h1{margin:0;text-align:center;font:700 29px Georgia,serif;color:var(--ac)}
.sub{text-align:center;color:var(--mu);margin:2px 0 20px;font-size:14px}
label{display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:var(--mu);margin:0 0 5px 2px}
.field{position:relative;margin-bottom:15px}
.field input{width:100%;padding:12px 44px 12px 14px;border:1.5px solid #cfd8cf;border-radius:12px;font:inherit;background:#fff;color:var(--ink);transition:.2s}
.field input:focus{outline:none;border-color:var(--oc);box-shadow:0 0 0 4px rgba(196,127,23,.18);transform:translateZ(8px)}
.eye{position:absolute;right:8px;bottom:6px;border:0;background:none;cursor:pointer;font-size:18px;padding:4px 7px;opacity:.7}
.eye:hover{opacity:1}
.go{width:100%;margin-top:6px;padding:13px;border:0;border-radius:12px;font:700 16px "Segoe UI",sans-serif;color:#fff;cursor:pointer;
  background:linear-gradient(135deg,var(--gr),var(--ac));box-shadow:0 10px 0 #12301f,0 18px 28px rgba(0,0,0,.35);
  transform:translateZ(46px);transition:transform .12s,box-shadow .12s}
.go:hover{filter:brightness(1.1)}
.go:active{transform:translateZ(46px) translateY(7px);box-shadow:0 3px 0 #12301f,0 8px 14px rgba(0,0,0,.35)}
.err{background:#f6d6cf;color:#9c2f1f;border-radius:10px;padding:10px 13px;margin-bottom:14px;font-size:14px;animation:shake .4s}
@keyframes shake{20%,60%{transform:translateX(-6px) translateZ(34px)}40%,80%{transform:translateX(6px) translateZ(34px)}}
.foot{text-align:center;margin-top:30px;font-size:12px;color:var(--mu)}
@media(max-width:560px){.cube,.sun{display:none}.panel{padding:26px 22px}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style></head><body>

<div class="stage"><div class="world" id="world">
  <div class="sun"></div>
  <div class="cube c1"><i></i><i></i><i></i><i></i><i></i><i></i></div>
  <div class="cube c2"><i></i><i></i><i></i><i></i><i></i><i></i></div>
  <div class="cube c3"><i></i><i></i><i></i><i></i><i></i><i></i></div>
  <div class="layer hill h1"><svg viewBox="0 0 1200 400" preserveAspectRatio="none"><path d="M0 400V210C180 120 330 170 520 140S860 70 1200 190V400Z"/></svg></div>
  <div class="layer hill h2"><svg viewBox="0 0 1200 400" preserveAspectRatio="none"><path d="M0 400V250C220 170 400 260 640 210S1000 150 1200 240V400Z"/></svg></div>
  <div class="layer hill h3"><svg viewBox="0 0 1200 400" preserveAspectRatio="none"><path d="M0 400V300C260 230 480 330 760 280S1050 250 1200 300V400Z"/></svg></div>
  <svg class="tree t1" viewBox="0 0 200 160"><rect x="96" y="70" width="8" height="90"/><path d="M10 70C40 40 90 48 100 62C112 44 170 40 192 72C150 60 120 68 100 76C80 68 40 60 10 70Z"/></svg>
  <svg class="tree t2" viewBox="0 0 200 160"><rect x="96" y="70" width="8" height="90"/><path d="M10 70C40 40 90 48 100 62C112 44 170 40 192 72C150 60 120 68 100 76C80 68 40 60 10 70Z"/></svg>
</div></div>

<div class="wrap"><div class="card3d" id="card3d"><div class="panel" id="panel">
  <div class="glare"></div>
  <div class="logo">&#129409;</div>
  <h1>Safari Tours</h1>
  <p class="sub">Log in to track invoices, payments and performance.</p>
  <?php if($err):?><div class="err"><?=e($err)?></div><?php endif;?>
  <form method="post" autocomplete="on"><?=csrf()?>
    <div class="field"><label for="id">Owner email or staff username</label>
      <input id="id" name="id" required autofocus value="<?=e($_POST['id']??'')?>"></div>
    <div class="field"><label for="pw">Password</label>
      <input id="pw" type="password" name="password" required><button type="button" class="eye" id="eye" aria-label="Show password">&#128065;</button></div>
    <button class="go">Log in</button>
  </form>
  <div class="foot">Business Performance Evaluation System &middot; Nairobi</div>
</div></div></div>

<script>
(function(){
  var card=document.getElementById('card3d'), world=document.getElementById('world'), panel=document.getElementById('panel');
  var touch=window.matchMedia('(hover:none)').matches;
  var reduce=window.matchMedia('(prefers-reduced-motion:reduce)').matches;
  if(!touch&&!reduce){
    document.addEventListener('mousemove',function(e){
      var x=(e.clientX/window.innerWidth-.5), y=(e.clientY/window.innerHeight-.5);
      card.style.transform='rotateY('+(x*16)+'deg) rotateX('+(-y*16)+'deg)';
      world.style.setProperty('--mx',x*2);world.style.setProperty('--my',y*2);
      panel.style.setProperty('--gx',(x+.5)*100+'%'); panel.style.setProperty('--gy',(y+.5)*100+'%');
    });
    document.addEventListener('mouseleave',function(){card.style.transform='';world.style.setProperty('--mx',0);world.style.setProperty('--my',0);});
  }
  var eye=document.getElementById('eye'), pw=document.getElementById('pw');
  if(eye&&pw){eye.addEventListener('click',function(){pw.type=pw.type==='password'?'text':'password';eye.textContent=pw.type==='password'?'👁':'🙈';});}
})();
</script>
</body></html>