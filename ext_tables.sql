#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_siteessentials_google_analytics_content text NOT NULL,
	tx_siteessentials_sitemap_exclude tinyint(4) unsigned DEFAULT '0' NOT NULL,
	tx_siteessentials_sitemap_exclude_type tinyint(4) unsigned DEFAULT '0' NOT NULL,
	tx_siteessentials_sitemap_change_frequency tinyint(4) unsigned DEFAULT '0' NOT NULL,
	tx_siteessentials_sitemap_priority double(2,1) DEFAULT '0.5' NOT NULL,
	tx_siteessentials_robots_exclude tinyint(4) unsigned DEFAULT '0' NOT NULL,
	tx_siteessentials_robots_content text NOT NULL
);