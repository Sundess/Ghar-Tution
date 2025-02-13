-- Create the database
CREATE DATABASE IF NOT EXISTS ghartution;
USE ghartution;

-- Create the "users" table
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('male', 'female', 'other') DEFAULT 'other',
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL,  -- Expected format: "+977 XXXXXXXXXX"
    password VARCHAR(255) NOT NULL,
    role ENUM('parent', 'tutor', 'admin') DEFAULT 'parent',
    cv VARCHAR(255),                     -- File path to CV PDF (for tutors)
    tutor_location ENUM('Kathmandu', 'Bhaktapur', 'Lalitpur'),
    profile_picture VARCHAR(255),        -- File path to profile picture
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the "tuition_posts" table
CREATE TABLE tuition_posts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,            -- Foreign key to users.id (the parent who creates the post)
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    tuition_type ENUM('online', 'offline') NOT NULL,
    gender_preferred ENUM('any', 'male', 'female') DEFAULT 'any',
    grade VARCHAR(20) NOT NULL,          -- e.g. "Grade 1-5", "Grade 5-8", "Grade 9-10", "+2", "Bachelors"
    subjects VARCHAR(255) NOT NULL,        -- Comma separated list of subjects
    class_start_time VARCHAR(50) NOT NULL, -- e.g., "5 PM"
    class_duration INT NOT NULL,           -- Duration in hours (minimum 1)
    no_of_students INT NOT NULL,
    category VARCHAR(50) NOT NULL,         -- e.g., "For 3 months", "For exam only", "For whole year"
    price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the "applications" table
CREATE TABLE applications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    post_id INT(11) NOT NULL,             -- Foreign key to tuition_posts.id
    tutor_id INT(11) NOT NULL,            -- Foreign key to users.id (the tutor applying)
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (post_id) REFERENCES tuition_posts(id),
    FOREIGN KEY (tutor_id) REFERENCES users(id)
);
