#Lastenheft
*Einführung*
Web-interface und Datenbanken

# Benutzer-Schnittstelle
Web-Frontend

Benutzergruppen

- Analytiker (Anonym)
- Wähler
- Wahllokal
- Administrator

Usecases

- Analytiker (Anonym)
	- Hochrechnungen von laufenden Wahlen
	- Aufrufen und Analyse vom Wahlergebnis
- Wähler
	- Authentifizierung
	- wählen
- Wahllokalleiter
	- Authentifizierung
	- Verwaltung von Wahlinformationsergebnissen
- Administrator
	- Authentifizierung
	- Stammdatenverwaltung für Wahlen
	- Benutzerverwaltung


##Funktionale Anforderungen

###Allgemein
1. Wähler, Wahllokal und Administrator-Benutzer kann sich mit Benutzername und Passwort authentifizieren

###Analytiker

1. Vergleichen von Wahlergebnissen bund1esweit, pro Bundesland, pro Wahlkreis
2. Vergleichen von historischen Wahlergebnissen bundesweit, pro Bundesland, pro Wahlkreis (Vergleiche zwischen Jahren)
3. Anzeige des Gewinners eines Wahlkreises (Direktmandat)
4. Aulfistung von allen Mandaten bundesweit, Bundesland
5. Anzeige der Koalitionsmöglichkeiten für eine beliebige Wahl
6. Anzeige der Sitzeverteilung pro Bundesland und dessen Aufteilung auf Parteien in einem Bundesland

###Wähler
1. Abgabe seiner Stimme

###Wahllokalleiter
1. Einfügen von abgegebenen Wahlstimmen
2. Ändern von Wahlstimmen der aktuellen Wahl (bei Fehlern)

###Administrator
1. Einfügen, Ändern und Löschen von Wahlen
2. Einfügen, Ändern und Löschen von Parteien
3. Einfügen, Ändern und Löschen von Länderlisten von Parteien für eine Wahl
4. Einfügen, Ändern und Löschen von Direktkandidaten für Wahlkreise
5. Einfügen, Ändern und Löschen von Bundesländern
6. Einfügen, Ändern und Löschen von Wahlkreisen
7. Einfügen, Ändern und Löschen von Wahlbezirke
8. Einfügen, Ändern und Löschen von Wahllokale (mit Adressen und Städten)


##Nicht-Funktionale Anforderungen

- Datenschutzrichtlinien müssen berücksichtigt werden
- Webinterface nutzbar mit Firefox 24.0, Chrome 30.0.1599.101 und Safari 7.0
- Ermittlung der Wahlergebnisse muss in unter 1 Minute erfolgen
- Das System muss am Wahltag bis zu 20.000 Inserts pro Stunde unterstützen
- Das Server-System muss auf Windows, Linux und Server portierbar sein

##Abnahmekriterien

- 