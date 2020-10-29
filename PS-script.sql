/* Drop existing tables to prevent errors */
DROP TABLE IF EXISTS ordereditems;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS admin;

/* Create Tables */
CREATE TABLE IF NOT EXISTS inventory (
    productID	INT AUTO_INCREMENT,
	quantity	INT,

	PRIMARY KEY (productID)
);


CREATE TABLE IF NOT EXISTS customer (
    customerID  INT AUTO_INCREMENT,
    name        VARCHAR(35),
    email       VARCHAR(50),
    address     VARCHAR(50),
    ccnum       VARCHAR(30),
    ccexp       VARCHAR(20),

    PRIMARY KEY (customerID)
);


CREATE TABLE IF NOT EXISTS orders (
	ordersID	INT AUTO_INCREMENT,
	custID		INT,
	status		CHAR(1),
	totalweight	FLOAT(7,2),
	addfees		FLOAT(7,2),
	totalprice	FLOAT(7,2),
	finalprice	FLOAT(7,2),
    date		DATE,
	
	PRIMARY KEY (ordersID),
	FOREIGN KEY (custID) REFERENCES customer(customerID)
);


CREATE TABLE IF NOT EXISTS ordereditems (
	orderID		INT,
	quantity	INT,
	productID	INT,

	PRIMARY KEY (orderID, productID),
	FOREIGN KEY (orderID) REFERENCES orders(ordersID),
    FOREIGN KEY (productID) REFERENCES inventory(productID)
);


CREATE TABLE IF NOT EXISTS admin (
	bracket		FLOAT(7,2),
	charge		FLOAT(7,2),
    
	PRIMARY KEY (bracket)
);


/* Display Tables */
DESCRIBE inventory;

DESCRIBE orders;

DESCRIBE customer;

DESCRIBE ordereditems;

DESCRIBE admin;