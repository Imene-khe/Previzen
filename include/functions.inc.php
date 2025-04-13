<?php

include  __DIR__ . '/utile.inc.php';
function get_apod_data(string $api_key, string $date): ?array {
    $url = "https://api.nasa.gov/planetary/apod?api_key=$api_key&date=$date&thumbs=true";
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

function get_apod_html(string $api_key, string $date): string {
    $url = "https://api.nasa.gov/planetary/apod?api_key=$api_key&date=$date&thumbs=true";
    $response = @file_get_contents($url);
    
    $data = $response ? json_decode($response, true) : null;

    if (!$data) return "<p>Impossible de récupérer les données de la NASA.</p>";

    $html = "";
    
    

    if ($data['media_type'] === 'image') {
        $html .= '<div class="image-container"><img src="' . htmlspecialchars($data['url']) . '" width="400" alt="APOD"></div>';
    } elseif ($data['media_type'] === 'video') {
        $html .= "<iframe width=\"560\" height=\"315\" src=\"" . htmlspecialchars($data['url']) . "\" frameborder=\"0\" allowfullscreen></iframe>";
    }

    $html .= "<p>" . nl2br(htmlspecialchars($data['explanation'])) . "</p>";

    return $html;
}


function get_geoplugin_html(string $ip): string {
    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=$ip");
    if (!$xml) return "<p>Impossible de récupérer les données de GeoPlugin.</p>";

    $html = "<ul>";
    $html .= "<li><strong>IP :</strong> " . htmlspecialchars($ip) . "</li>";
    $html .= "<li><strong>Ville :</strong> " . htmlspecialchars((string)($xml->geoplugin_city ?? 'N/A')) . "</li>";
    $html .= "<li><strong>Région :</strong> " . htmlspecialchars((string)($xml->geoplugin_region ?? 'N/A')) . "</li>";
    $html .= "<li><strong>Pays :</strong> " . htmlspecialchars((string)($xml->geoplugin_countryName ?? 'N/A')) . "</li>";
    $html .= "<li><strong>Continent :</strong> " . htmlspecialchars((string)($xml->geoplugin_continentName ?? 'N/A')) . "</li>";
    $html .= "</ul>";

    return $html;
}

function get_whatismyip_html(string $ip, string $key): string {
    $url = "https://api.whatismyip.com/ip-address-lookup.php?key=$key&input=$ip";
    $response = @file_get_contents($url);

    if (!$response) {
        return "<p>Impossible de contacter l’API WhatIsMyIP.</p>";
    }

    $lines = preg_split("/\r\n|\n|\r/", trim($response)); // Gère tous les formats de retour
    $data = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        $parts = explode(':', $line, 2);
        if (count($parts) == 2) {
            $keyName = strtolower(trim($parts[0])); // en minuscules par sécurité
            $value = trim($parts[1]);
            $data[$keyName] = $value;
        }
    }

    $html = "<ul>";
    $html .= "<li><strong>IP :</strong> " . htmlspecialchars($data['ip'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Ville :</strong> " . htmlspecialchars($data['city'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Région :</strong> " . htmlspecialchars($data['region'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Pays :</strong> " . htmlspecialchars($data['country'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Code postal :</strong> " . htmlspecialchars($data['postalcode'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Fournisseur :</strong> " . htmlspecialchars($data['isp'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Latitude :</strong> " . htmlspecialchars($data['latitude'] ?? 'N/A') . "</li>";
    $html .= "<li><strong>Longitude :</strong> " . htmlspecialchars($data['longitude'] ?? 'N/A') . "</li>";
    $html .= "</ul>";

    return $html;
}

function getTheme(): string {
    if (!isset($_COOKIE['theme'])) {
        return 'day'; // Thème par défaut
    }

    return ($_COOKIE['theme'] === 'night_style') ? 'night' : 'day';
}



function getIcon($basename) {
    $theme = getTheme();
    return "/images/{$basename}-{$theme}.png";
}

function compter_visites(string $fichier = './data/compteur.txt'): int {
    if (!file_exists($fichier)) {
        file_put_contents($fichier, 0);
    }

    $visites = (int) file_get_contents($fichier);
    $visites++;
    file_put_contents($fichier, $visites);

    return $visites;
}






function callWeatherAPI($endpoint, $query) {
    if (!$query) return null; 

    $base = "http://api.weatherapi.com/v1/";
    $key = "10534f5a5b1748fcbb0150313250104";
    $url = $base . $endpoint . "?key={$key}&q=" . urlencode($query) . "&lang=fr";
    if ($endpoint === "forecast.json") {
        $url .= "&days=7";
    }

    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}


function searchCity($ville, $departement = null, $region = null) {
    $result = callMeteoConceptAPI("location/cities", ['search' => $ville]);

    if (!isset($result['cities'])) return null;

    // 1. Filtrer par nom de département (prioritaire)
    if ($departement) {
        foreach ($result['cities'] as $city) {
            if (
                isset($city['depname']) &&
                strtolower(trim($city['depname'])) === strtolower(trim($departement))
            ) {
                return $city;
            }
        }
    }

    // 2. Sinon, filtrer par nom de région
    if ($region) {
        foreach ($result['cities'] as $city) {
            if (
                isset($city['regname']) &&
                strtolower(trim($city['regname'])) === strtolower(trim($region))
            ) {
                return $city;
            }
        }
    }

    // 3. Sinon, première ville trouvée
    return $result['cities'][0] ?? null;
}





function getWeatherForCity($insee) {
    $result = callMeteoConceptAPI("forecast/daily", ['insee' => $insee]);
    return $result['forecast'][0] ?? null;
}

function getTodayWeatherData($ville) {
    $data = callWeatherAPI("current.json", $ville);
    if (!$data) return null;

    return [
        'ville' => $data['location']['name'],
        'cp' => $data['location']['tz_id'], // Pas de CP direct, fallback
        'condition' => $data['current']['condition']['text'],
        'tmin' => $data['current']['temp_c'],
        'tmax' => $data['current']['temp_c'],
        'vent' => $data['current']['wind_kph']
    ];
}

function getNextHoursForecast($ville, $jour = 0) {
    $data = callWeatherAPI("forecast.json", $ville);
    if (!$data || !isset($data['forecast']['forecastday'][$jour]['hour'])) return null;

    $hours = $data['forecast']['forecastday'][$jour]['hour'];
    $moments = [8 => 'matin', 12 => 'midi', 18 => 'soir'];

    $result = [
        'ville' => $data['location']['name'],
        'cp' => $data['location']['tz_id'],
        'conditions' => []
    ];

    foreach ($moments as $hour => $moment) {
        if (!isset($hours[$hour])) continue;
        $f = $hours[$hour];
        $result['conditions'][$moment] = [
            'condition' => $f['condition']['text'],
            't' => $f['temp_c'],
            'vent' => $f['wind_kph']
        ];
    }

    $imageLabel = $result['conditions']['midi']['condition']
        ?? $result['conditions']['matin']['condition']
        ?? '';
    $result['image'] = getWeatherImage($imageLabel);

    return $result;
}


function getWeatherLabel($code) {
    $labels = [
        0 => 'Ensoleillé',
        1 => 'Peu nuageux',
        2 => 'Ciel voilé',
        3 => 'Nuageux',
        4 => 'Très nuageux',
        5 => 'Couvert',
        6 => 'Brouillard',
        10 => 'Pluie faible',
        11 => 'Pluie modérée',
        12 => 'Pluie forte'
        // Ajoute plus si besoin
    ];
    return $labels[$code] ?? "Inconnu";
}


function getWeatherImage($label) {
    $label = strtolower($label);
    if (str_contains($label, 'pluie')) return 'pluie.png';
    if (str_contains($label, 'nuage')) return 'nuage.png';
    if (str_contains($label, 'soleil') || str_contains($label, 'ensoleillé') || str_contains($label, 'dégagé')) return 'soleil.png';
    return 'inconnu.png';
}

function getDayDetails($ville) {
    $data = callWeatherAPI("forecast.json", $ville);
    if (!$data || !isset($data['forecast']['forecastday'][0]['day'])) return null;

    $day = $data['forecast']['forecastday'][0]['day'];
    return [
        'date' => $data['forecast']['forecastday'][0]['date'],
        'weather' => $day['condition']['text'],
        'tmin' => $day['mintemp_c'],
        'tmax' => $day['maxtemp_c'],
        'precipitation' => $day['totalprecip_mm'],
        'wind' => $day['maxwind_kph'],
        'gust' => $day['maxwind_kph']
    ];
}

function getClientIP() {
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function getCityFromIP($ip) {
    $url = "http://www.geoplugin.net/json.gp?ip=" . $ip;
    $data = json_decode(file_get_contents($url), true);

    return [
        'ville' => $data['geoplugin_city'] ?? null,
        'departement' => $data['geoplugin_region'] ?? null,       // ex : "Val-d'Oise"
        'region' => $data['geoplugin_regionName'] ?? null          // ex : "Île-de-France"
    ];
}

function getCityAndCPFromIP($ip) {
    $url = "http://www.geoplugin.net/json.gp?ip=" . $ip;
    $data = json_decode(file_get_contents($url), true);

    return [
        'ville' => $data['geoplugin_city'] ?? null,
        'cp' => $data['geoplugin_postCode'] ?? null
    ];
}


function getCityFromMeteoConceptXML($ville, $cpRecherche = null) {
    $token = '9f8ef9fa70069cdabcbe7deb066c70341eeaa2faba0068144f12cd4ff8dc5f02';
    $url = "https://api.meteo-concept.com/api/location/cities?token=$token&search=" . urlencode($ville);
    $xml = @simplexml_load_file($url);

    if (!$xml || !isset($xml->cities->item)) {
        echo "<!-- XML non récupéré depuis l'API -->";
        return null;
    }

    // 1. Si code postal fourni, essaie de le retrouver
    if ($cpRecherche) {
        foreach ($xml->cities->item as $item) {
            if ((string)$item->cp === $cpRecherche) {
                return [
                    'insee' => (string)$item->insee,
                    'cp' => (string)$item->cp,
                    'name' => (string)$item->name
                ];
            }
        }
    }

    // 2. Sinon, cherche la ville du Val-d'Oise (95130)
    foreach ($xml->cities->item as $item) {
        if ((string)$item->cp === '95130') {
            return [
                'insee' => (string)$item->insee,
                'cp' => (string)$item->cp,
                'name' => (string)$item->name
            ];
        }
    }

    // 3. Dernier recours : première ville du résultat
    $item = $xml->cities->item[0];
    return [
        'insee' => (string)$item->insee,
        'cp' => (string)$item->cp,
        'name' => (string)$item->name
    ];
}

function chargerRegionsEtDepartements($fichier_regions, $fichier_departements) {
    $codes_regions = [];

    // Vérifier si le fichier des régions existe
    if (!file_exists($fichier_regions)) {
        echo "Erreur : fichier $fichier_regions introuvable.<br>";
        return [];
    }

    // Ouvrir et lire les régions
    $r = fopen($fichier_regions, "r");
    fgetcsv($r); // sauter l'entête
    while ($ligne = fgetcsv($r)) {
        $codes_regions[$ligne[0]] = $ligne[5]; // REG => LIBELLE
    }
    fclose($r);

    $resultat = [];

    // Vérifier si le fichier des départements existe
    if (!file_exists($fichier_departements)) {
        echo "Erreur : fichier $fichier_departements introuvable.<br>";
        return [];
    }

    // Ouvrir et lire les départements
    $d = fopen($fichier_departements, "r");
    fgetcsv($d); // sauter l'entête
    while ($ligne = fgetcsv($d)) {
        $code_reg = $ligne[1];
        $code_dep = $ligne[0];
        $nom_dep  = $ligne[5];

        if (isset($codes_regions[$code_reg])) {
            $nom_reg = $codes_regions[$code_reg];
            $resultat[$nom_reg][] = [
                'numero' => $code_dep,
                'nom' => $nom_dep
            ];
        }
    }
    fclose($d);

    return $resultat;
}

function getNextDaysForecast($ville) {
    $data = callWeatherAPI("forecast.json", $ville);
    if (!$data || !isset($data['forecast']['forecastday'])) return [];

    $result = [];

    foreach ($data['forecast']['forecastday'] as $day) {
        $date = DateTime::createFromFormat('Y-m-d', $day['date']);
        $jours = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
        $dayIndex = (int) $date->format('w');
        $dayLabel = $jours[$dayIndex] . ' ' . $date->format('d');
        $result[] = [
            'date' => $day['date'],
           
            'day' => ucfirst($dayLabel),
            'icon' => $day['day']['condition']['icon'],
            'tmin' => round($day['day']['mintemp_c']),
            'tmax' => round($day['day']['maxtemp_c']),
            'wind' => round($day['day']['maxwind_kph']),
            'gust' => round($day['day']['maxwind_kph']),
        ];
    }

    return $result;
}

function getPlageWeatherData($ville) {
    $data = callWeatherAPI("forecast.json", $ville);

    if (!$data || !isset($data['forecast']['forecastday'][0]['day'])) return null;

    $day = $data['forecast']['forecastday'][0]['day'];

    return [
        'condition' => $day['condition']['text'],
        'icone' => getWeatherImage($day['condition']['text']),
        'temp_air' => $day['avgtemp_c'],
        'temp_eau' => estimateWaterTemp($day['avgtemp_c']),
        'vent' => $day['maxwind_kph'],
        'uv' => $day['uv'],
        'maree' => rand(0, 1) ? 'Haute' : 'Basse' // simulation simple
    ];
}

function getMarineZoneData($zone) {
    $zones = [
        'manche' => 'Granville',
        'atlantique' => 'La Rochelle',
        'mediterranee' => 'Nice'
    ];

    if (!isset($zones[$zone])) return null;

    $ville = $zones[$zone];
    $data = callWeatherAPI("forecast.json", $ville);

    if (!$data || !isset($data['forecast']['forecastday'][0]['day'])) return null;

    $day = $data['forecast']['forecastday'][0]['day'];

    return [
        'zone' => ucfirst($zone),
        'ville_ref' => $ville,
        'temp_eau' => estimateWaterTemp($day['avgtemp_c']), // estimation par ta fonction déjà existante
        'vent' => $day['maxwind_kph'] . " km/h",
        'maree' => rand(0, 1) ? 'Haute' : 'Basse' // simulation pour l’instant, à remplacer si tu as une API marée
    ];
}

function getMareeData(string $ville): ?array {
    $stations = [
        'Granville' => 'granville',
        'La Rochelle' => 'la-rochelle-pallice',
        'Nice' => 'nice',
        'Biarritz' => 'biarritz',
        'Brest' => 'brest',
        'Marseille' => 'marseille'
    ];

    if (!isset($stations[$ville])) return null;

    $station = $stations[$ville];
    $url = "https://www.marees.info/api/$station";

    $response = @file_get_contents($url);
    if (!$response) return null;

    $data = json_decode($response, true);
    if (!isset($data['marees'])) return null;

    $marees = array_slice($data['marees'], 0, 2);

    $result = [];
    foreach ($marees as $maree) {
        $result[] = [
            'type' => ucfirst($maree['type']),
            'heure' => substr($maree['heure'], 11, 5),
            'coef' => $maree['coef'] ?? null
        ];
    }

    return $result;
}

function getSnowDataForMassif(string $massif): array {
    $stations = getTopSkiStationsByMassif($massif);
    $results = [];

    foreach ($stations as $station) {
        $lat = $station['lat'];
        $lon = $station['lon'];
        $name = $station['name'];

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&daily=snowfall_sum&timezone=auto";
        $response = @file_get_contents($url);
        if (!$response) continue;

        $data = json_decode($response, true);
        if (!isset($data['daily']['time']) || !isset($data['daily']['snowfall_sum'])) continue;

        $entries = [];
        foreach ($data['daily']['time'] as $i => $date) {
            $entries[] = [
                'date' => $date,
                'snow_cm' => $data['daily']['snowfall_sum'][$i]
            ];
        }

        $results[] = [
            'station' => $name,
            'data' => $entries
        ];
    }

    return $results;
}


function getTopSkiStationsByMassif(string $massif): array {
    $stations = [
        'alpes' => [
            ['name' => 'Tignes', 'lat' => 45.4691, 'lon' => 6.9063],
            ['name' => 'Val Thorens', 'lat' => 45.2974, 'lon' => 6.5796],
            ['name' => 'Alpe d’Huez', 'lat' => 45.0918, 'lon' => 6.0680],
            ['name' => 'Les Deux Alpes', 'lat' => 45.0076, 'lon' => 6.1200],
            ['name' => 'Chamonix', 'lat' => 45.9237, 'lon' => 6.8694]
        ],
        'pyrenees' => [
            ['name' => 'Saint-Lary', 'lat' => 42.8161, 'lon' => 0.3297],
            ['name' => 'Cauterets', 'lat' => 42.8873, 'lon' => -0.1167],
            ['name' => 'Font-Romeu', 'lat' => 42.5044, 'lon' => 2.0362],
            ['name' => 'Ax 3 Domaines', 'lat' => 42.7200, 'lon' => 1.8200],
            ['name' => 'Gourette', 'lat' => 42.9562, 'lon' => -0.3384]
        ],
        'vosges' => [
            ['name' => 'La Bresse', 'lat' => 48.0020, 'lon' => 6.9112],
            ['name' => 'Gérardmer', 'lat' => 48.0724, 'lon' => 6.8763],
            ['name' => 'Ventron', 'lat' => 47.9439, 'lon' => 6.8784],
            ['name' => 'Le Markstein', 'lat' => 47.9427, 'lon' => 7.0383],
            ['name' => 'Ballon d’Alsace', 'lat' => 47.8308, 'lon' => 6.8533]
        ],
        'jura' => [
            ['name' => 'Les Rousses', 'lat' => 46.4839, 'lon' => 6.0650],
            ['name' => 'Métabief', 'lat' => 46.8000, 'lon' => 6.3500],
            ['name' => 'Monts Jura', 'lat' => 46.2963, 'lon' => 5.9551],
            ['name' => 'La Pesse', 'lat' => 46.2932, 'lon' => 5.8497],
            ['name' => 'Bellefontaine', 'lat' => 46.5500, 'lon' => 6.0833]
        ],
        'massif-central' => [
            ['name' => 'Super-Besse', 'lat' => 45.5100, 'lon' => 2.9333],
            ['name' => 'Le Lioran', 'lat' => 45.0491, 'lon' => 2.7556],
            ['name' => 'Mont-Dore', 'lat' => 45.5753, 'lon' => 2.8090],
            ['name' => 'Chastreix', 'lat' => 45.5189, 'lon' => 2.7356],
            ['name' => 'Prat de Bouc', 'lat' => 45.0356, 'lon' => 2.7425]
        ],
        'corse' => [
            ['name' => 'Val d’Ese', 'lat' => 42.0000, 'lon' => 9.1000],
            ['name' => 'Ghisoni', 'lat' => 42.1000, 'lon' => 9.2000],
            ['name' => 'Vergio', 'lat' => 42.2833, 'lon' => 8.9333],
            ['name' => 'Asco', 'lat' => 42.5000, 'lon' => 9.0000],
            ['name' => 'Haut Asco', 'lat' => 42.4270, 'lon' => 9.0400]
        ]
    ];

    return $stations[$massif] ?? [];
}

function getSnowDataForStation(string $stationName, float $lat, float $lon): array {
    $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&daily=snowfall_sum&timezone=auto";
    $response = @file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    if (!isset($data['daily']['time']) || !isset($data['daily']['snowfall_sum'])) return [];

    $entries = [];
    foreach ($data['daily']['time'] as $i => $date) {
        $entries[] = [
            'date' => $date,
            'snow_cm' => $data['daily']['snowfall_sum'][$i]
        ];
    }

    return [[
        'station' => $stationName,
        'data' => $entries
    ]];
}

function getMassifMapCenter(string $massif): array {
    $massifCenters = [
        'alpes' => ['lat' => 45.5, 'lon' => 6.5, 'zoom' => 8],
        'pyrenees' => ['lat' => 42.8, 'lon' => 0.3, 'zoom' => 8],
        'vosges' => ['lat' => 48.0, 'lon' => 6.9, 'zoom' => 8],
        'jura' => ['lat' => 46.5, 'lon' => 6.0, 'zoom' => 9],
        'massif-central' => ['lat' => 45.3, 'lon' => 2.8, 'zoom' => 8],
        'corse' => ['lat' => 42.2, 'lon' => 9.1, 'zoom' => 8]
    ];

    return $massifCenters[$massif] ?? ['lat' => 46.5, 'lon' => 2.5, 'zoom' => 6]; 
}

function displayRandomPhotoFigure(string $dossier = './images/massif') {
    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!is_dir($dossier)) {
        echo "<p>Dossier introuvable : $dossier</p>";
        return;
    }

    $fichiers = array_filter(scandir($dossier), function($fichier) use ($dossier, $extensions_autorisees) {
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
        return is_file($dossier . $fichier) && in_array($extension, $extensions_autorisees);
    });

    if (!empty($fichiers)) {
        $image = $fichiers[array_rand($fichiers)];
        $chemin = $dossier . $image;
        echo '<figure>';
        echo '<img src="' . htmlspecialchars($chemin) . '" alt="Image aléatoire">';
        echo '<figcaption>' . htmlspecialchars($image) . '</figcaption>';
        echo '</figure>';
    } else {
        echo "<p>Aucune image disponible dans le dossier <strong>$dossier</strong>.</p>";
    }
}

?>
