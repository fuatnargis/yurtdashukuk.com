'use strict';
const menu = document.querySelector('.menu-toggle');
const nav = document.querySelector('.main-nav');
menu?.addEventListener('click', () => {
  const open = menu.getAttribute('aria-expanded') !== 'true';
  menu.setAttribute('aria-expanded', String(open));
  menu.setAttribute('aria-label', open ? 'Menüyü kapat' : 'Menüyü aç');
  nav.classList.toggle('is-open', open);
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && menu?.getAttribute('aria-expanded') === 'true') { menu.click(); menu.focus(); }
});
document.addEventListener('click', e => {
  if (nav?.classList.contains('is-open') && !e.target.closest('.site-header')) menu.click();
});
const dialog = document.querySelector('#search-dialog');
document.querySelector('.search-toggle')?.addEventListener('click', () => {dialog.showModal(); document.querySelector('#global-search').focus();});
document.querySelector('.search-close')?.addEventListener('click', () => dialog.close());
dialog?.addEventListener('click', e => {if (e.target === dialog) {const r=dialog.getBoundingClientRect(); if(e.clientX<r.left||e.clientX>r.right||e.clientY<r.top||e.clientY>r.bottom) dialog.close();}});
document.querySelectorAll('.view-button').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('.view-button').forEach(b => {b.classList.toggle('is-active',b===button); b.setAttribute('aria-pressed',String(b===button));});
  document.querySelector('#archive-results').classList.toggle('grid-view',button.dataset.view==='grid');
}));
document.querySelector('.back-top')?.addEventListener('click', () => window.scrollTo({top:0,behavior:matchMedia('(prefers-reduced-motion: reduce)').matches?'instant':'smooth'}));
const updateScroll = () => document.querySelector('.back-top')?.classList.toggle('visible',window.scrollY>600);
window.addEventListener('scroll',updateScroll,{passive:true});updateScroll();
document.querySelector('.print-button')?.addEventListener('click', () => window.print());
document.querySelector('.copy-link')?.addEventListener('click', async () => {
  const status=document.querySelector('.copy-status');
  try {await navigator.clipboard.writeText(location.href);status.textContent='Bağlantı kopyalandı.';} catch {status.textContent='Bağlantıyı adres çubuğundan kopyalayabilirsiniz.';}
});
document.querySelectorAll('.faq-list details').forEach(detail => detail.addEventListener('toggle', () => {
  if(detail.open) document.querySelectorAll('.faq-list details').forEach(other=>{if(other!==detail)other.open=false;});
}));
// Kaydırmaya bağlı üst bilgi durumu ve okuma ilerlemesi
const header=document.querySelector('.site-header');
const progress=document.querySelector('.reading-progress');
const article=document.querySelector('.post-main .prose, .detail-main .prose');
// İçindekiler: görünen bölümü işaretle
const tocLinks=[...document.querySelectorAll('.toc-list a[href^="#"]')];
const tocTargets=tocLinks.map(a=>document.getElementById(decodeURIComponent(a.getAttribute('href').slice(1)))).filter(Boolean);
const updateToc=()=>{
  if(!tocTargets.length)return;const marker=window.scrollY+Math.max(130,Math.round(window.innerHeight*0.24));let current=tocTargets[0];
  for(const t of tocTargets){if(t.getBoundingClientRect().top+window.scrollY<=marker)current=t;else break;}
  tocLinks.forEach(a=>a.parentElement.classList.toggle('is-active',a.getAttribute('href')==='#'+current.id));
};
tocLinks.forEach(a=>a.addEventListener('click',e=>{const t=document.getElementById(decodeURIComponent(a.getAttribute('href').slice(1)));if(!t)return;e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});history.replaceState(null,'',a.getAttribute('href'));}));
const onScroll=()=>{
  header?.classList.toggle('is-scrolled',window.scrollY>40);
  updateToc();
  if(progress&&article){
    const rect=article.getBoundingClientRect();const start=window.scrollY+rect.top-window.innerHeight*0.35;const end=window.scrollY+rect.bottom-window.innerHeight*0.8;
    const ratio=Math.min(1,Math.max(0,(window.scrollY-start)/Math.max(1,end-start)));
    progress.style.width=(ratio*100).toFixed(2)+'%';progress.classList.toggle('is-visible',ratio>0&&ratio<1);
  }
};
window.addEventListener('scroll',onScroll,{passive:true});window.addEventListener('resize',onScroll);onScroll();
// Görünüm alanına girince yumuşak belirme
const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
if(!reduced&&'IntersectionObserver' in window){
  const groups=['.article-grid','.practice-photo-grid','.practice-grid','.category-grid','.law-grid','.team-grid','.team-directory','.faq-list','.principles','.footer-grid','.contact-details','.archive-list','.hub-grid','.post-grid'];
  const singles=['.section-heading','.page-heading .container','.intro-grid>*','.editorial-split>*','.approach-section .container>*','.office-panel','.contact-band-inner','.detail-main','.detail-sidebar','.contact-info','.contact-form-card','.archive-sidebar','.directory-heading','.team-welcome','.team-introduction','.intro-plaque','.principle-strip','.post-main','.post-side>*','.post-recent','.list-heading'];
  const targets=new Set();
  singles.forEach(sel=>document.querySelectorAll(sel).forEach(el=>{el.dataset.delay=el.dataset.delay||'0';targets.add(el);}));
  groups.forEach(sel=>document.querySelectorAll(sel).forEach(group=>{[...group.children].forEach((child,i)=>{if(child.closest('.hero'))return;child.dataset.delay=String(Math.min(6,i%6));targets.add(child);});}));
  const observer=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('is-visible');observer.unobserve(entry.target);}});},{rootMargin:'0px 0px -8% 0px',threshold:0.08});
  targets.forEach(el=>{if(el.closest('.hero')||el.closest('.site-header'))return;const r=el.getBoundingClientRect();if(r.top<window.innerHeight&&r.bottom>0){el.classList.add('reveal','is-visible');return;}el.classList.add('reveal');observer.observe(el);});
}
