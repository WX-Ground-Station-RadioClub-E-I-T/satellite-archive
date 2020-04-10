<?php

// Database configuration
define("MYSQL_HOST", $_ENV["MYSQL_HOST"]);
define("MYSQL_DATABASE", $_ENV["MYSQL_ARCHIVE_DATABASE"]);
define("MYSQL_USER", $_ENV["MYSQL_ARCHIVE_USER"]);
define("MYSQL_PASS", $_ENV["MYSQL_ARCHIVE_PASSWORD"]);
define("ARCHIVE_ENDPOINT", $_ENV["ARCHIVE_ENDPOINT"]);
define("ARCHIVE_VIDEOS_ENDPOINT", $_ENV["ARCHIVE_VIDEOS_ENDPOINT"]);
define("DEPENDENCIES_ENDPOINT", $_ENV["DEPENDENCIES_ENDPOINT"]);

define("DEBUG", false); // True, enter on debug mode
define("VIDEO_PREVIEW_VISIBLE_PIC_SAMPLE", "http://cesar.esa.int/sun_monitor/archive/helios/visible/2018/201803/20180316/image_hel_visible_20180316T103051_processed_thumbnail.jpg");
define("VIDEO_PREVIEW_HALPHA_PIC_SAMPLE", "http://cesar.esa.int/sun_monitor/archive/helios/halpha/2018/201803/20180316/image_hel_halpha_20180316T102851_processed_thumbnail.jpg");
