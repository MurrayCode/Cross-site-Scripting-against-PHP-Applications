DROP TABLE IF EXISTS `gk_contact_categories`;
--
-- Table structure for table `gk_contact_categories`
--

DROP TABLE IF EXISTS `gk_contact_categories`;
CREATE TABLE IF NOT EXISTS `gk_contact_categories` (
  `cid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` int(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `summary` text COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `virtual_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gk_contact_items`
--

DROP TABLE IF EXISTS `gk_contact_items`;
CREATE TABLE IF NOT EXISTS `gk_contact_items` (
 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'untitled',
  `branch` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `virtual_filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'untitled',
  `street` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `province` varchar(64) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal` varchar(32) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `country` varchar(64) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `tollfree` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `display_email` tinyint(1) NOT NULL DEFAULT '0',
  `additional_info` text COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `additional_options` text COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gk_contact_items`
--
INSERT INTO `gk_contact_categories` (`cid`, `status`, `parent_id`, `title`, `summary`, `virtual_filename`, `sort_order`) VALUES
(1, 1, 0, 'Default Category', '', 'default', 0);

INSERT INTO `gk_contact_items` (`id`, `category_id`, `title`, `branch`, `contact_person`, `virtual_filename`, `street`, `city`, `province`, `postal`, `country`, `tollfree`, `phone`, `fax`, `email`, `mobile`, `display_email`, `additional_info`, `additional_options`, `status`, `sort_order`) VALUES
(1, 1, 'Contact Person', 'Building Name', 'Mr. Baby Gekko', 'contact_person', '#123, 4567 Road NW', 'Edmonton', 'AB', 'T6L 1X6', 'Canada', '1.888.123.45678', '780.999.9999', '780.888.8888', 'info@babygekko.com', '780.777.7777', 0, '<p><img src="/images/demo/gekko2.png" alt="Baby Gekko" width="130" height="112" /></p>', '', 1, 0);
