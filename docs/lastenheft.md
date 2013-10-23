#Lastenheft
Der Zweck dieses Dokuments ist die Beschreibung von Anforderungen an das zu entwickelnde Wahlinformationssystem (WIS) zur Verwaltung von Informationen zu Bundestagswahlen nach deutschem Wahlrecht.
Das geforderte System soll einerseits die Möglichkeit bieten, relevante Informationen zu Bundestagswahlen zu speichern, sowie andererseits die Erstellung von Analysen/Statistiken zu Wahlergebnissen ermöglichen. Hierzu zählen insbesondere auch Vergleiche mit Wahlergebnissen aus vergangenen Wahlen.
Neben der Implementierung einer geeigneten Möglichkeit zur Persistierung von Datensätze ist die Erstellung eines Web-UIs ebenfalls Ziel dieses Projekts.

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

1. As an analyst, I want to compare election outcomes within an arbitrary constituency or state (between arbitrary constituencies, respctively).
2. As an analyst, I want to compare election outcomes between different election years (between arbitrary constituencie or within an arbitrary constituency or state).
3. As an analyst, I want to determine a certain constituency´s winner (cf. direct mandate).
4. As an analyst, I want to list all mandates of an arbitrary state (of all states, respectively).
5. As an analyst, I want to determine all coalition possibilites for an arbitrary election.
6. As an analyst, I want to determine a state´s amount of chairs in the Bundestag for an arbitrary election.
7. As an analyst, I want to determine the distribution of a state´s chairs in the Bundestag among the available parties.

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