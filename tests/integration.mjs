import assert from 'node:assert/strict';
import {spawn, spawnSync} from 'node:child_process';
import {mkdtempSync, readFileSync, existsSync, writeFileSync, mkdirSync, unlinkSync} from 'node:fs';
import {tmpdir} from 'node:os';
import path from 'node:path';
import http from 'node:http';

async function networkFetch(url,options={}) {
  const headers=new Headers(options.headers||{});let payload;
  if(options.body instanceof URLSearchParams){payload=Buffer.from(options.body.toString());headers.set('Content-Type','application/x-www-form-urlencoded');}
  else if(options.body instanceof FormData){const encoded=new Response(options.body);payload=Buffer.from(await encoded.arrayBuffer());headers.set('Content-Type',encoded.headers.get('content-type'));}
  if(payload)headers.set('Content-Length',String(payload.length));headers.set('Connection','close');
  return new Promise((resolve,reject)=>{const req=http.request(url,{method:options.method||'GET',headers:Object.fromEntries(headers),agent:false},res=>{const chunks=[];res.on('data',c=>chunks.push(c));res.on('end',()=>{const buffer=Buffer.concat(chunks);const responseHeaders=new Headers();for(const [k,v] of Object.entries(res.headers)){if(Array.isArray(v))for(const value of v)responseHeaders.append(k,value);else if(v!==undefined)responseHeaders.set(k,v);}resolve({status:res.statusCode,ok:res.statusCode>=200&&res.statusCode<300,headers:responseHeaders,text:async()=>buffer.toString('utf8')});});res.on('error',reject);});req.on('error',reject);req.setTimeout(10000,()=>req.destroy(new Error('HTTP timeout')));req.end(payload);});
}

// Runs against an isolated database. Real website data is never edited.
const root=path.resolve(import.meta.dirname,'..');
const testData=mkdtempSync(path.join(tmpdir(),'hukuk-integration-'));
const env={...process.env,HUKUK_DATA_DIR:testData};
const php=process.env.PHP_BIN||'php';
const modules=spawnSync(php,['-m'],{encoding:'utf8'}).stdout;
const phpArgs=[];
if(!modules.includes('pdo_sqlite'))phpArgs.push('-d',process.platform==='win32'?'extension=php_pdo_sqlite.dll':'extension=pdo_sqlite');
if(!modules.split(/\r?\n/).includes('gd'))phpArgs.push('-d',process.platform==='win32'?'extension=php_gd.dll':'extension=gd');
const installed=spawnSync(php,[...phpArgs,'tools/install.php'],{cwd:root,env,encoding:'utf8'});
assert.equal(installed.status,0,installed.stderr);
const access=readFileSync(path.join(testData,'admin-access.txt'),'utf8');
const email=/E-posta: (.+)/.exec(access)[1].trim();
const password=/Parola: (.+)/.exec(access)[1].trim();
const port=18000+Math.floor(Math.random()*2000),base=`http://127.0.0.1:${port}`;
const server=spawn(php,[...phpArgs,'-d','upload_max_filesize=6M','-d','post_max_size=12M','-S',`127.0.0.1:${port}`,'router.php'],{cwd:root,env,stdio:['ignore','ignore','pipe'],windowsHide:true});
let logs='';server.stderr.on('data',x=>{logs+=x.toString();});
let checks=0;const results=[];
let generatedUpload='';
function ok(test,description){assert.ok(test,description);checks++;results.push(description);process.stdout.write(`PASS ${description}\n`);}
function client(){let cookie='';return async (route,options={})=>{const headers={...options.headers};if(cookie)headers.Cookie=cookie;const response=await networkFetch(base+route,{...options,headers});const cookies=response.headers.getSetCookie();if(cookies.length)cookie=cookies.at(-1).split(';')[0];return response;};}
const visitor=client(),admin=client();
async function html(get,route){const r=await get(route);return {status:r.status,body:await r.text(),headers:r.headers};}
function token(body){const match=/name="csrf" value="([a-f0-9]+)"/.exec(body);assert.ok(match,'CSRF field exists');return match[1];}
function formFields(body){
  const result={};for(const tag of body.matchAll(/<input\b[^>]*>/g)){const n=/\bname="([^"]+)"/.exec(tag[0]),v=/\bvalue="([^"]*)"/.exec(tag[0]);if(n&&v)result[n[1]]=v[1].replaceAll('&amp;','&').replaceAll('&quot;','"').replaceAll('&#039;',"'");}return result;
}
async function post(get,route,data){return get(route,{method:'POST',body:new URLSearchParams(data)});}
async function action(data){const dash=await html(admin,'/admin/');return post(admin,'/admin/action.php',{csrf:token(dash.body),...data});}
const newEntry=(overrides={})=>({action:'save-entry',type:'article',id:'0',title:'Entegrasyon Deneme İçeriği',slug:'entegrasyon-deneme',excerpt:'Otomatik entegrasyon testi için örnek metin.',body:'## Deneme başlığı\nGüvenli içerik testi.\n<script>alert(1)</script>',image:'/assets/images/library.jpg',category:'Deneme',author:'Test Editörü',status:'draft',published_at:'2026-01-01T10:00',sort_order:'0',meta_title:'',meta_description:'',link:'',...overrides});
try {
  for(let i=0;i<50;i++){try{const r=await networkFetch(base);if(r.ok)break;}catch{}await new Promise(r=>setTimeout(r,100));}
  const routes=['/','/makaleler','/calisma-alanlari','/kurumsal','/ekibimiz','/iletisim','/sayfa/kvkk','/sayfa/cerez-politikasi','/sayfa/yasal-bilgilendirme','/makaleler/hukuki-gorusmeye-hazirlik','/calisma-alanlari/aile-hukuku'];
  const linkedAssets=new Set();
  for(const route of routes){const r=await html(visitor,route);ok(r.status===200,`${route}: 200`);ok(!/Fatal error|Warning:|Parse error|\\n/.test(r.body),`${route}: no PHP errors or escaped line breaks`);ok((r.body.match(/<h1\b/g)||[]).length===1,`${route}: one h1`);for(const m of r.body.matchAll(/(?:src|href)="(\/assets\/[^"?]+)/g))linkedAssets.add(m[1]);}
  for(const asset of linkedAssets){const r=await visitor(asset);ok(r.status===200,`${asset}: asset exists`);}
  ok((await visitor('/missing-page')).status===404,'Unknown pages return 404');
  for(const blocked of ['/storage/site.sqlite','/storage/admin-access.txt','/app/seed.php','/tools/install.php','/.gitignore','/assets/uploads/.htaccess','/tests/integration.mjs'])ok((await visitor(blocked)).status===404,`${blocked}: protected`);
  ok((await visitor('/blog-list.html')).status===301,'Legacy article URL redirects');
  for(const route of ['/home-3.html','/about-me.html','/attorneys-4-cols.html','/practice-image-3-cols.html','/blog-single-post.html','/contact-2.html','/faq.html','/sayfa/kurumsal'])ok((await visitor(route)).status===301,`${route}: canonical redirect`);
  const home=await html(visitor,'/');ok(home.headers.get('content-security-policy')?.includes("script-src 'self'"),'CSP prevents untrusted scripts');ok(home.body.includes('noindex,nofollow'),'Sample site is not indexed by default');
  const search=await html(visitor,'/makaleler?q='+encodeURIComponent('sözleşme'));ok(search.body.includes('Sözleşme imzalamadan')&&!search.body.includes('Aradığınız konuda yayın bulunamadı.'),'Turkish search finds matching publication');
  ok((await html(visitor,'/makaleler?q=imkansiz-xyz')).body.includes('Aradığınız konuda yayın bulunamadı.'),'Search has an empty state');
  ok((await html(visitor,'/makaleler?kategori='+encodeURIComponent('İş Hukuku'))).body.includes('<strong>1</strong> yayın'),'Category filter reduces result set');
  ok((await visitor('/admin/')).status===303,'Admin access requires authentication');
  ok((await post(visitor,'/admin/action.php',{action:'save-settings'})).status===303,'Unauthenticated mutation is rejected');
  let login=await html(admin,'/admin/login.php');
  ok((await post(admin,'/admin/login.php',{csrf:'invalid',email,password})).status===419,'Login rejects invalid CSRF');
  login=await html(admin,'/admin/login.php');
  ok((await post(admin,'/admin/login.php',{csrf:token(login.body),email,password:'wrong-password'})).status===401,'Wrong password is rejected');
  login=await html(admin,'/admin/login.php');
  ok((await post(admin,'/admin/login.php',{csrf:token(login.body),email,password})).status===303,'Administrator can sign in');
  for(const route of ['/admin/','/admin/?view=content&type=article','/admin/?view=edit&type=article','/admin/?view=settings','/admin/?view=settings&group=home','/admin/?view=settings&group=sections','/admin/?view=settings&group=appearance','/admin/?view=settings&group=texts','/admin/?view=settings&group=seo','/admin/?view=media','/admin/?view=messages','/admin/?view=backup','/admin/?view=account']){const r=await html(admin,route);ok(r.status===200&&!/Fatal error|Warning:|Parse error/.test(r.body),`${route}: authenticated screen renders`);}
  ok((await post(admin,'/admin/action.php',{action:'save-entry',csrf:'invalid'})).status===419,'Mutation rejects invalid CSRF');
  let saved=await action(newEntry());ok(saved.status===303&&saved.headers.get('location').includes('id='),'Draft can be created');
  let editURL=saved.headers.get('location');let edit=await html(admin,editURL);let fields=formFields(edit.body);const id=fields.id;
  ok((await visitor('/makaleler/entegrasyon-deneme')).status===404,'Draft is hidden from public');
  ok((await html(admin,`/admin/?view=preview&id=${id}`)).body.includes('&lt;script&gt;alert(1)&lt;/script&gt;'),'Preview escapes script markup');
  saved=await action(newEntry({id,original_updated_at:fields.original_updated_at,status:'published'}));
  let published=await html(visitor,'/makaleler/entegrasyon-deneme');ok(published.status===200,'Published entry appears on website');ok(published.body.includes('&lt;script&gt;alert(1)&lt;/script&gt;')&&!published.body.includes('<script>alert(1)</script>'),'Public content escapes HTML');
  await action(newEntry({id,original_updated_at:'stale',title:'Yanlış Üzerine Yazma',status:'published'}));
  ok(!(await html(visitor,'/makaleler/entegrasyon-deneme')).body.includes('Yanlış Üzerine Yazma'),'Stale edit cannot overwrite current content');
  // Consume the failed form state before reading fresh optimistic-lock token.
  await html(admin,editURL);edit=await html(admin,editURL);fields=formFields(edit.body);
  await action(newEntry({id,original_updated_at:fields.original_updated_at,status:'published',title:'Yeni İçerik Başlığı'}));
  ok((await html(visitor,'/makaleler/entegrasyon-deneme')).body.includes('Yeni İçerik Başlığı'),'Edit is immediately reflected on website');
  edit=await html(admin,editURL);const revisionId=/name="revision_id" value="(\d+)"/.exec(edit.body)[1];
  await action({action:'restore-revision',id,type:'article',revision_id:revisionId});
  ok((await html(visitor,'/makaleler/entegrasyon-deneme')).body.includes('Entegrasyon Deneme İçeriği'),'Previous revision can be restored');
  await action({action:'archive-entry',id,type:'article'});ok((await visitor('/makaleler/entegrasyon-deneme')).status===404,'Archived content leaves public website');
  await action({action:'restore-entry',id,type:'article'});ok((await visitor('/makaleler/entegrasyon-deneme')).status===404,'Restored archive becomes a private draft');
  await action(newEntry({slug:'gelecek-yayin',title:'Gelecekteki Yayın',status:'published',published_at:'2099-01-01T10:00'}));ok((await visitor('/makaleler/gelecek-yayin')).status===404,'Scheduled publication remains hidden until date');
  for(const [type,slug,url,category] of [['practice','yeni-alan','/calisma-alanlari/yeni-alan','scale'],['page','ek-sayfa','/sayfa/ek-sayfa',''],['team','ekip-uyesi','/ekibimiz/ekip-uyesi','Avukat']]){
    await action(newEntry({type,slug,title:'Otomatik '+type,category,status:'published'}));
    ok((await html(visitor,url)).body.includes('Otomatik '+type),`${type}: content type publishes successfully`);
  }
  await action(newEntry({type:'menu',slug:'ek-menu',title:'Ek menü',status:'published',link:'/sayfa/ek-sayfa'}));
  ok((await html(visitor,'/')).body.includes('>Ek menü</a>'),'Menu changes appear in public navigation');
  await action(newEntry({type:'faq',slug:'ek-soru',title:'Test için ek bir soru?',status:'published',body:'Otomatik soru yanıtı.'}));
  ok((await html(visitor,'/')).body.includes('Otomatik soru yanıtı.'),'FAQ content is editable');
  const textSettings=await html(admin,'/admin/?view=settings&group=texts');
  const textValues={action:'save-settings',group:'texts'};for(const m of textSettings.body.matchAll(/<textarea name="([^"]+)"[^>]*>([\s\S]*?)<\/textarea>/g))textValues[m[1]]=m[2].replaceAll('&amp;','&').replaceAll('&quot;','"').replaceAll('&#039;',"'");
  textValues.text_principle_1_title='Panelden Güncel İlke';textValues.text_contact_form_button='Yeni form butonu';
  await action(textValues);ok((await html(visitor,'/')).body.includes('Panelden Güncel İlke'),'Shared section text is editable');ok((await html(visitor,'/iletisim')).body.includes('Yeni form butonu'),'Public form button text is editable');
  // Sitemap links for reserved pages must use their canonical route.
  const pageRows=await html(admin,'/admin/?view=content&type=page');const kurumsalId=/href="\/admin\/\?view=edit&amp;type=page&amp;id=(\d+)"><span>Kurumsal/.exec(pageRows.body)?.[1];
  assert.ok(kurumsalId,'Corporate page exists');const corporateEdit=await html(admin,`/admin/?view=edit&type=page&id=${kurumsalId}`);const corporateFields=formFields(corporateEdit.body);
  await action(newEntry({id:kurumsalId,type:'page',title:'Kurumsal',slug:'kurumsal',status:'published',original_updated_at:corporateFields.original_updated_at,meta_title:'Özel Kurumsal Başlığı',meta_description:'Özel kurumsal açıklaması'}));
  ok((await html(visitor,'/kurumsal')).body.includes('<title>Özel Kurumsal Başlığı'),'Corporate page respects its own SEO fields');
  await action({action:'archive-entry',id:kurumsalId,type:'page'});ok((await visitor('/kurumsal')).status===200,'Core corporate page cannot be archived');
  const appearance={action:'save-settings',group:'appearance',primary_color:'#142b36',accent_color:'#aa8855',footer_text:'Entegrasyon testi alt bilgisi',legal_notice:'Genel bilgilendirme testi'};
  await action(appearance);ok((await html(visitor,'/')).body.includes('Entegrasyon testi alt bilgisi'),'Settings persist and appear publicly');
  await action({...appearance,accent_color:'red;display:none'});ok((await html(visitor,'/')).body.includes('--gold:#aa8855'),'Invalid theme CSS is rejected');
  const contact=await html(visitor,'/iletisim');
  const message={csrf:token(contact.body),name:'Test Ziyaretçisi',email:'visitor@example.test',phone:'',subject:'Diğer',message:'Bu mesaj yalnızca otomatik yerel uygulama testi içindir.',notice:'1',website:''};
  ok((await post(visitor,'/iletisim',{...message,email:'invalid'})).status===422,'Contact form validates email');
  ok((await post(visitor,'/iletisim',{...message,notice:''})).status===422,'Contact form requires notice acknowledgement');
  ok((await post(visitor,'/iletisim',{...message,website:'bot.example'})).status===422,'Contact honeypot rejects spam');
  ok((await post(visitor,'/iletisim',message)).status===303,'Valid contact request is saved');
  const inbox=await html(admin,'/admin/?view=messages');ok(inbox.body.includes('Test Ziyaretçisi'),'Contact request is visible in admin inbox');
  const mid=/view=messages&amp;id=(\d+)#message-detail/.exec(inbox.body)[1];
  await action({action:'message-status',id:mid,status:'read'});ok((await html(admin,`/admin/?view=messages&id=${mid}`)).body.includes('class="badge read"'),'Message status can be updated');
  const uploadPage=await html(admin,'/admin/?view=media');const upload=new FormData();upload.set('csrf',token(uploadPage.body));upload.set('action','upload');upload.set('image',new Blob([readFileSync(path.join(root,'assets/images/library.jpg'))],{type:'image/jpeg'}),'library-test.jpg');
  ok((await admin('/admin/action.php',{method:'POST',body:upload})).status===303,'Image can be uploaded');
  const library=await html(admin,'/admin/?view=media');ok(library.body.includes('library-test.jpg'),'Uploaded image appears in media library');generatedUpload=/src="(\/assets\/uploads\/[a-f0-9]+\.webp)" alt="library-test.jpg"/.exec(library.body)?.[1]||'';
  const badUpload=new FormData();badUpload.set('csrf',token(library.body));badUpload.set('action','upload');badUpload.set('image',new Blob(['<?php echo 1; ?>'],{type:'image/jpeg'}),'malicious.jpg');await admin('/admin/action.php',{method:'POST',body:badUpload});ok((await html(admin,'/admin/?view=media')).body.includes('Yalnızca JPG, PNG ve WebP'),'Disguised executable upload is rejected');
  const backup=await action({action:'export'});ok(backup.status===200&&backup.headers.get('content-disposition')?.includes('attachment'),'Backup download is authenticated');
  const backupText=await backup.text();const backupData=JSON.parse(backupText);ok(backupData.format==='mizan-cms'&&!backupText.includes('private_salt')&&!backupText.includes('password')&&!backupText.includes('Test Ziyaretçisi'),'Backup excludes credentials and contact data');
  const bp=await html(admin,'/admin/?view=backup');const importForm=new FormData();importForm.set('csrf',token(bp.body));importForm.set('action','import');importForm.set('backup',new Blob([backupText],{type:'application/json'}),'backup.json');await admin('/admin/action.php',{method:'POST',body:importForm});ok((await html(admin,'/admin/?view=backup')).body.includes('Yedek birleştirildi.'),'Content backup imports successfully');
  await action({action:'save-settings',group:'seo',site_url:'https://example.test',seo_title:'Test Hukuk',seo_description:'Test açıklaması',indexing:'1'});
  ok((await html(visitor,'/sitemap.xml')).body.includes('https://example.test/makaleler/'),'Sitemap includes canonical content URLs');
  ok((await html(visitor,'/robots.txt')).body.includes('Disallow: /admin/'),'Robots protects administrative pages');
  const secondAdmin=client();const l2=await html(secondAdmin,'/admin/login.php');await post(secondAdmin,'/admin/login.php',{csrf:token(l2.body),email,password});
  await action({action:'account',name:'Güncel Yönetici',email,current_password:password,new_password:'New-Testing-Password-829!',confirm_password:'New-Testing-Password-829!'});
  ok((await secondAdmin('/admin/')).status===303,'Password change invalidates other sessions');
  ok((await html(admin,'/admin/')).body.includes('Güncel Yönetici'),'Current session retains account update');
  await action({action:'logout'});ok((await admin('/admin/')).status===303,'Logout clears administrative access');
  const attacker=client();let rateStatus=0;for(let i=0;i<9;i++){const l=await html(attacker,'/admin/login.php');rateStatus=(await post(attacker,'/admin/login.php',{csrf:token(l.body),email:'invalid@example.test',password:'incorrect'})).status;}
  ok(rateStatus===429,'Repeated login attempts are rate limited');
  const phpLog=path.join(testData,'php-error.log');const errorLog=existsSync(phpLog)?readFileSync(phpLog,'utf8'):'';ok(!/PHP (?:Fatal error|Warning|Notice|Deprecated|Parse error)/.test(errorLog),'No PHP runtime warnings or fatal errors');
  mkdirSync(path.join(root,'tests/artifacts'),{recursive:true});writeFileSync(path.join(root,'tests/artifacts/integration-results.json'),JSON.stringify({passed:checks,date:new Date().toISOString(),checks:results},null,2));
  process.stdout.write(`\n${checks} checks passed. Isolated database: ${testData}\n`);
} catch(error){console.error(error);console.error(logs.slice(-3500));process.exitCode=1;}
finally{server.kill();if(generatedUpload && /^\/assets\/uploads\/[a-f0-9]+\.webp$/.test(generatedUpload)){const generatedPath=path.join(root,generatedUpload);if(generatedPath.startsWith(path.join(root,'assets','uploads'))&&existsSync(generatedPath))unlinkSync(generatedPath);}}
