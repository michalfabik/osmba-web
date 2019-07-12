# osmba-web

PHP backend za OpenStreetMap.ba osnovan na [nPress CMS](https://github.com/zbycz/npress)-u.
JS aplikacija za mape je u repou [osmba](https://github.com/osm-ba/osmba).

- **LIVE verzija:** [openstreetmap.ba](https://openstreetmap.ba/)
<!--- - **ISSUES:** [na githubu osmba](https://github.com/osm-ba/osmba/issues?q=is%3Aopen+is%3Aissue+label%3Aosmcz-web`-->
<!--- - **DEMO:** [devosm.zby.cz](https://devosm.zby.cz/) - auto-deploy z větve `devosm`-->

## Kako doprinijeti projektu

- Front end (osmba) vidi na [osm-ba/osmba](https://github.com/osm-ba/osmba)
- Tu je website u PHP-u sa CMS sistemom i proširenja nPressa (direktoriji `data`, `app` i `theme`) - vidi compare
- Aktuelne grane:
  - [master](https://github.com/osm-ba/osmba-web) - grana za razvoj
<!---  - [devosm](https://github.com/osmcz/osmcz-web/tree/devosm) - auto-deploy na [devosm.zby.cz](https://devosm.zby.cz)-->
  - [production](https://github.com/osm-ba/osmba-web/tree/production) - manuelni deploy na [openstreetmap.ba](https://openstreetmap.ba)

### Dev Quickstart

Više u [INSTALL.md](INSTALL.md) za nPress

1. instalirati php5 + mysql
2. klonirati repo
3. ako treba, podesiti ovlaštenja `chmod 777 data/files/ data/thumbs/ app/log/ app/temp/`
4. u `data/config.local.neon` podesiti konekciju za bazu podataka + importirati `data/dump.sql`
5. u direktoriju pokrenuti `php -S localhost:8080`
6. otvoriti http://localhost:8080

### Preuzimanje izmjena iz `osmba`

```bash
rm -r ./theme/
cp -r ../osmba/* ./theme/
git add -a
git commit -m "deployed osmba v0.20\nosm-ba/osmba@8c0f9e413fefe8f3c4361e96a7eb656cd8023b93"
```

## Deploy na produkcijsku verziju

```
TODO
git tag deploy_20181231
```

## Autor, licenca

(c) 2014-2018 [Pavel Zbytovský](https://zby.cz) i drugi

Pod MIT licencom - slobodno širite, prodajite, uz navođenje autora.
