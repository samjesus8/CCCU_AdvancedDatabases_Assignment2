INSERT INTO Movie (movieId, title, year, genre, summary, producerId, countryCode)
VALUES
(1, 'Inception', 2010, 'Sci-Fi', 'A thief who enters the dreams of others to steal their secrets from their subconscious.', 1, 'US'),
(2, 'The Dark Knight', 2008, 'Action', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 2, 'US'),
(3, 'Interstellar', 2014, 'Sci-Fi', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity''s survival.', 1, 'US'),
(4, 'The Matrix', 1999, 'Action', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 3, 'US'),
(5, 'Fight Club', 1999, 'Drama', 'An insomniac office worker and a devil-may-care soapmaker form an underground fight club that evolves into something much, much more.', 4, 'US');

INSERT INTO Country (code, name, language)
VALUES
('US', 'United States', 'English'),
('UK', 'United Kingdom', 'English'),
('FR', 'France', 'French'),
('JP', 'Japan', 'Japanese'),
('CA', 'Canada', 'English');

INSERT INTO Artist (artistId, surname, name, DOB)
VALUES
(1, 'Nolan', 'Christopher', '1970-07-30'),
(2, 'Bale', 'Christian', '1974-01-30'),
(3, 'Wachowski', 'Lana', '1965-06-21'),
(4, 'Pitt', 'Brad', '1963-12-18'),
(5, 'DiCaprio', 'Leonardo', '1974-11-11');

INSERT INTO Role (movieId, actorId, roleName)
VALUES
(1, 5, 'Cobb'),
(1, 2, 'Ariadne'),
(1, 3, 'Arthur'),
(2, 2, 'Batman'),
(2, 3, 'Joker'),
(3, 5, 'Cooper'),
(3, 2, 'Mal'),
(4, 4, 'Neo'),
(4, 5, 'Trinity'),
(5, 4, 'Tyler Durden'),
(5, 5, 'Narrator');

INSERT INTO Internet_user (email, surname, name, region)
VALUES
('johndoe@example.com', 'Doe', 'John', 'North America'),
('janedoe@example.com', 'Doe', 'Jane', 'Europe'),
('alexsmith@example.com', 'Smith', 'Alex', 'Asia'),
('emilybrown@example.com', 'Brown', 'Emily', 'North America'),
('michaeljones@example.com', 'Jones', 'Michael', 'Europe');

INSERT INTO Score_movie (email, movieId, score)
VALUES
('johndoe@example.com', 1, 9),
('johndoe@example.com', 2, 8),
('janedoe@example.com', 1, 10),
('janedoe@example.com', 3, 9),
('alexsmith@example.com', 3, 8),
('alexsmith@example.com', 4, 9),
('emilybrown@example.com', 5, 8),
('michaeljones@example.com', 5, 7);