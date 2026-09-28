import {spawnSync} from 'node:child_process';
import {existsSync, readFileSync, readdirSync} from 'node:fs';
import path from 'node:path';

const root=path.resolve(import.meta.dirname,'..');
const required=['api/index.php','index.php','app/bootstrap.php','app/schema.sql','assets/site.css','assets/site.js','assets/profile.css','tests/integration.mjs','vercel.json'];
for(const file of required){
  if(!existsSync(path.join(root,file)))throw new Error(`Dağıtım için gerekli dosya eksik: ${file}`);
}

const config=JSON.parse(readFileSync(path.join(root,'vercel.json'),'utf8'));
if(!config.functions?.['api/index.php']?.runtime||!config.routes?.some(route=>route.dest==='/api/index.php')){
  throw new Error('Vercel PHP işlevi veya sayfa yönlendirmesi eksik.');
}

function phpFiles(dir){
  if(!existsSync(path.join(root,dir)))return [];
  return readdirSync(path.join(root,dir),{withFileTypes:true}).flatMap(item=>{
    const relative=path.join(dir,item.name);
    return item.isDirectory()?phpFiles(relative):item.isFile()&&item.name.endsWith('.php')?[relative]:[];
  });
}

const php=process.env.PHP_BIN||'php';
const files=['index.php','router.php','makaleler.php',...['app','admin','api','tools','include'].flatMap(phpFiles)];
for(const file of files){
  const result=spawnSync(php,['-l',file],{cwd:root,encoding:'utf8'});
  if(result.status!==0)throw new Error(`PHP sözdizimi hatası: ${file}\n${result.stderr||result.stdout}`);
}
console.log(`${files.length} PHP dosyası: sözdizimi doğru.`);

const test=spawnSync(process.execPath,['tests/integration.mjs'],{cwd:root,encoding:'utf8',timeout:120000});
if(test.status!==0)throw new Error(`Entegrasyon testleri başarısız.\n${test.stdout||''}\n${test.stderr||''}`);
console.log(test.stdout.trim().split(/\r?\n/).at(-1));
console.log('Dağıtım öncesi kontroller tamamlandı.');
