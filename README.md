# E-mailverificatie API (PHP)
Een eenvoudige API voor het verifiëren van e-mailadressen en het controleren van geldige MX-records van domeinen.

**Let op de API-sleutel in de code; er is een limiet, dus het kan zijn dat deze niet altijd werkt! Wil je deze API echt gebruiken? Stuur me dan een bericht en je ontvangt een andere API-sleutel!**

## API Endpoint
De API controleert of een e-mailadres geldig is en of het domein in staat is om e-mails te ontvangen via MX-records.

### Endpoint:
```http
GET /?key={api_key}&email={email}
```

### Parameters:
- **key**: Je API-sleutel. Dit voorkomt ongeautoriseerde toegang.
- **email**: Het e-mailadres dat je wilt verifiëren.

- ### Voorbeeld:
```http
GET http://api.mail-verify.ict-hoogeveen.nl/?key=<KEY>&email=info@ict-hoogeveen.nl
```

## Responses
De API retourneert een JSON-object met de volgende mogelijke responses:

### 1. Ongeldige API-sleutel
- **Status**: `401 Unauthorized`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "De API sleutel staat gewoon in de code? Ctrl + C en Ctrl + V is niet zo moeilijk he!"
    }
    ```
- **Betekenis**: De meegegeven API-sleutel is onjuist of ontbreekt. Zorg ervoor dat je de correcte sleutel gebruikt.

### 2. Geen e-mailadres meegegeven
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Sjonge jonge... je moet wel het email adres meegeven he!"
    }
    ```
- **Betekenis**: De parameter 'email' is niet opgegeven. Zorg ervoor dat je een e-mailadres meestuurt.

### 3. Ongeldig e-mailadres
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Dan moet je wel een echt email adres meegeven he!"
    }
    ```
- **Betekenis**: Het opgegeven e-mailadres is ongeldig. Zorg ervoor dat het correct geformatteerd is.

### 4. Domein heeft geen MX-records
- **Status**: `200 OK`
- **Response**:
    ```json
    {
        "status": false,
        "message": "Dit domein heeft geen MX records, dat is een beetje jammer he!",
        "email": "info@ict-hoogeveen.nl",
        "domain": "ict-hoogeveen.nl",
        "mx_records": false
    }
    ```
- **Betekenis**: Het domein van het e-mailadres heeft geen MX-records en kan mogelijk geen e-mails ontvangen.

### 5. Succesvolle verificatie met volledige DNS-informatie
- **Status**: `200 OK`
- **Response**:
    ```json
    {
        "status": true,
        "message": "Eyyy! Dit ziet er goed uit.",
        "email": "info@ict-hoogeveen.nl",
        "domain": "ict-hoogeveen.nl",
        "dns_records": {
            "CNAME": [],
            "A": [],
            "AAAA": [],
            "NS": [],
            "MX": [],
            "TXT": []
        }
    }
    ```
- **Betekenis**: Het e-mailadres is geldig en het domein heeft de juiste DNS-records.

## Installatie

1. **Clone de repository:**
    ```bash
    git clone https://github.com/SN1P3S101/api-email-verification.git
    ```

2. **Voer de API lokaal uit met PHP:**
    - Gebruik PHP's ingebouwde webserver:
    ```bash
    php -S localhost:8000
    ```

3. **Zorg voor een actieve internetverbinding:**
    - De API heeft internettoegang nodig voor DNS-queries.

4. **Test de API:**
    - Gebruik een tool zoals `curl` of open je browser om een GET-verzoek te sturen:
    ```bash
    curl "http://localhost:8000/?key=JOUW_API_SLEUTEL&email=test@example.com"
    ```

## Vereisten

- **PHP 8.2 of hoger:** Zorg ervoor dat PHP correct is geïnstalleerd.
- **Internettoegang:** De API heeft een werkende internetverbinding nodig voor het ophalen van DNS-gegevens.
