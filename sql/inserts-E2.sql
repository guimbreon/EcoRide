
INSERT INTO `Utilizadores` (`email`, `NIF`, `nome`, `telemovel`, `pass`, `ft_perfil`) VALUES
('Admin15@Admin.Admin', '123456789', 'Admin15', '123456789', '$2y$10$MRub54WU4adbMpC.W0TtcObXT50NiFAzrc/FryGtWacKlSMUhhjXW', 'assets/img/perfil/1.jpg'),
('Condutor15@Condutor.Condutor', '123456789', 'Condutor15', '123456789', '$2y$10$y2OspHCAsVJFjVLJNSEa0.48NhhYc85NtGyjvxs5.b6EJSmVhm/ae', 'assets/img/perfil/1.jpg'),
('Passageiro15@Passageiro.Passageiro', '123456789', 'Passageiro15', '123456789', '$2y$10$R8AN.chYg1mLgVNonzCzSuCeX4JJMzGuXWbpgt/KLNlpIkfAayKZW', 'assets/img/perfil/1.jpg');


INSERT INTO `Passageiros` (`id`) VALUES
('Passageiro15@Passageiro.Passageiro');

INSERT INTO `Condutores` (`id`, `aval`) VALUES
('Condutor15@Condutor.Condutor', 5);

INSERT INTO `Carros` (`matricula`, `id_dono`, `marca`, `modelo`, `cor`, `combustivel`, `ft_carro`) VALUES
('AA-01-AA', 'Condutor15@Condutor.Condutor', 'Toyota', 'Corolla', 'Branco', 'Gasolina', 'assets/img/ecoRide.png'),
('BB-02-BB', 'Condutor15@Condutor.Condutor', 'Honda', 'Civic', 'Preto', 'Diesel', 'assets/img/ecoRide.png'),
('CC-03-CC', 'Condutor15@Condutor.Condutor', 'Ford', 'Focus', 'Azul', 'Híbrido', 'assets/img/ecoRide.png'),
('DD-04-DD', 'Condutor15@Condutor.Condutor', 'Volkswagen', 'Golf', 'Vermelho', 'Elétrico', 'assets/img/ecoRide.png'),
('EE-05-EE', 'Condutor15@Condutor.Condutor', 'BMW', 'Serie 3', 'Cinza', 'Gasolina', 'assets/img/ecoRide.png');

INSERT INTO `Viagens` (`condutor_id`, `carro_id`, `origem`, `destino`, `lugares`, `data_hora`, `preco`) VALUES
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 2, 4, '2025-11-25 08:30:00', 15.50),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 3, 3, '2025-10-25 09:00:00', 12.00),
('Condutor15@Condutor.Condutor', 'CC-03-CC', 3, 4, 5, '2025-9-12 10:15:00', 18.75),
('Condutor15@Condutor.Condutor', 'DD-04-DD', 4, 5, 2, '2025-11-25 11:45:00', 20.00),
('Condutor15@Condutor.Condutor', 'EE-05-EE', 5, 1, 6, '2025-11-25 13:00:00', 25.00),
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 3, 4, '2025-11-25 14:30:00', 10.50),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 4, 3, '2025-11-25 15:45:00', 14.25),
('Condutor15@Condutor.Condutor', 'CC-03-CC', 3, 5, 5, '2025-11-25 16:30:00', 22.00),
('Condutor15@Condutor.Condutor', 'DD-04-DD', 4, 1, 2, '2025-11-25 17:15:00', 19.50),
('Condutor15@Condutor.Condutor', 'EE-05-EE', 5, 2, 4, '2025-11-25 18:00:00', 16.75),
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 4, 3, '2025-11-25 19:00:00', 13.50),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 5, 5, '2025-11-25 20:15:00', 21.00);

INSERT INTO `Viagens` (`condutor_id`, `carro_id`, `origem`, `destino`, `lugares`, `data_hora`, `preco`) VALUES
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 2, 4, '2025-12-01 08:00:00', 15.00),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 3, 3, '2025-12-02 09:30:00', 12.50),
('Condutor15@Condutor.Condutor', 'CC-03-CC', 3, 4, 5, '2025-12-03 10:45:00', 19.00),
('Condutor15@Condutor.Condutor', 'DD-04-DD', 4, 5, 2, '2025-12-04 11:15:00', 20.50),
('Condutor15@Condutor.Condutor', 'EE-05-EE', 5, 1, 6, '2025-12-05 13:30:00', 26.00),
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 3, 4, '2025-12-06 14:45:00', 11.00),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 4, 3, '2025-12-07 15:15:00', 14.75),
('Condutor15@Condutor.Condutor', 'CC-03-CC', 3, 5, 5, '2025-12-08 16:00:00', 22.50),
('Condutor15@Condutor.Condutor', 'DD-04-DD', 4, 1, 2, '2025-12-09 17:30:00', 20.00),
('Condutor15@Condutor.Condutor', 'EE-05-EE', 5, 2, 4, '2025-12-10 18:15:00', 17.25),
('Condutor15@Condutor.Condutor', 'AA-01-AA', 1, 4, 3, '2025-12-11 19:45:00', 14.00),
('Condutor15@Condutor.Condutor', 'BB-02-BB', 2, 5, 5, '2025-12-12 20:30:00', 21.50),
('Condutor15@Condutor.Condutor', 'CC-03-CC', 3, 1, 4, '2025-12-13 08:15:00', 18.25),
('Condutor15@Condutor.Condutor', 'DD-04-DD', 4, 2, 3, '2025-12-14 09:45:00', 16.00),
('Condutor15@Condutor.Condutor', 'EE-05-EE', 5, 3, 5, '2025-12-15 10:30:00', 23.75);


INSERT INTO `Reservas` (`idPass`, `viagem_id`, `avaliacao`, `pontoRecolha`, `preco`) VALUES
('Passageiro15@Passageiro.Passageiro', 37, 5, 1, 15.50),
('Passageiro15@Passageiro.Passageiro', 39, 4, 2, 12.00),
('Passageiro15@Passageiro.Passageiro', 41, 5, 3, 18.75),
('Passageiro15@Passageiro.Passageiro', 43, 4, 4, 20.00),
('Passageiro15@Passageiro.Passageiro', 45, 5, 5, 25.00);
