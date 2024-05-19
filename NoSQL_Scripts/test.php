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

    // Insert data into Movies collection
    $moviesData = readCSV('movies.csv');
    $moviesCollection = $db->movies;

    foreach ($moviesData as $row) {
        $movie = [
            'title' => $row['title'],
            'year' => intval($row['year']),
            'genre' => $row['genre'],
            'summary' => $row['summary'],
            'producerId' => new MongoDB\BSON\ObjectId($row['producerId']),
            'country' => [
                'code' => $row['country_code'],
                'name' => $row['country_name'],
                'language' => $row['country_language']
            ],
            'roles' => [], // Will be populated later
            'scores' => [] // Will be populated later
        ];
        $moviesCollection->insertOne($movie);
    }

    // Insert data into Users collection
    $usersData = readCSV('InternetUserTable.csv');
    $usersCollection = $db->users;

    foreach ($usersData as $row) {
        $user = [
            '_id' => $row['email'],
            'surname' => $row['surname'],
            'name' => $row['name'],
            'region' => $row['region']
        ];
        $usersCollection->insertOne($user);
    }

    // Insert data into Roles collection and update Movies collection
    $rolesData = readCSV('RoleTable.csv');

    foreach ($rolesData as $row) {
        $movieId = new MongoDB\BSON\ObjectId($row['movieId']);
        $actorId = new MongoDB\BSON\ObjectId($row['actorId']);
        $roleName = $row['roleName'];

        $artist = $db->artists->findOne(['artistId' => $actorId]);

        if ($artist) {
            $role = [
                'actorId' => $actorId,
                'roleName' => $roleName,
                'artist' => $artist
            ];

            $moviesCollection->updateOne(
                ['_id' => $movieId],
                ['$push' => ['roles' => $role]]
            );
        } else {
            echo "Warning: Artist with ID $actorId not found.<br>";
        }
    }

    // Insert data into Scores collection and update Movies collection
    $scoresData = readCSV('ScoreMovieTable.csv');

    foreach ($scoresData as $row) {
        $email = $row['userEmail'];
        $movieId = new MongoDB\BSON\ObjectId($row['movieId']);
        $score = intval($row['score']);

        $scoreEntry = [
            'userEmail' => $email,
            'score' => $score
        ];

        $moviesCollection->updateOne(
            ['_id' => $movieId],
            ['$push' => ['scores' => $scoreEntry]]
        );
    }

    echo "Data inserted successfully!";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>