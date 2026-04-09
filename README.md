# NAW Beheersysteem

Webapplicatie voor het beheren van klantgegevens (NAW) zonder technische kennis van MySQL of PHP.

## Functionaliteiten

- **Create**: Nieuwe klanten toevoegen
- **Read**: Klanten bekijken, zoeken en sorteren
- **Update**: Klantgegevens wijzigen
- **Delete**: Klanten verwijderen met bevestiging

## Installatie

### Stap 1: XAMPP starten
1. Start XAMPP Control Panel
2. Start **Apache** en **MySQL**

### Stap 2: Database importeren
1. Ga naar [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Klik **"Import"** (bovenaan)
3. Kies bestand: `database.sql`
4. Klik **"Go"**

### Stap 3: Applicatie openen
- Ga in je browser naar: [http://localhost/naw-systeem](http://localhost/naw-systeem)

## Gebruik

| Functie | Hoe |
|---------|-----|
| Klant toevoegen | Klik "Nieuwe klant" → vul formulier in → klik "Opslaan" |
| Zoeken | Typ in zoekbalk → druk Enter |
| Sorteren | Klik op kolomkop (Naam, Woonplaats) |
| Wijzigen | Klik "Wijzig" → pas aan → klik "Opslaan" |
| Verwijderen | Klik "Verwijder" → bevestig in popup |

## Technieken

- **Backend**: PHP 8.0+ met PDO
- **Database**: MySQL
- **Frontend**: Bootstrap 5
- **Veiligheid**: Prepared statements, input validatie

## Projectstructuur
