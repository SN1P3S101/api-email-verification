# api-email-verification
Een eenvoudige API voor het verifiëren van e-mailadressen en het controleren van geldige MX-records van domeinen.

**Neem een kijkje in de code voor de key, er is een limiet aanwezig dus het kan zijn dat deze niet altijd werkt!**
**Wil je deze echt gebruiken? Stuur me dan een berichtje en dan krijg je een andere key!**

## API Endpoint
De API controleert of een e-mailadres geldig is en of het domein in staat is om e-mails te ontvangen door middel van MX-records.

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
        "message": "The key is right there... why can't you just copy it?"
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
- **Betekenis**: Het vereiste parameter 'email' is niet opgegeven in de aanvraag. Zorg ervoor dat je een e-mailadres meestuurt.

### 3. Ongeldig e-mailadres
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Dan moet je wel een echt email adres meegeven he!"
    }
    ```
- **Betekenis**: Het opgegeven e-mailadres is ongeldig. Zorg ervoor dat het een correct geformatteerd e-mailadres is.

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
- **Betekenis**: Het domein dat bij het e-mailadres hoort heeft geen MX-records en kan dus waarschijnlijk geen e-mails ontvangen.

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
- **Betekenis**: Het e-mailadres lijkt geldig en het domein heeft correcte DNS-records (zoals A, MX, TXT, NS). De verschillende DNS-records worden weergegeven in het JSON-resultaat.

## Installatie

1. **Clone de repository:**
    ```bash
    git clone https://github.com/jouwgebruikernaam/email-verification-api.git
    ```

2. **Voer de API lokaal uit met PHP:**
    - Gebruik PHP's ingebouwde webserver om de API te draaien:
    ```bash
    php -S localhost:8000
    ```

3. **Zorg voor een actieve internetverbinding:**
    - De API heeft toegang tot het internet nodig om DNS-queries uit te voeren.

4. **Test de API:**
    - Open je browser of gebruik een tool zoals `curl` om een GET-verzoek naar het lokale endpoint te sturen:
    ```bash
    curl "http://localhost:8000/?key=JOUW_API_SLEUTEL&email=test@example.com"
    ```

## Vereisten

- **PHP 8.2 of hoger:** Zorg ervoor dat PHP correct is geïnstalleerd op je systeem.
- **Internettoegang:** De API heeft een werkende internetverbinding nodig om DNS-gegevens extern op te halen.
