// Mobile nav toggle and small interactions for the homepage
(function(){
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.getElementById('siteNav');
  if(!toggle || !nav) return;

  toggle.addEventListener('click', ()=>{
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    nav.classList.toggle('open');
  });

  // Close mobile nav when a link is clicked
  nav.querySelectorAll('a').forEach(a=>{
    a.addEventListener('click', ()=>{
      if(nav.classList.contains('open')){
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded','false');
      }
    })
  });

  // Small reveal animation for cards when scrolled into view
  const cards = document.querySelectorAll('.card');
  const obs = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        e.target.classList.add('in-view');
      }
    })
  },{threshold:0.12});
  cards.forEach(c=>obs.observe(c));
})();
