DROP TABLE IF EXISTS `Mensagens`;
DROP TABLE IF EXISTS `Chat`;
DROP TABLE IF EXISTS `Reservas`;
DROP TABLE IF EXISTS `Viagens`;
DROP TABLE IF EXISTS `Local`;
DROP TABLE IF EXISTS `Passageiros`;
DROP TABLE IF EXISTS `Condutores`;
DROP TABLE IF EXISTS `Carros`;
DROP TABLE IF EXISTS `Utilizadores`;
DROP TABLE IF EXISTS `ValoresReferencia`;


CREATE TABLE `Utilizadores` (
  `email` VARCHAR(255) PRIMARY KEY NOT NULL,
  `NIF` VARCHAR(9),
  `nome` VARCHAR(255) NOT NULL,
  `telemovel` VARCHAR(9) NOT NULL,
  `pass` VARCHAR(255) NOT NULL,
  `ft_perfil` VARCHAR(255)  
) ENGINE=InnoDB;


CREATE TABLE `Condutores` (
  `id` VARCHAR(255) PRIMARY KEY,
  `aval` FLOAT(3,1),
  FOREIGN KEY (`id`) REFERENCES `Utilizadores`(`email`) ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE `Carros`(
  `matricula` VARCHAR(8) PRIMARY KEY,
  `id_dono` VARCHAR(255),
  `marca` VARCHAR(255),
  `modelo` VARCHAR(255),
  `cor` VARCHAR(50),
  `combustivel` VARCHAR(50),
  `ft_carro` VARCHAR(255),
  FOREIGN KEY (`id_dono`) REFERENCES `Utilizadores`(`email`) ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE `Passageiros` (
  `id` VARCHAR(255) PRIMARY KEY,
  FOREIGN KEY (`id`) REFERENCES `Utilizadores`(`email`) ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE `Local` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(255),
  `rua` VARCHAR(255),
  `nmr` VARCHAR(20),
  `localidade` VARCHAR(255),
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL
);


CREATE TABLE `Viagens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `condutor_id` VARCHAR(255),
  `carro_id` VARCHAR(8),
  `origem` INT,
  `destino` INT,
  `lugares` INT,
  `data_hora` DATETIME NOT NULL,
  `preco` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`condutor_id`) REFERENCES `Condutores`(`id`),
  FOREIGN KEY (`carro_id`) REFERENCES `Carros`(`matricula`),
  FOREIGN KEY (`origem`) REFERENCES `Local`(`id`),
  FOREIGN KEY (`destino`) REFERENCES `Local`(`id`)
);


CREATE TABLE `Reservas` (
  `idRes` INT AUTO_INCREMENT PRIMARY KEY,
  `idPass` VARCHAR(255),
  `viagem_id` INT,
  `avaliacao` INT,
  `pontoRecolha` INT,
  `preco` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`pontoRecolha`) REFERENCES `Local`(`id`),
  FOREIGN KEY (`idPass`) REFERENCES `Passageiros`(`id`),
  FOREIGN KEY (`viagem_id`) REFERENCES `Viagens`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `ValoresReferencia` (
  `tipo` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `Mensagens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender_id` VARCHAR(9),
  `reserva_id` INT,
  `conteudo` TEXT,
  FOREIGN KEY (`reserva_id`) REFERENCES `Reservas`(`idRes`),
  FOREIGN KEY (`sender_id`) REFERENCES `Utilizadores`(`email`)
);