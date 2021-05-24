-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `advertisers`;
CREATE TABLE `advertisers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adv_id` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_to_add` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `api_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hit',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advertisers_entity_id_foreign` (`entity_id`),
  CONSTRAINT `advertisers_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `advertisers` (`id`, `name`, `adv_id`, `alias`, `api_key`, `url`, `url_to_add`, `api_type`, `status`, `created_at`, `updated_at`, `entity_id`) VALUES
(1,	'idrive',	1,	'account 1',	'682db938af9869ffa8c0098ecdedb0022e7238b1f63536a14207d80bbc03850f',	'http://api.rembrow.com/pubapi.php',	'/{affiliate_id}/{source}/{aff_sub}',	'hitpath',	'0',	'2018-09-10 11:49:27',	'2018-09-10 11:49:27',	1),
(2,	'madrivo',	2,	'account1',	'e74ed50ada7d93ff5192139189bdae537a3991e50e08a5cf82f338c67f9fcb58',	'http://api.midenity.com/pubapi.php',	'/{affiliate_id}/{source}/{aff_sub}',	'hitpath',	'',	'2018-09-11 00:00:00',	'2018-09-11 00:00:00',	1),
(3,	'spheredigital',	3,	'account 1',	'2d052b6fcb079f0dade5217d1a21b117948f9bf167964df518572fdb2fa22f93',	'http://api.spheredigitalnetworks.com/pubapi.php',	'/{affiliate_id}/{source}/{aff_sub}',	'hitpath',	'',	'2018-09-11 00:00:00',	'2018-09-11 00:00:00',	1),
(4,	'Adgenics',	4,	'account1',	'a87fffdde867ec94d1f0919368340f3d64d549880b5f37c372186346d6d52766',	'http://api.loadsmooth.com/pubapi.php',	'/{affiliate_id}/{source}/{aff_sub}',	'hitpath',	'',	'2018-09-11 00:00:00',	'2018-09-11 00:00:00',	1);

DROP TABLE IF EXISTS `cache_offers`;
CREATE TABLE `cache_offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payout` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `daysleft` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bannercount` int(11) DEFAULT NULL,
  `emailcount` int(11) DEFAULT NULL,
  `textcount` int(11) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geotargeting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_added` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `advertiser_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_offers_advertiser_id_foreign` (`advertiser_id`),
  CONSTRAINT `cache_offers_advertiser_id_foreign` FOREIGN KEY (`advertiser_id`) REFERENCES `advertisers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `creatives`;
CREATE TABLE `creatives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offer_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creatives_offer_id_foreign` (`offer_id`),
  CONSTRAINT `creatives_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `creative_images`;
CREATE TABLE `creative_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creative_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creative_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creative_images_creative_id_foreign` (`creative_id`),
  CONSTRAINT `creative_images_creative_id_foreign` FOREIGN KEY (`creative_id`) REFERENCES `creatives` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `entities`;
CREATE TABLE `entities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_entity` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `entities` (`id`, `id_entity`, `name`, `path`, `created_at`, `updated_at`) VALUES
(1,	1,	'PIM',	'pim3uyLRGnw6cxutO3p',	'2018-09-10 11:49:27',	'2018-09-10 11:49:27'),
(2,	10,	'DGX',	'dgxS9qLNIUHql0jMHSE',	'2018-09-10 11:49:27',	'2018-09-10 11:49:27'),
(3,	11,	'WD',	'wdmRVzLWPgEU1Xrydfu',	'2018-09-10 11:49:27',	'2018-09-10 11:49:27'),
(4,	12,	'EDB',	'edbWspBfn1D27nGrgZm',	'2018-09-10 11:49:27',	'2018-09-10 11:49:27');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offer_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unsubscribe_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landing_page_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categories` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_payout` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_lines` text COLLATE utf8mb4_unicode_ci,
  `subject_lines` text COLLATE utf8mb4_unicode_ci,
  `level` tinyint(4) DEFAULT NULL,
  `countries` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suppression_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `is_mobile` tinyint(4) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `amount` decimal(9,2) DEFAULT NULL,
  `has_supp` tinyint(4) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `advertiser_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `offers_advertiser_id_foreign` (`advertiser_id`),
  CONSTRAINT `offers_advertiser_id_foreign` FOREIGN KEY (`advertiser_id`) REFERENCES `advertisers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_entity_id_foreign` (`entity_id`),
  CONSTRAINT `users_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `permission`, `remember_token`, `created_at`, `updated_at`, `entity_id`) VALUES
(1,	'admin',	'admin@admin.com',	'$2y$10$4iQWsDEK0RSNDNrnH8ye4egYn1fKPAJb0QLE6uZcbJRu3Iy/Xmz4C',	'user',	'5awO7DO7X5jsRPvDYBcJm3lJoHruT249SAtvaZgxMcih0yWo6YXrrvQMNJSS',	'2018-09-10 11:52:33',	'2018-09-10 11:52:33',	1),
(2,	'user1',	'dgx@user.com',	'$2y$10$uVd/zIJXbU4JJ5i93zUmXeyHSG.iRT5rGMoYkax6efYO78DL6rNii',	'user',	NULL,	'2018-09-11 09:50:56',	'2018-09-11 09:50:56',	2);

-- 2018-09-19 14:56:07
