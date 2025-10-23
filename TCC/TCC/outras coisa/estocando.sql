-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/10/2025 às 16:30
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `estocando`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastrar`
--

CREATE TABLE `cadastrar` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cadastrar`
--

INSERT INTO `cadastrar` (`id`, `nome`, `email`, `cnpj`, `senha`) VALUES
(4, 'fatal', 'meubombomzinho@gmail.com', '12345678901234', '$2y$10$m/T0Au7Jk/10A/xlXmR0WeDwSu9hbx2BN8hbgzsDM.zXxJUsOwRCC'),
(7, 'Heitor', 'heitor.p.tavares@aluno.senai.br', '12345678901235', '$2y$10$uJPBjTkvNz0U/2V8lYaxi.wvan0OzHCgBbMew0c2dETKjf7qpB5M6'),
(8, 'Iraco', 'heitor.p.tavares@aluno.senai.br', '09876543212345', '$2y$10$4NYOK4EMlpwhC7SeQwAP9ehqnRx3vsVDAZi27zChHEHoZtSu.Je.m'),
(9, 'Maia', 'maiahelena@gmail.com', '00000000000000', '$2y$10$4gFYLIPjGS8puzCH.IG9Mu8iFRmheeaqGHSnL1rdL/PxwMwuh9vh6');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil`
--

INSERT INTO `perfil` (`id`, `id_usuario`, `telefone`, `empresa`, `cargo`, `departamento`, `localizacao`, `bio`, `foto_perfil`) VALUES
(1, 8, '098765432', 'asdfghj', 'asdfg', 'rh', 'sdfgh', 'oi?', NULL),
(2, 9, '1234567', 'empresa do malvado doofenshmirtz SA.', 'Gerente ', 'rh', 'Rua das Nações n°2562', 'eu te odeio perry o ornitorrinco!!!', 'uploads/68ee55dead4c2.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produtos` int(11) NOT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `estoque_minimo` int(11) NOT NULL DEFAULT 0,
  `unidade_medida` varchar(50) DEFAULT NULL,
  `imagem_path` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cadastrar`
--
ALTER TABLE `cadastrar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produtos`),
  ADD KEY `nome_produto` (`nome_produto`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `preco` (`preco`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastrar`
--
ALTER TABLE `cadastrar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produtos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `cadastrar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
