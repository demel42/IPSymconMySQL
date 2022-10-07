# IPSymconMySQL

[![IPS-Version](https://img.shields.io/badge/Symcon_Version-6.0+-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Installation](#3-installation)
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguration)
6. [Anhang](#6-anhang)
7. [Versions-Historie](#7-versions-historie)

## 1. Funktionsumfang

Ein vereinfachtes Interface zu einer MySQL-Datenbank für die Basis-Befehle, es wird mit dem mysqli-Interface (http://php.net/manual/de/class.mysqli.php) gearbeitet.

## 2. Voraussetzungen

 - IP-Symcon ab Version 6.0
 - Datenbank-Server
   - Typ: MySQL oder MariaDB
   - Datenbank mit ausreichenden Zugriffsrechten

## 3. Installation

### a. Laden des Moduls

Die Konsole von IP-Symcon öffnen. Im Objektbaum unter Kerninstanzen die Instanz __*Modules*__ durch einen doppelten Mausklick öffnen.

In der _Modules_ Instanz rechts oben auf den Button __*Hinzufügen*__ drücken.

In dem sich öffnenden Fenster folgende URL hinzufügen:

`https://github.com/demel42/IPSymconMySQL.git`

und mit _OK_ bestätigen.

Anschließend erscheint ein Eintrag für das Modul in der Liste der Instanz _Modules_

### b. Einrichtung in IPS

In IP-Symcon nun _Instanz hinzufügen_ (_CTRL+1_) auswählen unter der Kategorie, unter der man die Instanz hinzufügen will, und Hersteller _(sonstiges)_ und als Gerät _MySQL_ auswählen.

In dem Konfigurationsdialog den Datenbank-Server eintragen (Name oder IP-Adresse sind zulässig), Datenbank und Zugriffdaten

## 4. Funktionsreferenz

### zentrale Funktion

`(mysqli Object) = MySQL_Open(integer $InstanzID)`

Öffnet die in der Konfiguration angegebene Datenbank und liefert ein _mysqli Object_ zurück. Die Verbindung muss nicht geschlossen werden, aufgrund der _Persistent Connections_ (http://php.net/manual/de/mysqli.persistconns.php) werden offene Verbindungen später wieder verwendet.

`MySQL_Close(integer $InstanzID, object $dbHandle)`

Schliesst die Datenbankverbindung.

`MySQL_ExecuteSimple(integer $InstanzID, string Statement)`

Öffnet die Datenbank und führt des SQL-Statement aus; das Ergebnis ist bei einer Abfrage dier Ergebnismenge als ein Array von Objekten bzw. _false bei einem Fehler (Fehlermeldung dann im Medungsfenster) oder by _update_ / _insert_ / _delete_ wird _true_ oder _false_ geliefert.

`MySQL_Query(integer $InstanzID, object $dbHandle, string Statement)`

analog zu _MySQL_ExecuteSimple_, jedoch wird der _dbHandle_ übergeben.

## 5. Konfiguration:

### Variablen

| Eigenschaft | Typ     | Standardwert | Beschreibung |
| :---------- | :------ | :----------- | :----------- |
| Server      | string  |              | Hostname / IP-Adresse des Datenbank-Servers |
| Port        | integer | 3306         | Port, unter dem der Datenbank-Server kommuniziert |
| Benutzer    | string  |              | Datenbank-Benutzer |
| Passwort    | string  |              | Passwort des Datenbank-Benutzers |
| Datenbank   | string  |              | zu benutzende Datenbank |

### Schaltflächen

| Bezeichnung     | Beschreibung |
| :-------------- | :----------- |
| Verbindungstest | Testet die Datenbankverbindung |

## 6. Anhang

GUIDs
- Modul: `{C0E06BE4-D9D8-4208-8CDB-93D161A7CA98}`
- Instanzen:
  - MySQL: `{7B420C9A-F0FF-4C65-925D-6FEE0D8F1A55}`

## 7. Versions-Historie

- 1.10.1 @ 07.10.2022 13:59
  - update submodule CommonStubs
    Fix: Update-Prüfung wieder funktionsfähig

- 1.10 @ 05.07.2022 09:44
  - Verbesserung: IPS-Status wird nur noch gesetzt, wenn er sich ändert

- 1.9.2 @ 22.06.2022 10:33
  - Fix: Angabe der Kompatibilität auf 6.2 korrigiert

- 1.9.1 @ 28.05.2022 11:37
  - update submodule CommonStubs
    Fix: Ausgabe des nächsten Timer-Zeitpunkts

- 1.9 @ 27.05.2022 15:30
  - update submodule CommonStubs
  - einige Funktionen (GetFormElements, GetFormActions) waren fehlerhafterweise "protected" und nicht "private"
  - interne Funktionen sind nun entweder private oder nur noch via IPS_RequestAction() erreichbar

- 1.8.2 @ 17.05.2022 15:38
  - update submodule CommonStubs
    Fix: Absicherung gegen fehlende Objekte

- 1.8.1 @ 10.05.2022 15:06
  - update submodule CommonStubs

- 1.8 @ 05.05.2022 17:53
  - IPS-Version ist nun minimal 6.0
  - Anzeige der Modul/Bibliotheks-Informationen, Referenzen und Timer
  - Implememtierung einer Update-Logik
  - Überlagerung von Translate und Aufteilung von locale.json in 3 translation.json (Modul, libs und CommonStubs)
  - diverse interne Änderungen

- 1.7 @ 18.12.2020 14:57
  - PHP_CS_FIXER_IGNORE_ENV=1 in github/workflows/style.yml eingefügt

- 1.6 @ 12.09.2020 11:40
  - LICENSE.md hinzugefügt
  - lokale Funktionen aus common.php in locale.php verlagert
  - Traits des Moduls haben nun Postfix "Lib"
  - define's durch statische Klassen-Variablen ersetzt

- 1.5 @ 30.12.2019 10:56
  - Anpassungen an IPS 5.3
    - Formular-Elemente: 'label' in 'caption' geändert

- 1.4 @ 10.10.2019 17:27
  - Anpassungen an IPS 5.2
    - IPS_SetVariableProfileValues(), IPS_SetVariableProfileDigits() nur bei INTEGER, FLOAT
    - Dokumentation-URL in module.json
  - Umstellung auf strict_types=1
  - Umstellung von StyleCI auf php-cs-fixer

- 1.3 @ 20.03.2019 14:56
  - Anpassungen IPS 5, Abspaltung von Branch _ips_4.4_

- 1.2 @ 10.03.2019 15:14
  - Fehlerkorrektur: Datenbank-Port wurde nicht gesetzt

- 1.1 @ 20.09.2018 17:28
  - Versionshistorie dazu,
  - define's der Variablentypen,
  - Schaltfläche mit Link zu README.md im Konfigurationsdialog

- 1.0 @ 24.03.2018 15:20
  - Initiale Version
