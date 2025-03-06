<?php
// Nepal Weather Forecast Application
// This application generates a simulated 30-day weather forecast for selected locations in Nepal

// Define Nepal locations with realistic weather patterns
$locations = [
    'Kathmandu' => ['min_temp' => 5, 'max_temp' => 30, 'rain_probability' => 0.35, 'elevation' => 1400],
    'Pokhara' => ['min_temp' => 7, 'max_temp' => 33, 'rain_probability' => 0.40, 'elevation' => 827],
    'Chitwan' => ['min_temp' => 10, 'max_temp' => 35, 'rain_probability' => 0.30, 'elevation' => 415],
    'Bhaktapur' => ['min_temp' => 5, 'max_temp' => 29, 'rain_probability' => 0.35, 'elevation' => 1401],
    'Lukla' => ['min_temp' => -5, 'max_temp' => 15, 'rain_probability' => 0.45, 'elevation' => 2860],
    'Lumbini' => ['min_temp' => 10, 'max_temp' => 35, 'rain_probability' => 0.25, 'elevation' => 150],
    'Namche Bazaar' => ['min_temp' => -10, 'max_temp' => 12, 'rain_probability' => 0.50, 'elevation' => 3440],
    'Dharan' => ['min_temp' => 9, 'max_temp' => 32, 'rain_probability' => 0.30, 'elevation' => 349],
    'Janakpur' => ['min_temp' => 10, 'max_temp' => 35, 'rain_probability' => 0.25, 'elevation' => 74],
    'Mustang' => ['min_temp' => -5, 'max_temp' => 20, 'rain_probability' => 0.15, 'elevation' => 3840],
];

// Current season calculation based on current date
function getCurrentSeason() {
    $month = date('n');
    if ($month >= 3 && $month <= 5) {
        return 'spring'; // March to May
    } elseif ($month >= 6 && $month <= 8) {
        return 'summer'; // June to August (monsoon)
    } elseif ($month >= 9 && $month <= 11) {
        return 'autumn'; // September to November
    } else {
        return 'winter'; // December to February
    }
}

// Function to generate a weather forecast for a specific location in Nepal
function generateForecast($location_data, $location_name) {
    $forecast = [];
    $season = getCurrentSeason();
    
    // Adjust temperature and rain probability based on season
    $seasonal_adjustments = [
        'winter' => ['temp_adj' => -5, 'rain_adj' => -0.15],
        'spring' => ['temp_adj' => 0, 'rain_adj' => 0],
        'summer' => ['temp_adj' => 3, 'rain_adj' => 0.3], // Monsoon season
        'autumn' => ['temp_adj' => -2, 'rain_adj' => -0.1]
    ];
    
    $temp_adjustment = $seasonal_adjustments[$season]['temp_adj'];
    $rain_adjustment = $seasonal_adjustments[$season]['rain_adj'];
    
    // Generate 30 days of weather data
    for ($i = 0; $i < 30; $i++) {
        // Calculate date
        $date = date('Y-m-d', strtotime("+$i days"));
        
        // Adjust temperature range based on season
        $min_temp = $location_data['min_temp'] + $temp_adjustment;
        $max_temp = $location_data['max_temp'] + $temp_adjustment;
        
        // Generate random temperature within location range
        $temp_high = rand($min_temp, $max_temp);
        
        // Low temp is proportional to elevation - higher elevations have bigger temp swings
        $temp_swing = 5;
        if ($location_data['elevation'] > 2000) {
            $temp_swing = 10;
        } elseif ($location_data['elevation'] > 1000) {
            $temp_swing = 8;
        }
        $temp_low = $temp_high - rand($temp_swing, $temp_swing + 5);
        
        // Adjust rain probability based on season
        $rain_prob = $location_data['rain_probability'] + $rain_adjustment;
        $rain_prob = max(0, min(1, $rain_prob)); // Keep between 0 and 1
        
        // Determine weather condition based on rain probability and elevation
        $weather_types = ['Sunny', 'Partly Cloudy', 'Cloudy', 'Light Rain', 'Heavy Rain', 'Thunderstorms'];
        
        // Add snow for high elevations in winter
        if ($location_data['elevation'] > 2500 && ($season == 'winter' || $season == 'autumn')) {
            $weather_types[] = 'Light Snow';
            $weather_types[] = 'Heavy Snow';
        }
        
        // Random weather with bias based on rain probability
        $rand = mt_rand(1, 100) / 100;
        if ($rand < $rain_prob) {
            // Higher chance of rain-related weather
            if ($location_data['elevation'] > 2500 && ($season == 'winter' || $season == 'autumn') && $temp_high < 5) {
                $weather = $weather_types[array_rand(array_slice($weather_types, 6, 2))]; // Snow types
            } else {
                $weather = $weather_types[array_rand(array_slice($weather_types, 3, 3))]; // Rain types
            }
        } else {
            // Higher chance of clear weather
            $weather = $weather_types[array_rand(array_slice($weather_types, 0, 3))];
        }
        
        // Create forecast entry
        $forecast[] = [
            'date' => $date,
            'day' => date('l', strtotime($date)),
            'temp_high' => $temp_high,
            'temp_low' => $temp_low,
            'weather' => $weather,
            'humidity' => rand(30, 90)
        ];
    }
    
    return $forecast;
}

// Get selected location from form
$selected_location = isset($_POST['location']) ? $_POST['location'] : 'Kathmandu';

// Generate forecast for selected location
$forecast_data = [];
if (array_key_exists($selected_location, $locations)) {
    $forecast_data = generateForecast($locations[$selected_location], $selected_location);
}

// Weather condition to icon mapping
function getWeatherIcon($condition) {
    switch ($condition) {
        case 'Sunny':
            return '☀️';
        case 'Partly Cloudy':
            return '⛅';
        case 'Cloudy':
            return '☁️';
        case 'Light Rain':
            return '🌦️';
        case 'Heavy Rain':
            return '🌧️';
        case 'Thunderstorms':
            return '⛈️';
        case 'Light Snow':
            return '🌨️';
        case 'Heavy Snow':
            return '❄️';
        default:
            return '❓';
    }
}

// Function to convert Celsius to Fahrenheit
function celsiusToFahrenheit($celsius) {
    return round(($celsius * 9/5) + 32);
}

// Temperature unit selection
$temp_unit = isset($_POST['temp_unit']) ? $_POST['temp_unit'] : 'celsius';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepal 30-Day Weather Forecast</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f8ff;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        .nepal-flag {
            display: block;
            margin: 0 auto 20px;
            text-align: center;
            font-size: 32px;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            margin-right: 10px;
            font-weight: bold;
        }
        select, button {
            padding: 8px 15px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }
        button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .high-temp {
            color: #e74c3c;
            font-weight: bold;
        }
        .low-temp {
            color: #3498db;
        }
        .weather-icon {
            font-size: 24px;
        }
        .date-cell {
            white-space: nowrap;
        }
        .forecast-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .location-info {
            font-weight: bold;
            color: #2c3e50;
        }
        .season-info {
            color: #7f8c8d;
            font-style: italic;
        }
        footer {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Nepal 30-Day Weather Forecast</h1>
    <div class="nepal-flag">🇳🇵</div>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="location">Select Location:</label>
            <select name="location" id="location">
                <?php foreach ($locations as $location => $data): ?>
                    <option value="<?= htmlspecialchars($location) ?>" <?= $selected_location == $location ? 'selected' : '' ?>>
                        <?= htmlspecialchars($location) ?> (<?= htmlspecialchars($data['elevation']) ?>m)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Temperature Unit:</label>
            <input type="radio" id="celsius" name="temp_unit" value="celsius" <?= $temp_unit == 'celsius' ? 'checked' : '' ?>>
            <label for="celsius">°C</label>
            <input type="radio" id="fahrenheit" name="temp_unit" value="fahrenheit" <?= $temp_unit == 'fahrenheit' ? 'checked' : '' ?>>
            <label for="fahrenheit">°F</label>
        </div>
        
        <button type="submit">Get Forecast</button>
    </form>
    
    <?php if (!empty($forecast_data)): ?>
        <div class="forecast-info">
            <p class="location-info">
                Showing 30-day forecast for <?= htmlspecialchars($selected_location) ?> 
                (Elevation: <?= htmlspecialchars($locations[$selected_location]['elevation']) ?>m)
            </p>
            <p class="season-info">
                Current Season: <?= ucfirst(getCurrentSeason()) ?> | 
                Starting from: <?= date('F j, Y') ?>
            </p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Weather</th>
                    <th>High</th>
                    <th>Low</th>
                    <th>Humidity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forecast_data as $day): ?>
                    <tr>
                        <td class="date-cell"><?= htmlspecialchars($day['date']) ?></td>
                        <td><?= htmlspecialchars($day['day']) ?></td>
                        <td>
                            <span class="weather-icon"><?= getWeatherIcon($day['weather']) ?></span>
                            <?= htmlspecialchars($day['weather']) ?>
                        </td>
                        <td class="high-temp">
                            <?php if ($temp_unit == 'celsius'): ?>
                                <?= htmlspecialchars($day['temp_high']) ?>°C
                            <?php else: ?>
                                <?= htmlspecialchars(celsiusToFahrenheit($day['temp_high'])) ?>°F
                            <?php endif; ?>
                        </td>
                        <td class="low-temp">
                            <?php if ($temp_unit == 'celsius'): ?>
                                <?= htmlspecialchars($day['temp_low']) ?>°C
                            <?php else: ?>
                                <?= htmlspecialchars(celsiusToFahrenheit($day['temp_low'])) ?>°F
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($day['humidity']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <footer>
            <p>This is a simulated weather forecast for educational purposes.</p>
            <p>Weather patterns are based on typical seasonal conditions in Nepal.</p>
        </footer>
    <?php endif; ?>
</body>
</html>