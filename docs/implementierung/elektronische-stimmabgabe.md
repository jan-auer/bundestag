# Elektronische Stimmabgabe

## Prozess

Dieser Abschnitt beschreibt den typischen Wahlprozess unter Anwendung der elektronischen Stimmabgabe, sowohl hinsichtlich organisatorischen als auch technischen Aspekten.
Zur anschaulichen Darstellung werden im Folgenden ein Wähler Hans sowie eine Wahlhelferin Anke betrachtet.

### 1. Öffnung des Wahlbüros

Für den Zugriff auf die Wahllokalseiten vom **WIS** benötigt Anke aus Sicherheitsgründen eine VPN-Verbindung. Nach dem erfolgreichen Verbindungsaufbau ruft sie mit einem gängingen Webbrowser die WIS-URL "/location" auf.

![Wahlkreiswahl I](screenshots/screenshot_5.png)

In dieser Ansicht kann Anke nun den Wahlkreis auswählen, in dem sie sich befindet.

![Wahlkreiswahl I](screenshots/screenshot_6.png)

Durch Wahl eines Wahlkreises gelangt sie anschließend auf eine Seite zur Registrierung von neune Wählern für die elektronische Stimmabgabe.

![Wählerfreischaltung I](screenshots/screenshot_7.png)

##### Technisch

Der initial ausgewählte Wahlkreis wird für alle anschließenden Wähler-Registrierungen verwendet. Eine entsprechende Überprüfung, ob Anke berechtigt ist, den jeweiligen Wahlkreis auszuwählen wurde derzeit bewusst nicht realisiert.

### 2. Check-In im Wahlbüro

Hans betritt das Wahlbüro und übergibt, wie bereits beim herkömmlichen Wahlgang ohne elektronische Stimmabgabe, der Wahlhelferin Anke seinen Personalausweis.
Anke kontrolliert die Personalien des (potentiellen) Wählers anhand ihrer Wählerliste (was ebenfalls dem herkömmlichen Prozess entspricht) und stellt deren Korrektheit fest. Im Anschluss ließt Anke Hans' Personalausweisnummer und trägt diese in ihre bereits geöffnete Eingabemaske ein (vgl. Schritt 1).

![Wählerfreischaltung II](screenshots/screenshot_8.png)

Daraufhin wird Anke vom WIS angeboten, den generierten Wahlschlüssel auszudrucken.

![Erfolgreiche Wählerfreischaltung](screenshots/screenshot_9.png)

Anke druckt den Wahlschlüssel und händigt ihn Hans aus.

##### Technisch

Bei Eingabe der Personalausweisnummer wird auf Basis selbiger ein MD5-Hash erzeugt - dieser wird im Anschluss als Wahlschlüssel verwendet.
Der generierte Hash wird in der DB persistiert, die entsprechende Entität wird als "noch nicht gewählt" für den Wahlkreis geflaggt.

##### Organisatorisch

Die Verwendung eines derartigen Wahlschlüssels gewährleistet zunächst eine Authentifizierung. Die Überprüfung, ob ein potentieller Wähler im aktuellen Wahllokal tatsächlich wählen darf, wird wie im herkömmlichen Prozess durch eine Wahlhelferin durchgeführt.
Das hier exemplarisch gewählte Verfahren, nämlich die Verschlüsselung der Personalausweisnummer durch MD5, kann natürlich beliebig ausgetauscht werden - beispielsweise durch Verwendung einer eindeutigeren Basis oder durch Verwendung eines anderen Algorithmus'.

### 3. Eigentliche Wahl

Hans begibt sich zunächst in eine Wahlkabine, in welcher er einen laufenden PC mitsamt Tastatur und Maus vorfindet, auf welchem die WIS-Site "/vote" angezeigt wird. Er stellt fest, dass das Verlassen sowie die Navigation innerhalb der Seite bzw. des Browsers nicht möglich ist.
Die Hans angezeigte Seite fordert ihn dazu auf, den erhaltenen Wahlschlüssel einzugeben. Hans gibt seinen persönlichen Wahlschlüssel ein und bestätigt.

![Wähler-Login](screenshots/screenshot_11.png)

Hans wird nun eine optisch dem herkömmlichen Wahlzettel nachempfundene Seite angezeigt, auf welcher er seine Erst- sowie Zweitstimme angibt.

![Wahlzettel](screenshots/screenshot_12.png)

Hans bestätigt seine Wahl durch Drücken eines Buttons mit der Beschriftung "Stimme jetzt abgeben", woraufhin WIS ihm eine Übersicht über seine abgegebene Erst- sowie Zweitstimme anzeigt. Selbstverständlich hat er hier auch noch einmal die Möglichkeit, seine Abgabe zu korrigieren.

![Überprüfung der Stimmabgabe](screenshots/screenshot_13.png)

Hans kontrolliert seine Stimmabgabe und bestätigt diese im Anschluss endgültig durch Klick auf den "Bestätigen"-Button.
Daraufhin teilt WIS Hans mit, dass dessen Stimme erfolgreich abgegeben wurde.

![Bestätigung der Stimmabgabe](screenshots/screenshot_14.png)

Hans stellt nach kurzer Zeit fest, dass die soeben noch angezeigte Erfolgsmeldung verschwunden ist und nun wieder die initiale Sicht zur Eingabe eines Wahlschlüssels angezeigt wird.

Hans verabschiedet sich von Anke und verlässt das Wahllokal.

##### Technisch

Bei endgültiger Stimmabgabe wird die abgegebene Stimme persistiert, das "voted"-Flag der den aktuellen Wahlschlüssel beinhaltenden Entität wird auf "true" gesetzt - hierdurch wird gewährleistet, dass Hans nicht noch einmal wählen kann. Diese Entität wird nicht mit der abgegebenen Stimme verknüpft, so dass nicht nachvollzogen werden kann, welche Wahl Hans getätigt hat.

Der zur Wahl bereit gestellte PC verwendet ein Kiosk-Tooling zur Verhinderung von nicht vorgesehenen Systemzugriffen (wie beispielsweise Zugriffe auf das dahinter liegende OS, manuelle URL-Eingaben etc.).
Die Netzwerkverbindung des Wahl-PCs wurde darüber hinaus durch eine Fachkraft so vorkonfiguriert, dass Internetzugriffe per VPN erfolgen - die "/vote"-Seiten des WIS sind - ebenso wie die Seite zur Registrierung eines neuen Wählers - nur durch einen entsprechenden VPN-Tunnel zu erreichen.

##### Organisatorisch

Eine mehrfache Stimmenabgabe wird durch Verwendung des "voted"-Flags verhindert, siehe oben.

Eine korrumpierende Verwendung des Wahl-PCs wird weiterhin ausgeschlossen, die Erreichbarkeit der Seiten zur elektronischen Stimmabgabe ausschließlich aus Wahllokalen wird durch die VPN-Verbindung sichergestellt.

## IT-Security

IT-spezfische Korruptionsmöglichkeiten (wie beispielsweise SQL-Injection) werden durch Prepared-Statements im Rahmen des Persistenzframeworks Doctrine verhindert.