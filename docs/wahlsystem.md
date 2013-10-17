# Das Deutsche Wahlsystem

Dieses Dokument dient als Zusammenfassung des Deutschen Wahlsystems (geregelt durch das Bundestagswahlrecht) für den Entwurf eines Informationssystems zur Bundestagswahl. Es entsteht kein Anspruch auf Vollständigkeit. Für weiterführende Informationen sei auf die Quellen verwiesen.

## Grundlagen

In Deutschland findet zur Bundestagswahl alle vier Jahre eine personalisierte Verhältniswahl anwendung, die durch das Bundeswahlgesetz und die Bundeswahlordnung geregelt wird. Nach den Wahlrechtsgrundsätzen gilt sie als *allgemein*, *unmittelbar*, *frei*, *gleich* und *geheim*. Wahlberechtigt sind Deutsche ab Vollendung des 18. Lebensjahres, sofern diese nicht von der Wahl ausgeschlossen sind oder betreut werden.

## Stimmabgabe

Deutschland ist in derzeit 299 Wahlkreise zu je 250.000 Einwohnern aufgeteilt, die ihrerseits wieder in Wahlbezirke unterteilt sind. Wahlberechtigte dürfen zum Wahltermin in einem Wahllokal seines Wahlbezirks oder per Briefwahl an der Wahl teilnehmen. Dazu stehen ihnen zwei Stimmen zu:

1. **Erststimme**: Es wird ein Direktkandidat aus dem Wahlkreis gewählt. Der Kandidat mit den meisten Stimmen erhält ein Direktmandat in den Bundestag. Dadurch wird sichergestellt, dass jede Region Deutschlands im Bundestag vertreten ist.
2. **Zweitstimme**: Es wird eine Partei mit ihrer Landesliste gewählt. Das bundesweite Verhältnis der Stimmen ergibt die Sitzverteilung unter den 598 Proporzmandaten im Bundestag.

Eine Stimme gilt bei fehlender oder fehlerhafter Kennzeichnung als ungültig und hat keinen Einfluss auf die Sitzverteilung. Auf Stimmzetteln, die für einen anderen Wahlkreis gültig sind, ist nur die Erststimme ungültig.
 
## Sitzzuteilung im Bundestag

Die Sitze der 598 Proporzmandate im Bundestag werden entsprechend dem Verhältnis der Zweitstimmen aufgeteilt, sofern bundesweit zumindest 5% der Zweitstimmen oder drei Direktmandate auf die jeweilige Partei fallen. Alle weiteren Zweitstimmen verfallen und werden nicht weiter berücksichtigt. 

Die Aufteilung der Sitze im Bundestag geschieht in mehreren Schritten, um sowohl die Verhältnisse der Zweitstimmen als auch die Direktmandate aus den Wahlkreisen zu beachten:

 1. Schritt: Das Sitzkontingent jedes Bundeslandes wird in Relation zum Anteil des Landes an der deutschen Bevölkerung bestimmt. 
 2. Schritt: Die Landessitze werden im Verhältnis der Zweitstimmen im Land auf die Parteien aufgeteilt. Die Parteien besetzen die Sitze zunächst mit den Direktmandaten aus den Wahlkreisen und füllen restliche Sitze - falls vorhanden - aus der Landesliste auf. Gibt es mehr Direktmandate als zugeteilte Sitze, gelten diese als *Überhangmandate* und vergrößern somit den Bundestag.
 3. Schritt: Das durch die Überhangmandate verfälschte Verhältnis der Parteien wird unter Berücksichtigung der in *Schritt 2* ermittelten *Mindestsitzzahl* mit zusätzlichen Mandaten für Parteien mit zu wenig Sitzen wieder korrigiert. 
 4. Schritt: Die neu ermittelten Sitzzahlen werden wie bereits in *Schritt 1* und *Schritt 2* auf die Landeslisten der Parteien (unter Berücksichtigung der Direktmandate) aufgeteilt. 
 
Alle Rundungsvorgänge bei der Aufteilung von Sitzen finden kaufmännisch statt. 

## Vorteile eines DBMS

# Effizienterer Datenzugriff

Die Verwendung von Indexstrukturen ermöglicht Datenbanken eine effizientere Selektion von Datensätzen. Ohne Verwendung eines DBMS müsste zur Auswahl bestimmter Datensätze die gesamte Datenbasis durchsucht werden.

# Multi-User-Access

DBMS synchronisieren konkurrierende Zugriffe verschiedener Benutzer automatisch - bei einer anderen Lösung müssten parallele Zugriffe ggf. zusätzlich implementiert werden.

# Datenzugriff

DBMS bieten von Haus aus Standardfunktionalitäten (bspw. zum Lese-/Schreibzugriff), welche ohne DBMS ggf. zusätzlich implementiert werden müssten.

# Transaktionen

DBMS bieten die Möglichkeit, durch eine Transaktion mehre Aktionen en bloc (d.h. entweder werden *alle*, oder *keine*) durchzuführen (ohne, dass zwischenzeitlich andere, ggf. konkurrierende Aktionen ausgeführt werden). Ohne Verwendung eines DBMS müsste eine solche Funktionalität ggf. zusätzlich implementiert werden.

# Konsistenzkontrolle

DBMS bieten die Möglichkeit, für Datensätze Konsistenzbedingungen (Constraints) zu definieren - inkonsistente Daten können somit durch Einsatz eines DBMS vermieden werden.

# Datenzugriffskontrolle

DBMS bieten die Möglichkeit, für Datensätze Zugriffsregeln zu definieren und somit unauthorisierten Zugriff zu verhindern.

# Standardisierung

Werden *alle* Datensätze in *einem* DBMS persistiert, so kann der Zugriff auf selbige stets auf gleiche Weise erfolgen - hierunter zählen neben einheitlichen Namen und Formaten ebenso Schlüssel sowie Fachbegriffe.

# Trennung von Daten und Anwendung

Da DBMS und Anwendung per Schnittstelle kommunizieren, brauchen diese keine Kenntnisse über das jeweils andere. Hieraus folgt, dass bspw. DBMS-interne Änderungen Anwendungen nicht beeinflussen.

## Quellen

 1. <http://bundeswahlleiter.de/>
 2. <http://de.wikipedia.org/wiki/Bundestagswahlrecht>






