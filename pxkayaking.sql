CREATE DATABASE IF NOT EXISTS kayaking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kayaking;

CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    trip_date DATE NOT NULL,
    time_start VARCHAR(20) NOT NULL,
    time_end VARCHAR(20) NOT NULL,
    message TEXT,
    experience_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('pxkayaking', '1234')
    ON DUPLICATE KEY UPDATE password='1234';

-- Optional: keep a canonical list of experiences to use in the site.
CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    tag VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL
);

INSERT INTO experiences (id, title, tag, description) VALUES
    (1, 'Sunrise Creek Drift', '90 mins', 'Soft light over the Canaguinim creek, quiet waters, and bird calls — perfect for first-timers and photo lovers.'),
    (2, 'Calm Backwater Trail', '1.5 hours', 'Glide across Canaguinim’s shaded backwaters and learn how this estuary thrives away from the open sea.'),
    (3, 'Twilight Glow Paddle', '75 mins', 'Chase West Coast sunsets and pastel skies into dusk, ending with chai by the village jetty.'),
    (4, 'Sea Kayaking (weather dependent)', '2 hours', 'Sea sessions when the coast is calm — we confirm the launch by phone or WhatsApp before you head out.')
ON DUPLICATE KEY UPDATE title=VALUES(title), tag=VALUES(tag), description=VALUES(description);
