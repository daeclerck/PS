/* Drop existing tables to prevent errors */
DROP TABLE IF EXISTS ordereditems;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS admin;

/* Create Tables */
CREATE TABLE IF NOT EXISTS inventory (
    productID	INT AUTO_INCREMENT NOT NULL,
    quantity	INT NOT NULL,

	PRIMARY KEY (productID)
);


CREATE TABLE IF NOT EXISTS customer (
    customerID  INT AUTO_INCREMENT NOT NULL,
    name        VARCHAR(35) NOT NULL,
    email       VARCHAR(50) NOT NULL,
    address     VARCHAR(50) NOT NULL,
    ccnum       VARCHAR(30) NOT NULL,
    ccexp       VARCHAR(20) NOT NULL,

    PRIMARY KEY (customerID)
);


CREATE TABLE IF NOT EXISTS orders (
	ordersID	INT AUTO_INCREMENT NOT NULL,
	custID		INT NOT NULL,
	status		CHAR(1) NOT NULL,
	totalweight	FLOAT(7,2) NOT NULL,
	addfees		FLOAT(7,2) NOT NULL,
	totalprice	FLOAT(7,2) NOT NULL,
	finalprice	FLOAT(7,2) NOT NULL,
        date		DATE NOT NULL,
	
	PRIMARY KEY (ordersID),
	FOREIGN KEY (custID) REFERENCES customer(customerID)
);


CREATE TABLE IF NOT EXISTS ordereditems (
	orderID		INT NOT NULL,
	quantity	INT NOT NULL,
	productID	INT NOT NULL,

	PRIMARY KEY (orderID, productID),
	FOREIGN KEY (orderID) REFERENCES orders(ordersID),
    FOREIGN KEY (productID) REFERENCES inventory(productID)
);


CREATE TABLE IF NOT EXISTS admin (
	bracket		FLOAT(7,2) NOT NULL,
	charge		FLOAT(7,2) NOT NULL,
    
	PRIMARY KEY (bracket)
);


/* Display Tables */
DESCRIBE inventory;

DESCRIBE orders;

DESCRIBE customer;

DESCRIBE ordereditems;

DESCRIBE admin;
