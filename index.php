<?php

    //! ICT-Hoogeveen | Wesley Schreur | SN1P3S
    // Welkom hier bij mijn super gave email verificateur 3000!
    // Hier is je supergeheime api sleutel: ICT-96PpYACPkT?TEw6MQfmH-e4p21tNR/86FYpeNU8tNSbIpmRDS5-EpGF?5iwA



    header('Content-Type: application/json');
    // Info: Stel de Content-Type header in op application/json om aan te geven dat de response in JSON-formaat is.

    $key = 'ICT-96PpYACPkT?TEw6MQfmH-e4p21tNR/86FYpeNU8tNSbIpmRDS5-EpGF?5iwA';
    // Info: Definieer de verwachte API-sleutel.

    $provided_key = isset($_GET['key']) ? $_GET['key'] : '';
    // Info: Haal de 'key' parameter op uit de GET request. Als deze niet is ingesteld, gebruik dan een lege string.

    $email = isset($_GET['email']) ? $_GET['email'] : '';

    // Natuurlijk moeten we even controleren of de key wel is meegegeven, want we willen niet dat iedereen toegang heeft ;)
    if ($provided_key !== $key) {

        http_response_code(401);
        // Info: Stel de HTTP response code in op 401 Unauthorized.

        echo json_encode([
            'status' => 'error',
            'message' => 'De API sleutel staat gewoon in de code? Ctrl + C en Ctrl + V is niet zo moeilijk he!'
        ]);
        // Info: Geef een JSON response terug die de fout aangeeft.

        exit;
        // Info: Beëindig de uitvoering van het script.

    }

    // Even een snelle check om te kijken of er wel wat data is meegegeven
    if (empty($email)) {

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Sjonge jonge... je moet wel het email adres meegeven he!'
        ]);

        exit;

    }

    // En dan nu DE controle, is het email adres wel echt een email adres?
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Info: Controleer of het emailadres geldig is met filter_var() en de FILTER_VALIDATE_EMAIL filter.
        // Info: filter_var() valideert en filtert data op basis van een specifieke filter; hier controleren we of het een geldig emailadres is.

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Dan moet je wel een echt email adres meegeven he!'
        ]);

        exit;

    }

    // Oke oke, laten we dan nu maar het email adres verifieren... voor het echie!
    list(, $domain) = explode('@', $email);
    // Info: Splits het emailadres op de '@' en verkrijg het domein-gedeelte.

    if (!checkdnsrr($domain, 'MX')) {
        // Info: Controleer met checkdnsrr() of het domein MX-records heeft.
        // Info: checkdnsrr() controleert of er DNS-records van een bepaald type bestaan voor het opgegeven domein; hier controleren we op 'MX' records die mailservers aangeven.

        echo json_encode([
            'status' => false,
            'message' => 'Dit domein heeft geen MX records, dat is een beetje jammer he!',
            'email' => $email,
            'domain' => $domain,
            'mx_records' => false
        ]);

        exit;

    }

    // Oke... even serieus nu! Nu we hier beland zijn weten we dat het werkt toch? Nou niet helemaal...
    // We hebben dan nu wel de MX-records gecontroleerd maar dat betekend niet direct dat dit een geldig adres is!
    // Omdat er MX records zijn betekend het wel dat er iets is ingesteld om emails te ontvangen, maar het lokale deel (voor de '@') kan nog steeds ongeldig zijn.


    $dns = [];
    // Info: Initialiseer een array om de DNS-records op te slaan.

    $records = ['CNAME', 'A', 'AAAA', 'NS', 'MX', 'TXT'];
    // Info: Definieer een array met DNS-recordtypes om op te halen.

    foreach ($records as $record) {

        $dns[$record] = dns_get_record($domain, constant('DNS_' . $record));
        // Info: Haal DNS-records van het type $record op voor het domein en sla ze op in de $dns array.
        // Info: Gebruik constant('DNS_' . $record) om de juiste DNS_* constant te krijgen.

    }

    echo json_encode([
        'status' => true,
        'message' => 'Eyyy! Dit ziet er goed uit.',
        'email' => $email,
        'domain' => $domain,
        'dns_records' => $dns
    ]);

?>
