# sbar_verwaltung_nbg
S-Bar Verwaltung

# DB Zugangsdaten + ports

.env.dist zu .env kopieren und anpassen

# vor Ausführung von docker-compose
Datenbank-dump (.sql Datei) in /db/init kopieren

dann
```
docker-compose up
```
im Basis-Verzeichnis ausführen

# Passwörter
einen Benutzer in tbl_user aussuchen und salt und passwort austauschen mit

salt=
```
fth10lbm4y
```
und
passwort=
```
91ff4341b0b55a9bb880a0e9247b6885
```

bzw.
```
UPDATE `db1055935-schwaben`.tbl_user t SET t.salt = 'fth10lbm4y', t.passwort = '91ff4341b0b55a9bb880a0e9247b6885' WHERE t.username = 'jnagel';
```
für user 'jnagel'

außerhalb docker:
```
docker exec -i sbar_verwaltung_schwaben-database-1 mysql -uroot -pmypass --execute="UPDATE `db1055935-schwaben`.tbl_user t SET t.salt = 'fth10lbm4y', t.passwort = '91ff4341b0b55a9bb880a0e9247b6885' WHERE t.username = 'jnagel';"
```

dann kann man sich mit dem Benutzername und Passwort=test anmelden

# Lokale Änderungen
auf Zeile 136 in app/view/cockpit.tpl.php

```
$fh = fopen('/is/htdocs/wp1055935_1F55KTNHIC/www/sbar/verwaltung_dev/logs/last_kf_import.log', 'r');
```

ist der Pfad hard-coded. das kann man ändern zu

```
$fh = fopen('./logs/last_kf_import.log', 'r');
```

# PHP 8.2
für PHP 8.2 muss man nur in der .env die PHP_VERSION Vriable anpassen und das image neu erstellen.
für die DEPRECATED Meldungen kann man in /app/index.php Zeile 13 anpassen
```
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
```
zu
```
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
```

# TODO
es muss noch getestet werden ob die PHP Extension 'pdflib' benutzt wird, denn die fehlt momentan