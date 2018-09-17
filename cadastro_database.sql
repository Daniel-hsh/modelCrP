CREATE DATABASE `CRUDprocess` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE CRUDprocess;
CREATE TABLE Cliente
(
	Id INTEGER AUTO_INCREMENT,
	Nome VARCHAR(60) NOT NULL,
	Email VARCHAR(150) NOT NULL,
    UF VARCHAR(30),
	Cidade VARCHAR(100),
    CONSTRAINT pkCliente PRIMARY KEY(Id)
)CHARACTER SET utf8 COLLATE utf8_general_ci;