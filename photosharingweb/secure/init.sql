-- IMPORTANT! If you change this file, you will need to manually
-- delete site.sqlite in order to regenerate the database from this file!

BEGIN TRANSACTION;

-- Users Table
CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	username TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL
);

-- Users seed data
INSERT INTO users (id, username, password) VALUES (1, 'kathy', '$2y$10$noccb/zFQXI2GWjSPBATg.dMkCEn/o5ywhbjAAd1twkjSvqQURnou'); -- password: hello
INSERT INTO users (id, username, password) VALUES (2, 'urael', '$2y$10$7B.YG0928CjG2iqmKRm5DePoPqmuqimK1gDYBouxL2lIcICS3nDRe'); -- password: bye
INSERT INTO users (id, username, password) VALUES (3, 'helen', '$2y$10$x2xQP6QXX95EqfIyZQRbUOoiFPRA7QZTO070641hQ1vSwkUT73eju'); -- password: vacation

-- sessions table
CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	session TEXT NOT NULL UNIQUE
);

-- documents aka photos table
CREATE TABLE documents (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	file_name TEXT NOT NULL,
	file_ext TEXT NOT NULL,
	description TEXT
);

-- documents seed data
-- Source of all seed photos: (original: Kathy Zhang)
-- all photos taken during my vacation over spring break a week ago.

INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (1, 1, 'beachmermaid.jpg', 'jpg', 'Sand mermaid <3');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (2, 2, 'shooting.jpg', 'jpg', 'Fun times in Disneys Buzz Lightyear ride');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (3, 1, 'selfie1.jpg', 'jpg', 'Bad selfie gud times');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (4, 1, 'atlantis.jpg', 'jpg', 'Atlantis waterpark in Bahamas');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (5, 1, 'disneygroup.jpg', 'jpg', 'Group picture at Disneyworld!');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (6, 3, 'cruiseship.jpg', 'jpg', 'Sunset on Norwegian Escape cruise ship');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (7, 2, 'bahamsgroup.jpg', 'jpg', 'We tried to do sorority squats');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (8, 3, 'fish.jpg', 'jpg', 'Fish at the Atlantis Aquarium in Bahamas');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (9, 2, 'selfie2.jpg', 'jpg', 'Selfie at Atlantis');
INSERT INTO documents (id, user_id, file_name, file_ext, description) VALUES (10, 1, 'sandcastle.jpg', 'jpg', 'It looks more like a big mound of sand');

CREATE TABLE tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	name TEXT NOT NULL UNIQUE
);

-- tags seed data
INSERT INTO tags (id, name) VALUES (1, 'bahamas');
INSERT INTO tags (id, name) VALUES (2, 'cruise');
INSERT INTO tags (id, name) VALUES (3, 'spring break');
INSERT INTO tags (id, name) VALUES (4, 'selfie');
INSERT INTO tags (id, name) VALUES (5, 'disneyworld');
INSERT INTO tags (id, name) VALUES (6, 'beach');

-- joint table for documents/photos and tags
CREATE TABLE doc_tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	doc_id INTEGER NOT NULL,
	tag_id INTEGER NOT NULL
);

-- doc_tags seed data
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (1, 1,1);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (2, 1,6);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (3, 1,3);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (4, 2,5);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (5, 3,4);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (6, 3,3);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (7, 3,1);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (8, 4,1);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (9, 5,5);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (10, 6,2);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (11, 7,1);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (12, 9,3);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (13, 10,3);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (14, 10,5);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (15, 10,1);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (16, 10,2);
INSERT INTO doc_tags (id, doc_id, tag_id) VALUES (17, 10,4);




COMMIT;
