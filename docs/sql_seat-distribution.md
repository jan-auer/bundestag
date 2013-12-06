# SQL-Anfrage zur Auswerung

Die SQL-Anfrage zur Berechnung der Wahlergebnisse orientiert sich an der [Beschreibung des Bundeswahlleiters vom 09.10.2013](http://bundeswahlleiter.de/de/aktuelle_mitteilungen/downloads/20131009_Erl_Sitzzuteilung.pdf).
Der dort beschriebene Algorithmus besteht im Wesentlichen aus fünf Schritten:

1. **Wie viele Sitze stehen einem Bundesland zu?** Ausschlaggebend ist die deutsche Bevölkerung des Bundeslandes. In jedem Bundesland wird pro Sitz in etwa die gleiche Anzahl Personen benötigt. In Summe müssen genau 598 Sitze verteilt werden.
2. **Wie verteilt sich das Sitzkontingent eines Bundeslandes auf die zu berücksichtigenden Parteien**, die in diesem Bundesland mit einer Landesliste angetreten sind? Ausschlaggebend sind die Zweitstimmen der Landeslisten. In Summe müssen genau so viele Sitze verteilt werden, wie dem Bundesland zustehen.
3. **Wie viele Sitze bekommt eine Partei nachdem Schritt 1 und 2 durchgeführt wurden?** Ausschlaggebend ist entweder die nach Zweitstimmen ermittelte Sitzzahl oder die Anzahl der gewonnenen Wahlkreise einer jeden Landesliste. Der höhere Wert zählt.
4. **Wie viele Sitze müsste der Bundestag dann insgesamt haben, damit alle Parteien auch die für sie ermittelte Mindestsitzzahl erhalten?** Wie viele Sitze entfallen dann auf jede Partei? Ausschlaggebend ist das Verhältnis der Zweitstimmen der Parteien. Jede Partei soll pro Sitz in etwa die gleiche Anzahl Stimmen benötigen.
5. **Wie viele Sitze einer Partei entfallen auf ihre Landeslisten?** Ausschlaggebend ist die Anzahl der Zweitstimmen. Aber es dürfen nicht weniger Sitze auf die jeweilige Landesliste entfallen, als die Partei Wahlkreise gewonnen hat. 

In der ursprünglichen Variante wird die Berechnungsgrundlage (Anzahl der Bevölkerung oder Zweitstimmen) durch einen geeigneten Wert (*"Divisor"*) geteilt und anschließend kaufmännisch gerundet, sodass sie in Summe die Sitzkontingente den Gesamtwert ergeben. 



* `state_party_candidates`: Anzahl der gewählten Direktkandidaten pro Landesliste. 
* `state_party_votes`: Anzahl der gültigen Zweitstimmen pro Landesliste. Es werden nur Parteien berücksichtigt, die die *5%-Hürde* geschafft haben oder mindestens drei Direktkandidaten stellen.
* `state_party_seats`: Anzahl der Sitze jeder Landesliste inkl. Überhangmandate. Die Ausgleichsmandate sind hier noch nicht berechnet. 
* `party_seats`: Bundesweite Anzahl der Sitze pro Partei. Ausgleichsmandate sind bereits berücksichtigt. 
* `party_state_seats`: Anzahl der Sitze jeder Landesliste inkl. aller Überhangmandate und Ausgleichsmandate. 

Die SQL-Anfragen zum Erzeugen einer View beinhalten wiederum mehrere *Common Table Expressions* (**WITH**-Statements), um die Lesbarkeit zur erhöhen. Diese CTEs sind so konzeptioniert und benannt, dass sie bei Bedarf ohne Weiteres in eine eigene View ausgelagert werden können. 

Die Sitzverteilungen werden jeweils in CTEs mit dem Namen `dhondt` berechnet, auch wenn es sich, streng genommen, um das Höchstzahlverfahren nach Sainte-Lague/Schepers handelt. Darin werden jedem Eintrag der Aufteilungsgrundlage (z.B. Bundesländer) Divisoren durch die Funktion `generate_series(1, seats)` zugeordnet und der Rang basierend auf `population / divisor` berechnet. Im nächsten Schritt werden die Einträge gezählt, deren Rank  kleiner oder gleich der verfügbaren Sitzzahl ist.

Zum derzeitigen Stand sind keine der Views materialisiert. Aus Performance-Gründen kann es allerdings durchaus angebracht sein, einige der Views nur bei neuen Wahldaten zu berechnen und dann materialisiert abzuspeichern. 


## Bekannte Probleme

* Die Auswertung arbeitet derzeit noch auf den aggregierten Wahlergebnissen. 
* Bei der Berechnung der finalen Sitzverteilung für die Landeslisten wird nicht die Mindestsitzzahl durch Direktmandate berücksichtigt.