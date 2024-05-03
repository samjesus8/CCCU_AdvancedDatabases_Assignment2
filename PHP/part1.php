<?php
// Database connection details
$host = 'bvk6p7stycgeooolszfe-mysql.services.clever-cloud.com';
$dbname = 'bvk6p7stycgeooolszfe';
$username = 'utknvlfaehqhnhvj';
$password = 'xyIHcLRKWLDnPTEg7oRF';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully<br>";

    // Function to execute SQL queries and display results
    function executeQuery($conn, $query) {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Display results
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }

    // Query 1: Select all movies with their average scores
    $query1 = "
        SELECT m.title, AVG(s.score) AS avg_score
        FROM Movie m
        LEFT JOIN Score_movie s ON m.movieId = s.movieId
        GROUP BY m.title
        ORDER BY avg_score DESC;
    ";
    echo "<h2>Query 1: Average Scores of Movies</h2>";
    executeQuery($conn, $query1);

    // Query 2: Select top 3 actors who have acted in the most movies
    $query2 = "
        SELECT a.name, a.surname, COUNT(r.movieId) AS movie_count
        FROM Artist a
        JOIN Role r ON a.artistId = r.actorId
        GROUP BY a.name, a.surname
        ORDER BY movie_count DESC
        LIMIT 3;
    ";
    echo "<h2>Query 2: Top Actors by Movie Count</h2>";
    executeQuery($conn, $query2);

    //Create a view to display movies and their genres
    $createViewQuery = "
        CREATE OR REPLACE VIEW Movie_Genre_View AS
        SELECT m.title, m.genre
        FROM Movie m;
    ";
    $stmt = $conn->prepare($createViewQuery);
    $stmt->execute();

    // Query 3: Select all movies and their genres from the created view
    $query3 = "
        SELECT * FROM Movie_Genre_View;
    ";
    echo "<h2>Query 3: Movies and Their Genres</h2>";
    executeQuery($conn, $query3);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>