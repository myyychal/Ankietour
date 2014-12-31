-- DROP TABLE users;
-- DROP TABLE productstypes;
DROP TABLE products;
DROP TABLE questionnaires;
DROP TABLE comparisons;

-- CREATE TABLE users (
--   id INTEGER PRIMARY KEY,
--   login INTEGER NOT NULL,
--   password VARCHAR NOT NULL,
--   privilege INTEGER NOT NULL
-- );

CREATE TABLE productstypes (
  id INTEGER PRIMARY KEY,
  name VARCHAR NOT NULL,
  description VARCHAR
);

CREATE TABLE products (
  id INTEGER PRIMARY KEY,
  typeid INTEGER NOT NULL,
  name VARCHAR NOT NULL,
  description VARCHAR,
  rate INTEGER,
  imageurl VARCHAR,
  FOREIGN KEY (typeid) REFERENCES productstypes(id) ON DELETE CASCADE
);

CREATE TABLE questionnaires (
  id INTEGER PRIMARY KEY,
  personid INTEGER,
  name VARCHAR,
  FOREIGN KEY (personid) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE comparisons (
  firstproductid INTEGER NOT NULL,
  secondproductid INTEGER NOT NULL,
  questionnaireid INTEGER NOT NULL,
  rate INTEGER NOT NULL,
  FOREIGN KEY (firstproductid) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (secondproductid) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (questionnaireid) REFERENCES questionnaires(id) ON DELETE CASCADE,
  PRIMARY KEY (firstproductid,secondproductid,questionnaireid)
);
