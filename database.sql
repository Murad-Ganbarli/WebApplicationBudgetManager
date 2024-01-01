-- Creates the Payments table
CREATE TABLE IF NOT EXISTS payments (
    idPayment INT PRIMARY KEY,
    paymentMethod VARCHAR(255) NOT NULL
);

-- Inserts data into the Payments table
INSERT INTO payments (idPayment, paymentMethod)
VALUES
    (1, 'Cash'),
    (2, 'Debit card'),
    (3, 'Credit card'),
    (4, 'Check'),
    (5, 'E-bank transfer');

-- Creates the Categories table
CREATE TABLE IF NOT EXISTS categories (
    categoryID INT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    accountingID INT,
    FOREIGN KEY (accountingID) REFERENCES accounting(accountingID)
);

-- Inserts data into the Categories table
INSERT INTO categories (categoryID, category, accountingID)
VALUES
    (0, 'food and drink', 1),
    (1, 'communal', 1),
    (2, 'education', 1),
    (3, 'tax', 1),
    (4, 'health', 1),
    (5, 'entertainment', 1),
    (6, 'communication', 1),
    (7, 'rent', 1),
    (8, 'transport', 1),
    (9, 'logistic', 1),
    (10, 'scholarship', 2);

-- Creates the Accounting table
CREATE TABLE IF NOT EXISTS accounting (
    accountingID INT PRIMARY KEY,
    accountType VARCHAR(255) NOT NULL,
    accountingCoefficient INT NOT NULL
);

-- Inserts data into the Accounting table
INSERT INTO accounting (accountingID, accountType, accountingCoefficient)
VALUES
    (1, 'Debit', -1),
    (2, 'Credit', 1);
