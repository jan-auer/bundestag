# Lastenheft

## Einführung

Mit **WIS** wird ein Wahlinformationssystem für deutsche Bundestagswahlen entwickelt, mit dem Interessierte vor der Wahl Informationen abfragen, sowie nach der Wahl Analysen und Statistiken über die Wahlergebnisse erstellen können. Hierzu zählen insbesondere auch Vergleiche der Wahlergebnisse mit vorangegangen Wahlen.

Das Wahlsystem ist als Webapplikation konzipiert und stellt dort zusätzlich zusätzlich zu den Wahlergebnissen der letzten Wahlen auch allgemeine Informationen zu den Parteien dar. Im weiteren Verlauf soll die Stimmabgabe am Wahltag ebenfalls durch eine eigene Schnittstelle des Wahlsystems ermöglicht werden.

## Benutzergruppen

- Analytiker (Anonym)
- Wähler
- Wahllokal
- Administrator

## Benutzer-Schnittstelle
Web-Frontend

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


## Funktionale Anforderungen

### Bundeswahlleiter

1. Als Bundeswahlleiter möchte ich gewährleisten, dass nur solche Wähler eine Stimme abgeben können, welche sich zuvor eindeutig authentifiziert haben.
2. Als Bundeswahlleiter möchte ich gewährleisten, dass nur solche Wahllokalleiter auf das System zugreifen können, welche sich zuvor eindeutig authentifiziert haben.
3. Als Bundeswahlleiter möchte ich gewährleisten, dass der Administratorzugriff auf das System nur nach vorheriger eindeutiger Authentifikation möglich ist.

### Analytiker

1. Als Analytiker möchte ich Wahlergebnisse bundesweit, pro Bundesland sowie pro Wahlkreis vergleichen.
2. Als Analytiker möchte ich Wahlergebnisse (bundesweit, pro Bundesland sowie pro Wahlkreis) aus verschiedenen Jahren vergleichen.
3. Als Analytiker möchte ich den Gewinner eines Wahlkreises (vgl. Direktmandat) ermitteln.
4. Als Analytiker möchte ich alle Mandate innerhalb eines Bundeslands (bzw. auch bundesweit) auflisten.
5. Als Analytiker möchte ich alle Koalitionsmöglichkeiten für ein beliebiges Wahlergebnis bestimmen.
6. Als Analytiker möchte ich die Anzahl der Sitze im Bundestag eines beliebigen Bundeslands bestimmen.
7. Als Analytiker möchte ich die Sitzverteilung auf Parteien für ein beliebiges Bundesland bestimmen.

### Wähler

1. Als Wähler möchte ich die Möglichkeit haben, eine Erst- und eine Zweitstimme abgeben.

### Wahllokalleiter

1. Als Wahllokalleiter möchte ich abgegebene Stimmen in das System einpflegen können.
2. Als Wahllokalleiter möchte ich im Fehlerfall Wahlstimmen einer aktuellen Wahl ändern können.

### Administrator

1. Als Administrator möchte ich eine beliebige Wahl hinzufügen, ändern oder löschen.
2. Als Administrator möchte ich eine beliebige Partei hinzufügen, ändern oder löschen.
3. Als Administrator möchte ich die Länderliste einer beliebigen Partei für eine beliebige Wahl hinzufügen, ändern oder löschen. 
4. Als Administrator möchte ich den Direktkandidaten eines beliebigen Wahlkreises für eine beliebige Wahl hinzufügen, ändern oder löschen.
5. Als Administrator möchte ich ein beliebiges Bundesland hinzufügen, ändern oder löschen.
6. Als Administrator möchte ich einen beliebigen Wahlkreis hinzufügen, ändern oder löschen.
7. Als Administrator möchte ich einen beliebigen Wahlbezirk hinzufügen, ändern oder löschen.
8. Als Administrator möchte ich ein beliebiges Wahllokal hinzufügen, ändern oder löschen.

## Nicht-Funktionale Anforderungen

- Datenschutzrichtlinien müssen berücksichtigt werden
- Webinterface nutzbar mit Firefox 24.0, Chrome 30.0.1599.101 und Safari 7.0
- Ermittlung der Wahlergebnisse muss in unter 1 Minute erfolgen
- Das System muss am Wahltag bis zu 20.000 Inserts pro Stunde unterstützen
- Das Server-System muss auf Windows, Linux und Server portierbar sein

## Abnahmekriterien

- 