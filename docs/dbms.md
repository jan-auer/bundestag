# Vorteile eines DBMS

## Effizienterer Datenzugriff

Die Verwendung von Indexstrukturen erm�glicht Datenbanken eine effizientere Selektion von Datens�tzen. Ohne Verwendung eines DBMS m�sste zur Auswahl bestimmter Datens�tze die gesamte Datenbasis durchsucht werden.

## Multi-User-Access

DBMS synchronisieren konkurrierende Zugriffe verschiedener Benutzer automatisch - bei einer anderen L�sung m�ssten parallele Zugriffe ggf. zus�tzlich implementiert werden.

## Datenzugriff

DBMS bieten von Haus aus Standardfunktionalit�ten (bspw. zum Lese-/Schreibzugriff), welche ohne DBMS ggf. zus�tzlich implementiert werden m�ssten.

## Transaktionen

DBMS bieten die M�glichkeit, durch eine Transaktion mehre Aktionen en bloc (d.h. entweder werden *alle*, oder *keine*) durchzuf�hren (ohne, dass zwischenzeitlich andere, ggf. konkurrierende Aktionen ausgef�hrt werden). Ohne Verwendung eines DBMS m�sste eine solche Funktionalit�t ggf. zus�tzlich implementiert werden.

## Konsistenzkontrolle

DBMS bieten die M�glichkeit, f�r Datens�tze Konsistenzbedingungen (Constraints) zu definieren - inkonsistente Daten k�nnen somit durch Einsatz eines DBMS vermieden werden.

## Datenzugriffskontrolle

DBMS bieten die M�glichkeit, f�r Datens�tze Zugriffsregeln zu definieren und somit unauthorisierten Zugriff zu verhindern.

## Standardisierung

Werden *alle* Datens�tze in *einem* DBMS persistiert, so kann der Zugriff auf selbige stets auf gleiche Weise erfolgen - hierunter z�hlen neben einheitlichen Namen und Formaten ebenso Schl�ssel sowie Fachbegriffe.

## Trennung von Daten und Anwendung

Da DBMS und Anwendung per Schnittstelle kommunizieren, brauchen diese keine Kenntnisse �ber das jeweils andere. Hieraus folgt, dass bspw. DBMS-interne �nderungen Anwendungen nicht beeinflussen.

# Nachteile eines DBMS

## Personalkosten

Der Einsatz eines DBMS erfordert entsprechend qualifiziertes Personal, welfches wiederum mit zus�tzlichen Kosten verbunden sein kann.

## Anschaffungskosten

Zum Betrieb eines DBMS ben�tigte Hard- sowie Software sind ggf. mit hohen Anschaffungskosten verbunden.

## Infrastruktur

Alle Clients, welche Datens�tze in die DB einf�gen oder aus selbiger lesen m�chten, ben�tigen Zugriff auf eine Schnittstelle zur DB (bspw. Webinterface/Internet). In unserem konkreten Szenario k�nnte dies bedeuten, dass s�mtliche Wahllokale mit einem Internetanschluss ausgestattet werden m�ssten.

## Single Point Of Failure

Da Datens�tze in einem DBMS zentralisiert gespeichert werden, existiert ein zentraler Angriffspunkt. Wird diese DB bspw. angegriffen, so kann die gesamte Datenbasis gef�hrdet sein.