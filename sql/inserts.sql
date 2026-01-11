

INSERT INTO `Utilizadores`(`email`, `NIF`, `nome`, `telemovel`, `pass`, `ft_perfil`) VALUES
('passageiro1@example.com', '111111111', 'Passageiro 1', '911111111', 'password1', 'assets/img/perfil/1.jpg'),
('passageiro2@example.com', '222222222', 'Passageiro 2', '922222222', 'password2', 'assets/img/perfil/1.jpg'),
('passageiro3@example.com', '333333333', 'Passageiro 3', '933333333', 'password3', 'assets/img/perfil/1.jpg'),
('passageiro4@example.com', '444444444', 'Passageiro 4', '944444444', 'password4', 'assets/img/perfil/1.jpg'),
('passageiro5@example.com', '555555555', 'Passageiro 5', '955555555', 'password5', 'assets/img/perfil/1.jpg'),
('passageiro6@example.com', '666666666', 'Passageiro 6', '966666666', 'password6', 'assets/img/perfil/1.jpg'),
('passageiro7@example.com', '777777777', 'Passageiro 7', '977777777', 'password7', 'assets/img/perfil/1.jpg');

INSERT INTO `Passageiros` (`id`) VALUES
('passageiro1@example.com'),
('passageiro2@example.com'),
('passageiro3@example.com'),
('passageiro4@example.com'),
('passageiro5@example.com'),
('passageiro6@example.com'),
('passageiro7@example.com');

INSERT INTO `Utilizadores` (`email`, `NIF`, `nome`, `telemovel`, `pass`, `ft_perfil`) VALUES
('condutor1@example.com', '123456789', 'Condutor 1', '912345678', 'password1', 'assets/img/perfil/1.jpg'),
('condutor2@example.com', '987654321', 'Condutor 2', '923456789', 'password2', 'assets/img/perfil/1.jpg'),
('condutor3@example.com', '456789123', 'Condutor 3', '934567890', 'password3', 'assets/img/perfil/1.jpg'),
('condutor4@example.com', '789123456', 'Condutor 4', '945678901', 'password4', 'assets/img/perfil/1.jpg'),
('condutor5@example.com', '321654987', 'Condutor 5', '956789012', 'password5', 'assets/img/perfil/1.jpg'),
('condutor6@example.com', '654987321', 'Condutor 6', '967890123', 'password6', 'assets/img/perfil/1.jpg'),
('condutor7@example.com', '147258369', 'Condutor 7', '978901234', 'password7', 'assets/img/perfil/1.jpg');

INSERT INTO `Condutores` (`id`, `aval`) VALUES
('condutor1@example.com', 5),
('condutor2@example.com', 4),
('condutor3@example.com', 3),
('condutor4@example.com', 2),
('condutor5@example.com', 4),
('condutor6@example.com', 3),
('condutor7@example.com', 5);

INSERT INTO `Local` (`nome`, `rua`, `nmr`, `localidade`, `latitude`, `longitude`) VALUES
('Parque Central', 'Rua das Flores', '123', 'Lisboa', 38.716931, -9.139989),
('Praça do Comércio', 'Avenida Ribeira', '45', 'Estoril', 38.707750, -9.136592),
('Torre de Belém', 'Avenida Brasília', '1', 'Lisboa', 38.691584, -9.215872),
('Oceanário', 'Esplanada Dom Carlos I', 's/n', 'Arruda', 38.763611, -9.093333),
('Estádio da Luz', 'Avenida Eusébio', '1904', 'Lisboa', 38.752758, -9.184720);

INSERT INTO `Viagens` (`condutor_id`, `origem`, `destino`, `lugares`, `data_hora`, `preco`) VALUES
('condutor1@example.com', 1, 2, 4, '2025-03-25 08:30:00', 15.50),
('condutor2@example.com', 2, 3, 3, '2025-03-25 09:00:00', 12.00),
('condutor3@example.com', 3, 4, 5, '2025-03-25 10:15:00', 18.75),
('condutor4@example.com', 4, 5, 2, '2025-03-25 11:45:00', 20.00),
('condutor5@example.com', 5, 1, 6, '2025-03-25 13:00:00', 25.00),
('condutor6@example.com', 1, 3, 4, '2025-03-25 14:30:00', 10.50),
('condutor2@example.com', 2, 4, 3, '2025-03-25 15:45:00', 14.25),
('condutor3@example.com', 3, 5, 5, '2025-03-25 16:30:00', 22.00),
('condutor3@example.com', 4, 1, 2, '2025-03-25 17:15:00', 19.50),
('condutor2@example.com', 5, 2, 4, '2025-03-25 18:00:00', 16.75),
('condutor1@example.com', 1, 4, 3, '2025-03-25 19:00:00', 13.50),
('condutor4@example.com', 2, 5, 5, '2025-03-25 20:15:00', 21.00);



INSERT INTO `Viagens` (`condutor_id`, `origem`, `destino`, `lugares`, `data_hora`, `preco`) VALUES
('condutor1@example.com', 1, 3, 4, '2025-03-26 08:30:00', 15.00),
('condutor2@example.com', 2, 5, 3, '2025-03-26 09:15:00', 12.50),
('condutor3@example.com', 3, 1, 5, '2025-03-26 10:45:00', 18.00),
('condutor4@example.com', 4, 2, 2, '2025-03-26 11:30:00', 20.50),
('condutor5@example.com', 5, 4, 6, '2025-03-26 13:15:00', 24.75),
('condutor6@example.com', 1, 5, 4, '2025-03-26 14:45:00', 11.00),
('condutor2@example.com', 2, 1, 3, '2025-03-26 15:30:00', 14.00),
('condutor3@example.com', 3, 2, 5, '2025-03-26 16:15:00', 21.50),
('condutor3@example.com', 4, 3, 2, '2025-03-26 17:45:00', 19.00),
('condutor2@example.com', 5, 4, 4, '2025-03-26 18:30:00', 17.25),
('condutor1@example.com', 1, 5, 3, '2025-03-26 19:15:00', 13.00),
('condutor4@example.com', 2, 3, 5, '2025-03-26 20:30:00', 20.75);





INSERT INTO `Viagens` (`condutor_id`, `origem`, `destino`, `lugares`, `data_hora`, `preco`) VALUES
('condutor1@example.com', 1, 2, 4, '2025-03-27 08:30:00', 16.00),
('condutor2@example.com', 2, 3, 3, '2025-03-27 09:00:00', 12.75),
('condutor3@example.com', 3, 4, 5, '2025-03-27 10:15:00', 19.25),
('condutor4@example.com', 4, 5, 2, '2025-03-27 11:45:00', 21.00),
('condutor5@example.com', 5, 1, 6, '2025-03-27 13:00:00', 26.00),
('condutor6@example.com', 1, 3, 4, '2025-03-27 14:30:00', 11.50),
('condutor2@example.com', 2, 4, 3, '2025-03-27 15:45:00', 15.00),
('condutor3@example.com', 3, 5, 5, '2025-03-27 16:30:00', 23.00),
('condutor3@example.com', 4, 1, 2, '2025-03-27 17:15:00', 20.00),
('condutor2@example.com', 5, 2, 4, '2025-03-27 18:00:00', 17.50),
('condutor1@example.com', 1, 4, 3, '2025-03-27 19:00:00', 14.00),
('condutor4@example.com', 2, 5, 5, '2025-03-27 20:15:00', 22.00);




INSERT INTO `Reservas` (`idPass`, `viagem_id`, `avaliacao`) VALUES
('passageiro1@example.com', 1, 0),
('passageiro2@example.com', 1, 1),

('passageiro6@example.com', 2, 5),

('passageiro1@example.com', 3, 1),

('passageiro6@example.com', 4, 0),
('passageiro7@example.com', 4, 1),

('passageiro4@example.com', 5, 5),
('passageiro5@example.com', 5, 0),
('passageiro6@example.com', 5, 1),

('passageiro7@example.com', 6, 2),
('passageiro3@example.com', 6, 5),

('passageiro4@example.com', 7, 0),
('passageiro5@example.com', 7, 1),
('passageiro6@example.com', 7, 2),

('passageiro4@example.com', 8, 1),

('passageiro5@example.com', 9, 2),
('passageiro6@example.com', 9, 3),

('passageiro2@example.com', 10, 0),
('passageiro3@example.com', 10, 1);


INSERT INTO `Reservas` (`idPass`, `viagem_id`, `avaliacao`) VALUES
('passageiro1@example.com', 11, 0),
('passageiro2@example.com', 11, 1),
('passageiro3@example.com', 11, 2),

('passageiro5@example.com', 12, 4),
('passageiro7@example.com', 12, 0),
('passageiro3@example.com', 12, 2),

('passageiro1@example.com', 13, 1),
('passageiro2@example.com', 13, 2),

('passageiro6@example.com', 14, 0),
('passageiro4@example.com', 14, 5),
('passageiro6@example.com', 14, 1),

('passageiro7@example.com', 16, 2),
('passageiro2@example.com', 16, 4),

('passageiro4@example.com', 17, 0),
('passageiro6@example.com', 17, 2),

('passageiro7@example.com', 18, 3),
('passageiro1@example.com', 18, 4),

('passageiro5@example.com', 19, 2),

('passageiro3@example.com', 20, 1);



INSERT INTO `ValoresReferencia` (`tipo`, `valor`) VALUES
('Gasolina', 1.75),
('Diesel', 1.50),
('Eletrico', 0.20),
('Hibrido', 1.00);
