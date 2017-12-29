<?php
/**
 * API constants file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 API Project (www.api.org)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 */


// Image Dimension Constraints
define('API_MINIMUM_WIDTH', '');

// Image Magick
define('API_MAGICK_CONVERT', '');
define('API_MAGICK_IDENTIFY', '');
define('API_MAGICK_ENABLED', '');

// Email Services
define('API_IMAP_IMAP', '');
define('API_IMAP_SMTP', '');
define('API_IMAP_IMAPPORT', '');
define('API_IMAP_SMTPPORT', '');
define('API_IMAP_CATCHALL', '');
define('API_IMAP_USERNAME', '');
define('API_IMAP_PASSWORD', '');

// Cache Timing
define('API_CACHE_IMAGE', 12222);
define('API_CACHE_ORIGINAL', 2777);
define('API_CACHE_SESSIONS', 1234567);

// Email Subject Constants
define('API_SUBJECT_UPDATEDICON', 'Updated Icon migrated for %s');
define('API_SUBJECT_NEWICON', 'New Icon add for %s');
define('API_SUBJECT_FORGOTTEN', 'Forgotten Password for user: %s');