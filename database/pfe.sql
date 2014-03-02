-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 02 Mars 2014 à 09:55
-- Version du serveur: 5.5.35
-- Version de PHP: 5.5.9-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id_address` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_city` int(11) NOT NULL,
  `id_district` int(11) NOT NULL,
  `id_street_type` int(11) NOT NULL,
  `street_number` tinyint(4) NOT NULL,
  `street_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `other` text CHARACTER SET utf8 NOT NULL,
  `primary` tinyint(4) NOT NULL,
  `date_created` int(11) NOT NULL,
  `date_updated` int(11) NOT NULL,
  PRIMARY KEY (`id_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `agencies`
--

CREATE TABLE IF NOT EXISTS `agencies` (
  `id_agency` int(11) NOT NULL AUTO_INCREMENT,
  `id_agency_holding` int(11) NOT NULL,
  `id_address` int(11) NOT NULL,
  `name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `phone_office` varchar(15) CHARACTER SET utf8 NOT NULL,
  `fax` varchar(15) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `website` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id_agency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `agencies_has_posts_type`
--

CREATE TABLE IF NOT EXISTS `agencies_has_posts_type` (
  `id_agency` int(11) NOT NULL,
  `id_post_type` int(11) NOT NULL,
  UNIQUE KEY `id_agency` (`id_agency`,`id_post_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `agencies_holdings`
--

CREATE TABLE IF NOT EXISTS `agencies_holdings` (
  `id_agency_holding` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_agency_holding`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `id_alert` int(11) NOT NULL AUTO_INCREMENT,
  `for_type` enum('user','collab') CHARACTER SET utf8 NOT NULL,
  `for_id` int(11) NOT NULL,
  `type` enum('new','price') CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_last_send` datetime NOT NULL,
  PRIMARY KEY (`id_alert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `collabs`
--

CREATE TABLE IF NOT EXISTS `collabs` (
  `id_collab` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_collab`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `collabs_has_users`
--

CREATE TABLE IF NOT EXISTS `collabs_has_users` (
  `id_collab` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  UNIQUE KEY `id_collab` (`id_collab`,`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id_favorite` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_action` datetime NOT NULL,
  PRIMARY KEY (`id_favorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `id_gallery` int(11) NOT NULL AUTO_INCREMENT,
  `media_count` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id_gallery`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geo_cities`
--

CREATE TABLE IF NOT EXISTS `geo_cities` (
  `id_city` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `id_region` int(11) NOT NULL,
  `latitude` varchar(45) CHARACTER SET utf8 NOT NULL,
  `longitude` varchar(45) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `zipcode` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geo_cities_districts`
--

CREATE TABLE IF NOT EXISTS `geo_cities_districts` (
  `id_district` int(11) NOT NULL AUTO_INCREMENT,
  `id_city` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `zipcode` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geo_countries`
--

CREATE TABLE IF NOT EXISTS `geo_countries` (
  `id_country` int(11) NOT NULL AUTO_INCREMENT,
  `id_capital` int(11) NOT NULL,
  `continent` enum('Europe') CHARACTER SET utf8 NOT NULL,
  `iso2` char(2) CHARACTER SET utf8 NOT NULL,
  `name_short` varchar(155) CHARACTER SET utf8 NOT NULL,
  `name_full` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone_prefix` varchar(6) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geo_countries_regions`
--

CREATE TABLE IF NOT EXISTS `geo_countries_regions` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `iso2` char(2) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geo_streets_type`
--

CREATE TABLE IF NOT EXISTS `geo_streets_type` (
  `id_street_type` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(75) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_street_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id_media` int(11) NOT NULL AUTO_INCREMENT,
  `id_gallery` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id_media`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_details` int(11) NOT NULL,
  `id_gallery` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_address` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `date_closed` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts_details`
--

CREATE TABLE IF NOT EXISTS `posts_details` (
  `id_post_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_post_type` int(11) NOT NULL,
  `id_post_property_type` int(11) NOT NULL,
  `id_heating_type` int(11) NOT NULL,
  `id_kitchen_type` int(11) NOT NULL,
  `gaz` tinyint(1) NOT NULL,
  `perf_energy` tinyint(4) DEFAULT NULL,
  `perf_climat` tinyint(4) DEFAULT NULL,
  `orientation` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `surface_living` tinyint(4) DEFAULT NULL,
  `surface_ground` tinyint(4) DEFAULT NULL,
  `story` tinyint(2) NOT NULL,
  `attic` tinyint(1) NOT NULL,
  `lift` tinyint(1) NOT NULL,
  `caretaker` tinyint(1) NOT NULL,
  `intercom` tinyint(1) NOT NULL,
  `digicode` tinyint(1) NOT NULL,
  `cellar` tinyint(1) NOT NULL,
  `garage` tinyint(1) NOT NULL,
  `fireplace` tinyint(1) NOT NULL,
  `vis_a_vis` tinyint(1) NOT NULL,
  `pet` tinyint(1) NOT NULL,
  `furnished` tinyint(1) NOT NULL,
  `balcony` tinyint(1) NOT NULL,
  `pool` tinyint(1) NOT NULL,
  `yard` tinyint(1) NOT NULL,
  `renting_date_start` datetime DEFAULT NULL,
  `renting_date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id_post_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts_property_type`
--

CREATE TABLE IF NOT EXISTS `posts_property_type` (
  `id_post_property_type` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_post_property_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts_type`
--

CREATE TABLE IF NOT EXISTS `posts_type` (
  `id_post_type` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_post_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `type` enum('reset_password') CHARACTER SET utf8 NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_used` datetime NOT NULL,
  UNIQUE KEY `type` (`type`,`token`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(64) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(75) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(75) CHARACTER SET utf8 NOT NULL,
  `phone_mobile` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `phone_office` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
