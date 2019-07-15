# nPress - open source CMS

nPress je sistem za uređivanje sadržaja osnovan na Nette Frameworku iz 2012. godine. Demo na [npress.zby.cz](http://npress.zby.cz) (od 7.12.2015. ne radi više izvorni domen npress.info).

**Zašto koristiti:**

- uređivanje strukture na više jezika
- jednostavan rad sa prilozima
- proširenje pomoću Nette Latte šablona (direktorij theme i meta direktiva `.template`, `.sectionTemplate`)
- koristi se u periodu 2010.-2016. na 10 sajtova uz potpuno zadovoljstvo vlasnika (od nekomercijalnih npr. [openstreetmap.cz](http://openstreetmap.cz), [blanik.info](http://blanik.info) ili [smetanovokvarteto.cz](http://smetanovokvarteto.cz))

**Zašto ne koristiti:**

- stari nette
- ko ne zna Latte šablone, neće uspjeti da ga proširi previše
- razvoj je stao

# Instalacija

1. Preuzmite sadržaj repozitarija ili distribucijsku verziju
2. Pripremite MySQL bazu podataka i importirajte fajl `/data/init.sql`
   - U distribucijskoj verziji takođe se nalaze podaci za testiranje. Naći ćete ih
     u `/data/npdemo.sql` i `/data/files/`.
3. Fajl `/data/config.neon.sample` kopirajte u `/data/config.neon`

- uredite konekciju na bazu podataka
- podesite šifru za administraciju, odnosno i druge opcije
  - provjerite, da fajl `/data/config.neon` nije dostupan putem weba

4. Podesite ovlaštenja za pisanje u direktorije `/data/files/`, `/data/thumbs/`, `/app/log/` i `/app/temp/`
5. Ukoliko pokrećete aplikaciju u poddirektoriju, u `.htaccess` zakomentirajte `RewriteBase`
6. Administracija je na adresi `<web-put-prema-npressu>/admin/`
7. Enjoy!

napomena: ako bilo šta ne radi što bi sigurno trebalo da radi, možda je dovoljno izbrisati `/app/temp/cache`

Sistem je još uvijek beta verzija, tako da sigurno sadrži pno grešaka. Molim da javljate bugove na adresi https://github.com/zbycz/npress/issues. Isto ću biti zahvalan za bilo kakve pull requeste ili sugestije. Ako negdje upotrijebite ovaj sistem, biće mi drago ako mi javite.

# Zahtjevi

- PHP 5.2 ili noviji
- zahtjevi za Nette: http://doc.nette.org/en/requirements
- Apache + mod_rewrite
- MySQL baza podataka

# Dalje funkcije

- Opcionalni direktorij `/theme/` može da sadrži šablone za vlastiti izgled. Osnovni layout će onda biti `@layout.latte`, a stranicu sa meta direktivom: `.template=something` će prikazati pomoću šablone `/theme/something.latte`.

- Stranicu je moguće promijeniti u kategoriju članaka pomoću meta direktive: `.category=yes`. Poslije toga će se u meniju prestati prikazivati podstranice, a pojaviće se interfejs za uređivanje članaka. U tekst kategorije treba dodati npr. makro `#-subpagesblog-<id_page>-#`

- Makroe ćete naći u `/app/components/NpMacros.php`, za sada nisu content aware i u najbližoj budućnosti ih očekuje refactoring.

- Pluginovi su za sada u skroz početnoj fazi razvoja. Ako imate neke sugestije/napomene za njih, molim da mi javite. Naći ćete ih u direktoriju /app/plugins/. Aktivacija u cofigu, npr. pomoću `plugins: PasswordPlugin:{password:123}`, a na odgovarajućoj stranici podesiti meta direktivu: `.password=yes`

# Autor i licenca

(c) 2011-2012 Pavel Zbytovský [zby.cz](http://zby.cz)
Pod MIT licencom - slobodno širite, prodajite, uz navođenje autora.

# Dokumentacija (uh...)

Pluginovi se registruju u configu pomoću sekcije parameters.plugins. Svaki plugin onda sebi registruje događaje u svom statičkom nizu $events, prilikom izazivanja (trigger) događaja se onda na razne načine zove metoda pluginova tačno sa nazivom $event(), i to za sve pluginove koji je registruju.

Vraća true ukoliko je svaki pokrenuti plugin vratio true.

- `Presenter#triggerEvent($event, [$args])` - Plugin je spojen kao komponenta presentera `$presenter[PluginName]`, metoda se zove na nju.
- `Presenter#triggerStaticEvent($event, [$args])` - metoda se zove statički kao `PluginName::{$event}()`.
- `Presenter#triggerEvent_filter($event, $filter)` - metoda se zove postepeno za sve registrovane, a `$filter` se postepeno predaje, na kraju se vraća.
