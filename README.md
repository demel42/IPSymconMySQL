# IPSymconMySQL

[![IPS-Version](https://img.shields.io/badge/Symcon_Version-4.4+-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Module-Version](https://img.shields.io/badge/Modul_Version-1.1-blue.svg)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![StyleCI](https://github.styleci.io/repos/126709827/shield?branch=master)](https://github.styleci.io/repos/126709827)

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

 - IP-Symcon ab Version 4.4
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

| Eigenschaft               | Typ      | Standardwert | Beschreibung |
| :-----------------------: | :-----:  | :----------: | :----------------------------------------------------------------------------------------------------------: |
| Server                    | string   |              | Hostname / IP-Adresse des Datenbank-Servers |
| Port                      | integer  | 3306         | Port, unter dem der Datenbank-Server kommuniziert |
| Benutzer                  | string   |              | Datenbank-Benutzer |
| Passwort                  | string   |              | Passwort des Datenbank-Benutzers |
| Datenbank                 | string   |              | zu benutzende Datenbank |

### Schaltflächen

| Bezeichnung                  | Beschreibung |
| :--------------------------: | :------------------------------------------------: |
| Verbindungstest              | Testet die Datenbankverbindung |

## 6. Anhang

GUIDs
- Modul: `{C0E06BE4-D9D8-4208-8CDB-93D161A7CA98}`
- Instanzen:
  - MySQL: `{7B420C9A-F0FF-4C65-925D-6FEE0D8F1A55}`

## 7. Versions-Historie

- 1.1 @ 20.09.2018 17:28<br>
  - Versionshistorie dazu,
  - define's der Variablentypen,
  - Schaltfläche mit Link zu README.md im Konfigurationsdialog

- 1.0 @ 24.03.2018 15:20<br>
  Initiale Version
