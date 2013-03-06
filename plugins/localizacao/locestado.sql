-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `correios`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `locestado`
--

CREATE TABLE IF NOT EXISTS `locestado` (
  `cod` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `ibge` int(2) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `locestado`
--

INSERT INTO `locestado` (`cod`, `nome`, `uf`, `ibge`) VALUES
(1, 'Acre', 'AC', 12),
(2, 'Alagoas', 'AL', 27),
(3, 'Amazonas', 'AM', 13),
(4, 'Amapá', 'AP', 16),
(5, 'Bahia', 'BA', 29),
(6, 'Ceará', 'CE', 23),
(7, 'Brasília', 'DF', 53),
(8, 'Espírito Santo', 'ES', 32),
(9, 'Goiás', 'GO', 52),
(10, 'Maranhão', 'MA', 21),
(11, 'Minas Gerais', 'MG', 31),
(12, 'Mato Grosso do Sul', 'MS', 50),
(13, 'Mato Grosso', 'MT', 51),
(14, 'Pará', 'PA', 15),
(15, 'Paraíba', 'PB', 25),
(16, 'Pernambuco', 'PE', 26),
(17, 'Piauí', 'PI', 22),
(18, 'Paraná', 'PR', 41),
(19, 'Rio de Janeiro', 'RJ', 33),
(20, 'Rio Grande do Norte', 'RN', 24),
(21, 'Rondônia', 'RO', 11),
(22, 'Roraima', 'RR', 14),
(23, 'Rio Grande do Sul', 'RS', 43),
(24, 'Santa Catarina', 'SC', 42),
(25, 'Sergipe', 'SE', 28),
(26, 'São Paulo', 'SP', 35),
(27, 'Tocantins', 'TO', 17);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
