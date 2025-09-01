--
-- Table structure for table `users`
--
Drop TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstName`, `lastName`, `email`) VALUES
(1, 'admin', 'password', 'Admin', 'User', 'admin@example.com'),
(2, 'user1', 'password1', 'John', 'Doe', 'John@gmail.com'),
(3, 'user2', 'password2', 'Jane', 'Smith', 'Jane@swin.edu.au'),
(4, 'user4', 'password4', 'Alice', 'Johnson', 'Alice@yahoo.com');



-- Create the 'restaurants' table
CREATE TABLE `restaurants` (
    `restaurant_id` INT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255),
    `star_rating` FLOAT,
    `type` VARCHAR(255),
    `image` VARCHAR(255),
    `long` DECIMAL(9, 6), 
    `lat` DECIMAL(9, 6)   
    )ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert data into the 'restaurants' table
INSERT INTO `restaurants` (`restaurant_id`, `name`, `star_rating`, `type`, `image`, `long`, `lat`)
VALUES
    (1, "Pellegrini's Espresso Bar", 4.5, "Italian", "res_Id1.jpg", 144.965, -37.813),
    (2, "Chin Chin", 4.0, "Asian Fusion", "res_Id2.jpg", 144.97, -37.814),
    (3, "Cumulus Inc.", 4.5, "Modern Australian", "res_Id3.jpg", 144.964, -37.815),
    (4, "Flower Drum", 5.0, "Cantonese", "res_Id4.jpg", 144.971, -37.812),
    (5, "Tipo 00", 4.7, "Italian", "res_Id5.jpg", 144.963, -37.816),
    (6, "Attica", 5.0, "Modern Australian", "res_Id6.jpg", 145.002, -37.851),
    (7, "Supernormal", 4.5, "Asian Fusion", "res_Id7.jpg", 144.971, -37.814),
    (8, "Cutler & Co.", 4.8, "Modern Australian", "res_Id8.jpg", 144.981, -37.801),
    (9, "Maha", 4.7, "Middle Eastern", "res_Id9.jpg", 144.967, -37.814),
    (10, "Embla", 4.6, "Wine Bar", "res_Id10.jpg", 144.966, -37.811),
    (11, "Lune Croissanterie", 4.9, "French Pastry", "res_Id11.jpg", 144.975, -37.805),
    (12, "Carlton Wine Room", 4.4, "Modern European", "res_Id12.jpg", 144.966, -37.800),
    (13, "Olio Cucina", 4.7, "Italian", "res_Id13.jpg", 144.964, -37.815),
    (14, "Kisumé", 4.8, "Japanese", "res_Id14.jpg", 144.972, -37.813),
    (15, "Gimlet at Cavendish House", 4.9, "European", "res_Id15.jpg", 144.970, -37.817),
    (16, "Grossi Florentino", 4.7, "Italian", "res_Id16.jpg", 144.966, -37.812),
    (17, "Ides", 5.0, "Modern Australian", "res_Id17.jpg", 144.982, -37.840),
    (18, "Lee Ho Fook", 4.6, "Chinese", "res_Id18.jpg", 144.965, -37.810),
    (19, "Matilda 159 Domain", 4.8, "Australian", "res_Id19.jpg", 144.974, -37.830),
    (20, "Di Stasio Citta", 4.9, "Italian", "res_Id20.jpg", 144.968, -37.816);



-- Create the 'reviews' table
CREATE TABLE `reviews` (
    `review_id` INT AUTO_INCREMENT PRIMARY KEY,
    `restaurant_id` INT,
    `user_id` INT,
   `comment` TEXT,
    `rating` INT,
    `date` DATE,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(restaurant_id), -- relationship
    FOREIGN KEY (user_id) REFERENCES users(id) -- relationship
);


-- Insert data into the 'reviews' table
INSERT INTO `reviews` (`restaurant_id`, `user_id`, `comment`,`rating`, date)
VALUES
    (1, 1, 'Classic Melbourne experience, great coffee!', 5, '2025-05-01'),
    (1, 2, 'Loved the pasta. Very authentic.', 4, '2025-05-03'),
    (2, 3, 'Always busy, but worth the wait. Amazing food!', 5, '2025-04-28'),
    (4, 1, 'Truly a 5-star experience. Impeccable service and food.', 5, '2025-05-10'),
    (5, 4, 'Best pasta in town, hands down.', 5, '2025-05-12');
    