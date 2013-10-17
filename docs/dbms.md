# Vorteile eines DBMS

## Effizienterer Datenzugriff

Die Verwendung von Indexstrukturen ermöglicht Datenbanken eine effizientere Selektion von Datensätzen. Ohne Verwendung eines DBMS müsste zur Auswahl bestimmter Datensätze die gesamte Datenbasis durchsucht werden.

## Multi-User-Access

DBMS synchronisieren konkurrierende Zugriffe verschiedener Benutzer automatisch - bei einer anderen Lösung müssten parallele Zugriffe ggf. zusätzlich implementiert werden.

## Datenzugriff

DBMS bieten von Haus aus Standardfunktionalitäten (bspw. zum Lese-/Schreibzugriff), welche ohne DBMS ggf. zusätzlich implementiert werden müssten.

## Transaktionen

DBMS bieten die Möglichkeit, durch eine Transaktion mehre Aktionen en bloc (d.h. entweder werden *alle*, oder *keine*) durchzuführen (ohne, dass zwischenzeitlich andere, ggf. konkurrierende Aktionen ausgeführt werden). Ohne Verwendung eines DBMS müsste eine solche Funktionalität ggf. zusätzlich implementiert werden.

## Konsistenzkontrolle

DBMS bieten die Möglichkeit, für Datensätze Konsistenzbedingungen (Constraints) zu definieren - inkonsistente Daten können somit durch Einsatz eines DBMS vermieden werden.

## Datenzugriffskontrolle

DBMS bieten die Möglichkeit, für Datensätze Zugriffsregeln zu definieren und somit unauthorisierten Zugriff zu verhindern.

## Standardisierung

Werden *alle* Datensätze in *einem* DBMS persistiert, so kann der Zugriff auf selbige stets auf gleiche Weise erfolgen - hierunter zählen neben einheitlichen Namen und Formaten ebenso Schlüssel sowie Fachbegriffe.

## Trennung von Daten und Anwendung

Da DBMS und Anwendung per Schnittstelle kommunizieren, brauchen diese keine Kenntnisse über das jeweils andere. Hieraus folgt, dass bspw. DBMS-interne Änderungen Anwendungen nicht beeinflussen.

# Nachteile eines DBMS

## Personalkosten

Der Einsatz eines DBMS erfordert entsprechend qualifiziertes Personal, welfches wiederum mit zusätzlichen Kosten verbunden sein kann.

## Anschaffungskosten

Zum Betrieb eines DBMS benötigte Hard- sowie Software sind ggf. mit hohen Anschaffungskosten verbunden.

## Infrastruktur

Alle Clients, welche Datensätze in die DB einfügen oder aus selbiger lesen möchten, benötigen Zugriff auf eine Schnittstelle zur DB (bspw. Webinterface/Internet). In unserem konkreten Szenario könnte dies bedeuten, dass sämtliche Wahllokale mit einem Internetanschluss ausgestattet werden müssten.

## Single Point Of Failure

Da Datensätze in einem DBMS zentralisiert gespeichert werden, existiert ein zentraler Angriffspunkt. Wird diese DB bspw. angegriffen, so kann die gesamte Datenbasis gefährdet sein.