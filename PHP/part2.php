<!DOCTYPE html>
<html>
<head>
    <title>PHP MongoDB Queries</title>
</head>
<body>

<h2>MongoDB Query Results:</h2>

<?php
require 'vendor/autoload.php'; // Include the Composer autoloader

try{

// Create a MongoDB client and access the database
$client = new MongoDB\Client("mongodb+srv://RyanJudd96:password1234@movies.sz0lxn6.mongodb.net/");
$database = $client->Movies;

//************ QUERY 1 *************** */

echo "<p>************ QUERY 1 ***************</p>";
echo "<p>Finds all the entries for the Movies collection.</p>";

// Access the collection and perform and display query results
$query1Collection = $database->movie;
$query1 = $query1Collection->find();

foreach ($query1 as $document) {
    echo "<p>Title: " . $document['title'] . "</p>";
    echo "<p>Year: " . $document['year'] . "</p>";
    echo "<p>Genre: " . $document['genre'] . "</p>";
    echo "<p>Summary: " . $document['summary'] . "</p>";
    echo "<p>Poducer ID: " . $document['producerId'] . "</p>";
    echo "<p>Country: " . $document['countryCode'] . "</p>";
    echo "<hr>";
}

echo "<p>******************************</p>";

//************ QUERY 2 ************* */

echo "<p>************ QUERY 2 ***************</p>";
echo "<p>Displays a count for each type of genre.</p>";

$query2Collection = $database->movie;
$query2Pipeline = [['$group' => ['_id' => '$genre', 'count' => ['$sum' => 1]]]];

// Execute the aggregation pipeline
$query2Result = $query2Collection->aggregate($query2Pipeline);

// Display the results
foreach ($query2Result as $document) {
    echo "Genre: " . $document['_id'] . ", Count: " . $document['count'] . "<br>";
}

echo "<p>******************************</p>";

//************ QUERY 3 ************* */

echo "<p>************ QUERY 3 ***************</p>";
echo "<p>Displays a count for how many role each ID has.</p>";

$query3Collection = $database->roles;
$query3Pipeline = [
    [
        '$group' => [
            '_id' => '$actorId',
            'count' => ['$sum' => 1]
        ]
    ],
    [
        '$project' => [
            'actorId' => '$_id',
            'roleCount' => '$count',
            '_id' => 0
        ]
    ]
];

$query3Result = $query3Collection->aggregate($query3Pipeline);

foreach ($query3Result as $document) {
    echo "Actor ID: " . $document['actorId'] . ", Role Count: " . $document['roleCount'] . "<br>";
}

}

catch(PDOException $e) 
{
    echo "Connection failed: " . $e->getMessage();
}

?>
</body>
</html>