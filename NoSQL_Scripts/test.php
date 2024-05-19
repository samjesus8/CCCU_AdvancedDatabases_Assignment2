<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

use MongoDB\Client as MongoClient;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection details
$mongoHost = 'mongodb+srv://RyanJudd96:Flapjack96@movies.sz0lxn6.mongodb.net/';
$mongoDBName = 'Movies';

try {
    // Connect to MongoDB
    $client = new MongoClient($mongoHost);
    $db = $client->$mongoDBName;
    echo "Connected to MongoDB successfully<br>";

    // Load the Excel file
    $spreadsheet = IOFactory::load('Movies Data.xlsx');

    // Get data from each sheet and insert into MongoDB

    // Insert data into Movies collection
    $moviesSheet = $spreadsheet->getSheetByName('Movies');
    $moviesData = $moviesSheet->toArray(null, true, true, true);
    $moviesCollection = $db->movies;

    foreach ($moviesData as $index => $row) {
        if ($index == 1) continue; // Skip header row

        $movie = [
            'title' => $row['A'],
            'year' => intval($row['B']),
            'genre' => $row['C'],
            'summary' => $row['D'],
            'producerId' => new MongoDB\BSON\ObjectId($row['E']),
            'country' => [
                'code' => $row['F'],
                'name' => $row['G'],
                'language' => $row['H']
            ],
            'roles' => [], // Will be populated later
            'scores' => [] // Will be populated later
        ];
        $moviesCollection->insertOne($movie);
    }

    // Insert data into Users collection
    $usersSheet = $spreadsheet->getSheetByName('Internet Users');
    $usersData = $usersSheet->toArray(null, true, true, true);
    $usersCollection = $db->users;

    foreach ($usersData as $index => $row) {
        if ($index == 1) continue; // Skip header row

        $user = [
            '_id' => $row['A'],
            'surname' => $row['B'],
            'name' => $row['C'],
            'region' => $row['D']
        ];
        $usersCollection->insertOne($user);
    }

    // Insert data into Roles collection and update Movies collection
    $rolesSheet = $spreadsheet->getSheetByName('Roles');
    $rolesData = $rolesSheet->toArray(null, true, true, true);

    foreach ($rolesData as $index => $row) {
        if ($index == 1) continue; // Skip header row

        $movieId = new MongoDB\BSON\ObjectId($row['A']);
        $actorId = new MongoDB\BSON\ObjectId($row['B']);
        $roleName = $row['C'];

        $artist = $db->artists->findOne(['artistId' => $actorId]);
        $role = [
            'actorId' => $actorId,
            'roleName' => $roleName,
            'artist' => $artist
        ];

        $moviesCollection->updateOne(
            ['_id' => $movieId],
            ['$push' => ['roles' => $role]]
        );
    }

    // Insert data into Scores collection and update Movies collection
    $scoresSheet = $spreadsheet->getSheetByName('Scores');
    $scoresData = $scoresSheet->toArray(null, true, true, true);

    foreach ($scoresData as $index => $row) {
        if ($index == 1) continue; // Skip header row

        $email = $row['A'];
        $movieId = new MongoDB\BSON\ObjectId($row['B']);
        $score = intval($row['C']);

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