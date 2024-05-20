<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

use MongoDB\Client as MongoClient;

// Database connection details
$mongoHost = 'mongodb+srv://RyanJudd96:Flapjack96@movies.sz0lxn6.mongodb.net/';
$mongoDBName = 'Movies';

try {
    // Connect to MongoDB
    $client = new MongoClient($mongoHost);
    $db = $client->$mongoDBName;
    echo "Connected to MongoDB successfully<br>";

    // Function to read CSV file and convert to array
    function readCSV($filePath) {
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $data = [];
        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }
        fclose($file);
        return $data;
    }

    // Function to ensure a string is valid UTF-8
    function ensureUTF8($string) {
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    // Insert data into Movies collection
    $moviesData = readCSV('C:\xampp\htdocs\csv\MovieTable.csv');
    $moviesCollection = $db->movie;

    foreach ($moviesData as $row) {
        $movie = [
            'movieId' => $row['movieId'],
            'title' => $row['title'],
            'year' => intval($row['year']),
            'genre' => $row['genre'],
            'summary' => ensureUTF8($row['summary']),
            'producerId' => intval($row['producerId']),
            'countryCode' => $row['countryCode']
        ];
        $moviesCollection->insertOne($movie);
    }

    // Insert data into Users collection
    $usersData = readCSV('C:\xampp\htdocs\csv\InternetUserTable.csv');
    $usersCollection = $db->users;

    foreach ($usersData as $row) {
        $user = [
            'email' => $row['email'],
            'surname' => $row['surname'],
            'name' => $row['name'],
            'region' => $row['region']
        ];
        $usersCollection->insertOne($user);
    }

    // Insert data into Roles collection
    $rolesData = readCSV('C:\xampp\htdocs\csv\RoleTable.csv');
    $rolesCollection = $db->roles;

    foreach ($rolesData as $row) {
        $role = [
            'movieId' => $row['movieId'],
            'actorId' => $row['actorId'],
            'roleName' => ensureUTF8($row['roleName'])
        ];

        $rolesCollection->insertOne($role);
    }

    // Insert data into Scores collection
    $scoresData = readCSV('C:\xampp\htdocs\csv\ScoreMovieTable.csv');
    $scoresCollection = $db->scores;

    foreach ($scoresData as $row) {
        $score = [
            'movieId'=> $row['movieId'],
            'email'=> $row['email'],
            'score'=> $row['score'],
        ];

        $scoresCollection->insertOne($score);
    }

    // Insert data into Artist Collection
    $artistData = readCSV('C:\xampp\htdocs\csv\ArtistTable.csv');
    $artistCollection = $db->artists;

    foreach ($artistData as $row) {
        $artist = [
            'artistId'=> $row['artistId'],
            'surname'=> ensureUTF8($row['surname']),
            'name'=> ensureUTF8($row['name']),
            'DOB'=> $row['DOB']
        ];

        $artistCollection->insertOne($artist);
    }

    // Insert data into Country Collection
    $countryData = readCSV('C:\xampp\htdocs\csv\CountryTable.csv');
    $countryCollection = $db->country;

    foreach ($countryData as $row) {
        $country = [
            'code'=> $row['code'],
            'name'=> $row['name'],
            'language'=> $row['language'],
        ];

        $countryCollection->insertOne($country);
    }

    echo "Data inserted successfully!";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>