# MySQL

Modul für IP-Symcon ab Version 4.

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguration)
6. [Anhang](#6-anhang)  

## 1. Funktionsumfang

Ein vereinfachtes Interface zu einer MySQL-Datenbank für die Basis-Befehle, es wird mit dem mysqli-Interface (http://php.net/manual/de/class.mysqli.php) gearbeitet.

## 2. Voraussetzungen

 - IPS 4.x
 - Datenbank-Server
   - Typ: MySQL oder MariaDB
   - Datenbank mit ausreichenden Zugriffsrechten

## 3. Installation

### a. Laden des Moduls

Die IP-Symcon (min Ver. 4.x) Konsole öffnen. Im Objektbaum unter Kerninstanzen die Instanz __*Modules*__ durch einen doppelten Mausklick öffnen.

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
| Verbindunstest               | Testet die Datenbankverbindung |

## 6. Anhang

GUID: `{C0E06BE4-D9D8-4208-8CDB-93D161A7CA98}` 
