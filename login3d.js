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