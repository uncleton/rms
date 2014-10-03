-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2014 at 07:53 AM
-- Server version: 5.5.33-31.1
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `prime_uncletons`
--

-- --------------------------------------------------------

--
-- Table structure for table `pfi_category`
--

CREATE TABLE IF NOT EXISTS `pfi_category` (
  `id` bigint(20) NOT NULL,
  `category` text NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `section` bigint(20) DEFAULT NULL,
  `description` text,
  `image` varchar(100) DEFAULT NULL,
  `parent` varchar(50) NOT NULL,
  `parenttree` mediumtext NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`section`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_category`
--

INSERT INTO `pfi_category` (`id`, `category`, `alias`, `section`, `description`, `image`, `parent`, `parenttree`, `status`) VALUES
(1378427683319, 'Pizza', 'pizza', 1, '', NULL, '', '', 1),
(1378427715353, 'Pasta', 'pasta', 1, '', NULL, '', '', 1),
(1378427740771, 'Grill / Meat', 'grill', 1, '', NULL, '', '', 1),
(1378428102253, 'Drinks', 'drinks', 1, '', NULL, '', '', 1),
(1378428102254, 'Sides', 'sides', 1, NULL, NULL, '', '', 1),
(9999, 'Prepared Ingredient', 'prepared-ingredient', 1, 'Prepared Ingredient', NULL, '0', '', 1),
(8888, 'Packages', 'packages', 1, NULL, NULL, '', '', 1),
(1111, 'Freebies', 'freebies', 1, NULL, NULL, '', '', 1),
(2222, 'Others', 'others', 1, 'Other Category', NULL, '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_content`
--

CREATE TABLE IF NOT EXISTS `pfi_content` (
  `id` bigint(20) NOT NULL,
  `title` mediumtext,
  `content` text,
  `thumbnail` varchar(200) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `meta_title` mediumtext,
  `meta_keywords` mediumtext,
  `meta_description` mediumtext,
  `alias` varchar(100) DEFAULT NULL,
  `section` varchar(300) DEFAULT NULL,
  `catid` bigint(20) DEFAULT NULL,
  `lid` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `photo` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `teaser` text NOT NULL,
  `date_time` datetime NOT NULL,
  `venue` mediumtext NOT NULL,
  `map` mediumtext NOT NULL,
  `employer` varchar(255) NOT NULL,
  `download` varchar(100) NOT NULL,
  `location` tinytext NOT NULL,
  `contact_info` text NOT NULL,
  `view_count` int(11) NOT NULL DEFAULT '0',
  `permalink` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`,`section`,`catid`,`status`),
  KEY `alias_2` (`alias`,`catid`,`status`),
  KEY `alias_3` (`alias`),
  FULLTEXT KEY `title` (`title`,`content`,`caption`,`teaser`,`venue`,`contact_info`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_content`
--

INSERT INTO `pfi_content` (`id`, `title`, `content`, `thumbnail`, `author`, `uid`, `meta_title`, `meta_keywords`, `meta_description`, `alias`, `section`, `catid`, `lid`, `status`, `featured`, `date`, `photo`, `video`, `caption`, `teaser`, `date_time`, `venue`, `map`, `employer`, `download`, `location`, `contact_info`, `view_count`, `permalink`) VALUES
(1378260746599, 'Coordinator - Corporate Communications', '<p>Acucela is seeking a full-time Coordinator to join our Corporate Communications department.  The position will report into the Director of Corporate Communications, but will be required to interact with other members of the Company.   The ideal candidate will possess strong project management, editing skills, and be fluent in Japanese.</p>', NULL, 'admin', 1, NULL, '', '', 'coordinator---corporate-communications', '1375431742967', 0, NULL, 1, 0, '2013-09-04 15:12:49', '', '', '', '', '2013-09-06 10:39:00', '', '', 'Desert Research Institute', '', 'Burgundy Tower, ADB Ave. Ortigas Center, Pasig City', '<p>Juan Luna</p>\r\n<p>Juan@companyabc.com</p>', 0, ''),
(1373529394711, 'About Us', '<div class="about-div">\r\n<h1>About PRSP</h1>\r\n<p>The Public Relations Society of the Philippines(PRSP) is the country&rsquo;s premier organization for public relations professionals.</p>\r\n<p>In its roster are practitioners who represent business and industry, government, non-profit organizations, hospitals, schools, hotels and professional services among others.</p>\r\n<p>PRSP is a non-stock, non-profit organization established on February 19, 1957 by leading PR practitioners in the country.</p>\r\n<br />\r\n<br />\r\n<h1>PRSP Officers</h1>\r\n<div class="officers-hold">\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list-->\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list-->s\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list-->\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list-->\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list-->\r\n<div class="officers-list"><img src="../../templates/default/images/officers-img.jpg" alt="" />\r\n<h2>Juan Dela Cruz</h2>\r\n<span>President</span></div>\r\n<!--end of officers-list--></div>\r\n<!--end of officers-hold--></div>', NULL, 'admin', 1, NULL, '', '', 'about-us', '100', 1375430718553, NULL, 1, 0, '2013-07-11 20:56:59', '', '', '', '', '2013-09-04 00:30:00', '', '', '', '', '', '', 0, ''),
(1379335407634, 'Sample Award', '<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>\r\n<p>Nam commodo dapibus bibendum. Vivamus nec est nec eros placerat egestas. Nulla at massa eget metus dapibus porttitor. Maecenas id ultricies turpis, eu vestibulum nunc. Etiam condimentum dapibus urna, non commodo sapien condimentum eget. Praesent justo ipsum, ultrices in consectetur at, mollis sollicitudin felis. Duis est urna, ultrices consectetur ultricies a, egestas vel urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque et aliquet libero, nec sollicitudin enim. Morbi dignissim iaculis libero quis pharetra. Nam sit amet elit adipiscing, suscipit elit et, convallis quam. Morbi vulputate, augue a ornare vestibulum, nunc sapien fringilla elit, a egestas mauris risus eu tortor. Nullam egestas justo tortor, eu bibendum nunc convallis in. Aenean vitae accumsan tortor. Phasellus sit amet viverra ante.</p>\r\n<p>Vivamus dignissim fermentum rhoncus. Etiam placerat dapibus adipiscing. Vivamus a congue arcu. Sed vitae diam non arcu gravida iaculis. Vestibulum sodales in ipsum ut porttitor. Pellentesque congue augue nunc, et porttitor lacus congue a. In ac magna molestie purus tempus dignissim eget sit amet massa. Praesent mollis lorem vel molestie consectetur. Proin et velit erat. Phasellus ornare est ligula, ac varius massa laoreet ut. Suspendisse ante lacus, posuere quis bibendum non, gravida quis enim. Pellentesque sodales sapien et nisi egestas pretium.</p>\r\n<p>Ut a ligula et velit gravida sodales. Duis interdum nec eros id facilisis. Nullam id bibendum mi. Etiam ante orci, elementum vitae gravida vel, sodales eu est. Etiam sodales ultrices tortor, at fringilla leo lobortis eget. Curabitur sapien lacus, hendrerit sit amet orci quis, posuere pellentesque neque. Duis posuere lacinia consequat. Sed luctus tortor quis arcu sodales tristique.</p>\r\n<p>Curabitur justo turpis, auctor et placerat vel, commodo eget purus. Phasellus mollis ut orci sed dignissim. Donec a orci hendrerit, hendrerit quam nec, lacinia nibh. Sed suscipit, dolor vestibulum eleifend placerat, dolor massa ultricies nibh, congue varius libero arcu vel elit. Integer ac ante luctus, tincidunt metus nec, placerat odio. Ut quis nibh ut odio vulputate tristique. Cras a rhoncus leo. Mauris id porta metus. Cras in bibendum ipsum. Nullam auctor pellentesque elementum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin ac urna rutrum, suscipit purus eu, tempor orci. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-award', '1379335337621', 0, NULL, 1, 0, '2013-09-17 01:44:03', '1379335443228.jpg', '', '', '', '2013-09-18 01:00:00', '', '', '', '', '', '', 0, ''),
(1373531781658, 'Sample Blog', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sapien metus, blandit suscipit justo a, cursus dignissim diam. Fusce pellentesque sit amet nunc in pharetra. Donec varius eros eget risus commodo, eget adipiscing sem tempus. Nunc malesuada, lorem eu feugiat eleifend, diam erat fringilla ante, sed elementum dolor urna sed sapien. Quisque odio orci, rhoncus ut turpis vel, dictum adipiscing lorem. Vivamus fringilla tincidunt tempor. Etiam mi orci, tincidunt eget dui at, iaculis commodo augue.</p>\r\n<p>Nullam at est justo. Vivamus imperdiet, augue in tempor suscipit, urna libero consequat arcu, id bibendum felis arcu suscipit nibh. Morbi sed fringilla eros. Phasellus ut mattis ligula. Maecenas ut dapibus massa. Aenean in nisi mauris. Quisque non felis nec lectus mollis rhoncus sit amet eget turpis. Pellentesque mattis eget massa non elementum. Nulla lacinia, est quis euismod cursus, libero risus semper elit, nec gravida lectus nisl sit amet mi. Cras sollicitudin aliquet nisl, eu condimentum odio.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-blog', '1373342459253', 0, NULL, 1, 0, '2013-07-11 21:36:47', '1378883079375.jpg', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', 0, ''),
(1378282403569, 'Sample Event', '<p>&nbsp;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin leo ipsum, feugiat sit amet mattis non, ultrices eget dui. Pellentesque volutpat elit libero, et varius lectus pretium vulputate. Praesent adipiscing eleifend lectus, non pulvinar mi semper non. Praesent id mauris molestie, tempus magna ut, bibendum dolor. Vivamus luctus nec erat et aliquet. Fusce ultrices hendrerit consequat. Mauris lacinia auctor elit. Sed porttitor velit non laoreet dapibus. Proin egestas felis urna, nec elementum felis luctus sed. Maecenas ac elit accumsan enim interdum egestas. Nullam non ligula eget felis tempor cursus. Cras rutrum facilisis ullamcorper. In porta libero nec est molestie elementum.</p>\r\n<p>Suspendisse potenti. Fusce at aliquam tortor. Integer cursus, quam quis varius scelerisque, eros ante mattis dolor, sed congue orci purus et nulla. Nullam lacinia massa vitae arcu sollicitudin pharetra. Nunc in porttitor nisi. Aliquam tincidunt eu odio sed condimentum. Nam sodales in nunc in sodales. Aliquam nec odio lacus.</p>\r\n<div class="fSeminars">\r\n<ul>\r\n    <li><img alt="Highlight 1" src="images/highlight-img1.jpg" />\r\n    <div class="fSeminars-Info">\r\n    <h3>fredrick sy</h3>\r\n    <div class="fSeminars-Info-gray">\r\n    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas accumsan rutrum nibh, a vulputate nunc</p>\r\n    </div>\r\n    <!--end of Finfo-gray--></div>\r\n    <!--end of fInfo--></li>\r\n    <li><img alt="Highlight 1" src="images/highlight-img2.jpg" />\r\n    <div class="fSeminars-Info">\r\n    <h3>prof. kyle lim</h3>\r\n    <div class="fSeminars-Info-gray">\r\n    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas accumsan rutrum nibh, a vulputate nunc</p>\r\n    </div>\r\n    <!--end of Finfo-gray--></div>\r\n    <!--end of fInfo--></li>\r\n    <li><img alt="Highlight 1" src="images/highlight-img3.jpg" />\r\n    <div class="fSeminars-Info">\r\n    <h3>JOSEF Integra</h3>\r\n    <div class="fSeminars-Info-gray">\r\n    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas accumsan rutrum nibh, a vulputate nunc</p>\r\n    </div>\r\n    <!--end of Finfo-gray--></div>\r\n    <!--end of fInfo--></li>\r\n</ul>\r\n<div class="clearDiv">&nbsp;</div>\r\n</div>', NULL, 'admin', 1, NULL, 'Araneta Sports Center', '', 'sample-event', '1375430135521', 0, NULL, 1, 0, '2013-09-04 21:15:01', '1380180560535.jpg', '', '', '', '2013-09-19 18:00:00', 'Araneta Sports Center', '25 ADB Ave Pasig City ', '', '', '', '', 0, ''),
(1379494843388, 'Test', '', NULL, 'admin', 1, NULL, '', '', 'test', '1379494794627', 0, NULL, 1, 0, '2013-09-18 22:01:24', '1379497312983.jpg', '', '', '', '2013-09-18 09:00:00', '', '', '', '', '', '', 0, 'http://google.com'),
(1379497320545, 'Fortius', '', NULL, 'admin', 1, NULL, '', '', 'fortius', '1379494794627', 0, NULL, 1, 0, '2013-09-18 22:42:29', '1379497349590.jpg', '', '', '', '2013-09-18 09:42:00', '', '', '', '', '', '', 0, 'http://primefactors.ph'),
(1380275836826, 'test', '<p>f sdf sadf</p>', NULL, 'admin', 1, NULL, '', '', 'testdsadf', '1373342459253', 0, NULL, 1, 0, '2013-09-27 22:57:40', '', '', '', '', '2013-09-27 09:57:00', '', '', '', '', '', '', 0, ''),
(1378431885772, 'Enterprise-Cass Videoconferencing for the Masses', '<p>Videoconferencing is an essential part of every worker''s toolbox. Until now, traditional limitations have hampered productivity and creativity and also prevented organizations from maximizing the ROI of their videoconferencing system investments. Businesses must choose an enterprise video-conferencing system that provides users the flexibility they need to collaborate anywhere, anytime and with anyone they want. Download this white paper to learn more.</p>', NULL, 'admin', 1, NULL, '', '', 'enterprise-cass-videoconferencing-for-the-masses', '1378427429384', 1378427683319, NULL, 1, 0, '2013-09-06 14:46:26', '1378715210882.png', '', '', '', '2013-09-06 09:44:00', '', '', '', '1378698093236.zip', '', '', 0, ''),
(1378803686901, 'Test 2', '<p>asdf asdf asdf asdf</p>', NULL, 'admin', 1, NULL, '', '', 'test-2', '1375430135521', 0, NULL, 1, 0, '2013-09-10 22:02:09', '1380180545887.jpg', '', '', '', '2013-09-04 10:00:00', 'Araneta Center', '', '', '', '', '', 0, ''),
(1378803734945, 'Test 3', '<p>asdf sfd asd f</p>', NULL, 'admin', 1, NULL, '', '', 'test-3', '1375430135521', 0, NULL, 1, 0, '2013-09-10 22:02:58', '1380180531856.png', '', '', '', '2013-09-28 14:30:00', 'Ortigas Pasig', '', '', '', '', '', 0, ''),
(1378883098352, 'Sample Blog Post 2', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sapien metus, blandit suscipit justo a, cursus dignissim diam. Fusce pellentesque sit amet nunc in pharetra. Donec varius eros eget risus commodo, eget adipiscing sem tempus. Nunc malesuada, lorem eu feugiat eleifend, diam erat fringilla ante, sed elementum dolor urna sed sapien. Quisque odio orci, rhoncus ut turpis vel, dictum adipiscing lorem. Vivamus fringilla tincidunt tempor. Etiam mi orci, tincidunt eget dui at, iaculis commodo augue.</p>\r\n<p>Nullam at est justo. Vivamus imperdiet, augue in tempor suscipit, urna libero consequat arcu, id bibendum felis arcu suscipit nibh. Morbi sed fringilla eros. Phasellus ut mattis ligula. Maecenas ut dapibus massa. Aenean in nisi mauris. Quisque non felis nec lectus mollis rhoncus sit amet eget turpis. Pellentesque mattis eget massa non elementum. Nulla lacinia, est quis euismod cursus, libero risus semper elit, nec gravida lectus nisl sit amet mi. Cras sollicitudin aliquet nisl, eu condimentum odio.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-blog-post-2', '1373342459253', 0, NULL, 1, 0, '2013-09-11 20:05:32', '1378883132362.jpg', '', '', '', '2013-09-11 07:04:00', '', '', '', '', '', '', 0, ''),
(1378883328924, 'Sample blog 3', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sapien metus, blandit suscipit justo a, cursus dignissim diam. Fusce pellentesque sit amet nunc in pharetra. Donec varius eros eget risus commodo, eget adipiscing sem tempus. Nunc malesuada, lorem eu feugiat eleifend, diam erat fringilla ante, sed elementum dolor urna sed sapien. Quisque odio orci, rhoncus ut turpis vel, dictum adipiscing lorem. Vivamus fringilla tincidunt tempor. Etiam mi orci, tincidunt eget dui at, iaculis commodo augue.</p>\r\n<p>Nullam at est justo. Vivamus imperdiet, augue in tempor suscipit, urna libero consequat arcu, id bibendum felis arcu suscipit nibh. Morbi sed fringilla eros. Phasellus ut mattis ligula. Maecenas ut dapibus massa. Aenean in nisi mauris. Quisque non felis nec lectus mollis rhoncus sit amet eget turpis. Pellentesque mattis eget massa non elementum. Nulla lacinia, est quis euismod cursus, libero risus semper elit, nec gravida lectus nisl sit amet mi. Cras sollicitudin aliquet nisl, eu condimentum odio.</p>', NULL, 'admin test', 1, NULL, '', '', 'sample-blog-2', '1373342459253', 0, NULL, 1, 0, '2013-09-11 20:09:36', '1378883376845.jpg', '', '', '', '2013-09-11 07:08:00', '', '', '', '', '', '', 0, ''),
(1379297287651, 'Sample Press Release', '<p>Covered across a core of seven topics from work and love to exploration and dreams, this year&rsquo;s version will expand to Malaysia, Vietnam, Indonesia and India as well.</p>\r\n<p>Courtesy Amanda Mooney and Edelman PR Flickr Filmed back in May of this year, the seven-video series is revealed on Edelman&rsquo;s official microsite http://wordsofageneration.co/. But the fascinating series is aptly highlighted by the words of Cornelia Kunze, vice chairman, Edelman Asia Pacific, Middle East &amp; Africa: &ldquo;The world and marketers have been fascinated with millennials in China, Singapore, Malaysia, Vietnam, Indonesia and India, but what is less talked about is the generation born around the 1970s &ndash; their parents, uncles and aunts who built the modern societies we find today. Right now, these are the ones with the buying power too, and for marketers they are incredibly difficult to understand. We decided to commit this to video because we believe the substance of what they say is best seen and heard rather than</p>', NULL, 'admin', 1, NULL, '', '', 'sample-press-release', '1375430207857', 0, NULL, 1, 0, '2013-09-16 15:09:05', '1379335563396.jpg', '', '', '', '2013-09-16 02:08:00', '', '', '', '', '', '', 20, ''),
(1379297564426, 'Sample Press Release 2', '<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>\r\n<p>Nam commodo dapibus bibendum. Vivamus nec est nec eros placerat egestas. Nulla at massa eget metus dapibus porttitor. Maecenas id ultricies turpis, eu vestibulum nunc. Etiam condimentum dapibus urna, non commodo sapien condimentum eget. Praesent justo ipsum, ultrices in consectetur at, mollis sollicitudin felis. Duis est urna, ultrices consectetur ultricies a, egestas vel urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque et aliquet libero, nec sollicitudin enim. Morbi dignissim iaculis libero quis pharetra. Nam sit amet elit adipiscing, suscipit elit et, convallis quam. Morbi vulputate, augue a ornare vestibulum, nunc sapien fringilla elit, a egestas mauris risus eu tortor. Nullam egestas justo tortor, eu bibendum nunc convallis in. Aenean vitae accumsan tortor. Phasellus sit amet viverra ante.</p>\r\n<p>Vivamus dignissim fermentum rhoncus. Etiam placerat dapibus adipiscing. Vivamus a congue arcu. Sed vitae diam non arcu gravida iaculis. Vestibulum sodales in ipsum ut porttitor. Pellentesque congue augue nunc, et porttitor lacus congue a. In ac magna molestie purus tempus dignissim eget sit amet massa. Praesent mollis lorem vel molestie consectetur. Proin et velit erat. Phasellus ornare est ligula, ac varius massa laoreet ut. Suspendisse ante lacus, posuere quis bibendum non, gravida quis enim. Pellentesque sodales sapien et nisi egestas pretium.</p>\r\n<p>Ut a ligula et velit gravida sodales. Duis interdum nec eros id facilisis. Nullam id bibendum mi. Etiam ante orci, elementum vitae gravida vel, sodales eu est. Etiam sodales ultrices tortor, at fringilla leo lobortis eget. Curabitur sapien lacus, hendrerit sit amet orci quis, posuere pellentesque neque. Duis posuere lacinia consequat. Sed luctus tortor quis arcu sodales tristique.</p>\r\n<p>Curabitur justo turpis, auctor et placerat vel, commodo eget purus. Phasellus mollis ut orci sed dignissim. Donec a orci hendrerit, hendrerit quam nec, lacinia nibh. Sed suscipit, dolor vestibulum eleifend placerat, dolor massa ultricies nibh, congue varius libero arcu vel elit. Integer ac ante luctus, tincidunt metus nec, placerat odio. Ut quis nibh ut odio vulputate tristique. Cras a rhoncus leo. Mauris id porta metus. Cras in bibendum ipsum. Nullam auctor pellentesque elementum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin ac urna rutrum, suscipit purus eu, tempor orci. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-press-release-2', '1375430207857', 0, NULL, 1, 0, '2013-09-16 15:13:44', '1379335553743.jpg', '', '', '', '2013-09-16 02:12:00', '', '', '', '', '', '', 62, ''),
(1379302450292, 'Sample Webinar', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>\r\n<p>Nam commodo dapibus bibendum. Vivamus nec est nec eros placerat egestas. Nulla at massa eget metus dapibus porttitor. Maecenas id ultricies turpis, eu vestibulum nunc. Etiam condimentum dapibus urna, non commodo sapien condimentum eget. Praesent justo ipsum, ultrices in consectetur at, mollis sollicitudin felis. Duis est urna, ultrices consectetur ultricies a, egestas vel urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque et aliquet libero, nec sollicitudin enim. Morbi dignissim iaculis libero quis pharetra. Nam sit amet elit adipiscing, suscipit elit et, convallis quam. Morbi vulputate, augue a ornare vestibulum, nunc sapien fringilla elit, a egestas mauris risus eu tortor. Nullam egestas justo tortor, eu bibendum nunc convallis in. Aenean vitae accumsan tortor. Phasellus sit amet viverra ante.</p>\r\n<p>Vivamus dignissim fermentum rhoncus. Etiam placerat dapibus adipiscing. Vivamus a congue arcu. Sed vitae diam non arcu gravida iaculis. Vestibulum sodales in ipsum ut porttitor. Pellentesque congue augue nunc, et porttitor lacus congue a. In ac magna molestie purus tempus dignissim eget sit amet massa. Praesent mollis lorem vel molestie consectetur. Proin et velit erat. Phasellus ornare est ligula, ac varius massa laoreet ut. Suspendisse ante lacus, posuere quis bibendum non, gravida quis enim. Pellentesque sodales sapien et nisi egestas pretium.</p>\r\n<p>Ut a ligula et velit gravida sodales. Duis interdum nec eros id facilisis. Nullam id bibendum mi. Etiam ante orci, elementum vitae gravida vel, sodales eu est. Etiam sodales ultrices tortor, at fringilla leo lobortis eget. Curabitur sapien lacus, hendrerit sit amet orci quis, posuere pellentesque neque. Duis posuere lacinia consequat. Sed luctus tortor quis arcu sodales tristique.</p>\r\n<p>Curabitur justo turpis, auctor et placerat vel, commodo eget purus. Phasellus mollis ut orci sed dignissim. Donec a orci hendrerit, hendrerit quam nec, lacinia nibh. Sed suscipit, dolor vestibulum eleifend placerat, dolor massa ultricies nibh, congue varius libero arcu vel elit. Integer ac ante luctus, tincidunt metus nec, placerat odio. Ut quis nibh ut odio vulputate tristique. Cras a rhoncus leo. Mauris id porta metus. Cras in bibendum ipsum. Nullam auctor pellentesque elementum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin ac urna rutrum, suscipit purus eu, tempor orci. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-webinar', '1378427429384', 1378427715353, NULL, 1, 0, '2013-09-16 16:34:51', '1379302491729.jpg', '', '', '', '2013-09-16 03:34:00', '', '', '', '1379302491756.zip', '', '', 0, ''),
(1379327024578, 'Featured Event', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>', NULL, 'admin', 1, NULL, '', '', 'featured-event', '1379324214350', 0, NULL, 1, 0, '2013-09-16 23:24:58', '1379328000932.png', '', '', '', '2013-09-16 10:23:00', '', '', '', '', '', '', 0, 'http://localhost/fort-framework/events/test-3.html'),
(1379327690508, 'Sample Fatured 2', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-fatured-2', '1379324214350', 0, NULL, 1, 0, '2013-09-16 23:35:38', '1379327974942.png', '', '', '', '2013-09-16 10:34:00', '', '', '', '', '', '', 0, 'http://localhost/fort-framework/events/test-3.html'),
(1379332493974, 'Sample Property', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at bibendum eros. Fusce tristique faucibus condimentum. Nam ligula lorem, tempor eget libero sit amet, commodo posuere metus. Sed in velit rutrum, pretium nunc sed, semper sapien. Nulla vitae erat odio. Etiam adipiscing commodo massa in interdum. Proin elementum enim nunc, in egestas dui vulputate eu. Nullam ultrices in turpis iaculis semper. Sed ac molestie leo. Nullam ornare iaculis diam, sit amet suscipit odio tempus non. Etiam porttitor nisl a dignissim aliquam. Mauris tristique posuere nisi id placerat.</p>\r\n<p>Nam commodo dapibus bibendum. Vivamus nec est nec eros placerat egestas. Nulla at massa eget metus dapibus porttitor. Maecenas id ultricies turpis, eu vestibulum nunc. Etiam condimentum dapibus urna, non commodo sapien condimentum eget. Praesent justo ipsum, ultrices in consectetur at, mollis sollicitudin felis. Duis est urna, ultrices consectetur ultricies a, egestas vel urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque et aliquet libero, nec sollicitudin enim. Morbi dignissim iaculis libero quis pharetra. Nam sit amet elit adipiscing, suscipit elit et, convallis quam. Morbi vulputate, augue a ornare vestibulum, nunc sapien fringilla elit, a egestas mauris risus eu tortor. Nullam egestas justo tortor, eu bibendum nunc convallis in. Aenean vitae accumsan tortor. Phasellus sit amet viverra ante.</p>\r\n<p>Vivamus dignissim fermentum rhoncus. Etiam placerat dapibus adipiscing. Vivamus a congue arcu. Sed vitae diam non arcu gravida iaculis. Vestibulum sodales in ipsum ut porttitor. Pellentesque congue augue nunc, et porttitor lacus congue a. In ac magna molestie purus tempus dignissim eget sit amet massa. Praesent mollis lorem vel molestie consectetur. Proin et velit erat. Phasellus ornare est ligula, ac varius massa laoreet ut. Suspendisse ante lacus, posuere quis bibendum non, gravida quis enim. Pellentesque sodales sapien et nisi egestas pretium.</p>\r\n<p>Ut a ligula et velit gravida sodales. Duis interdum nec eros id facilisis. Nullam id bibendum mi. Etiam ante orci, elementum vitae gravida vel, sodales eu est. Etiam sodales ultrices tortor, at fringilla leo lobortis eget. Curabitur sapien lacus, hendrerit sit amet orci quis, posuere pellentesque neque. Duis posuere lacinia consequat. Sed luctus tortor quis arcu sodales tristique.</p>\r\n<p>Curabitur justo turpis, auctor et placerat vel, commodo eget purus. Phasellus mollis ut orci sed dignissim. Donec a orci hendrerit, hendrerit quam nec, lacinia nibh. Sed suscipit, dolor vestibulum eleifend placerat, dolor massa ultricies nibh, congue varius libero arcu vel elit. Integer ac ante luctus, tincidunt metus nec, placerat odio. Ut quis nibh ut odio vulputate tristique. Cras a rhoncus leo. Mauris id porta metus. Cras in bibendum ipsum. Nullam auctor pellentesque elementum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin ac urna rutrum, suscipit purus eu, tempor orci. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>', NULL, 'admin', 1, NULL, '', '', 'sample-property', '1379332274575', 0, NULL, 1, 0, '2013-09-17 00:55:18', '1379335238825.jpg', '', '', '', '2013-09-16 11:54:00', '', '', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_discounts`
--

CREATE TABLE IF NOT EXISTS `pfi_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `discount` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pfi_discounts`
--

INSERT INTO `pfi_discounts` (`id`, `name`, `discount`) VALUES
(1, 'Senior Citizen', '20%'),
(2, 'Esclusivo', '10%'),
(3, 'Employee', '20%'),
(4, 'Other DIscount', 'any');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_ingredients`
--

CREATE TABLE IF NOT EXISTS `pfi_ingredients` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `stocks` int(11) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `unit_price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_ingredients`
--

INSERT INTO `pfi_ingredients` (`id`, `name`, `stocks`, `unit`, `unit_price`) VALUES
(1, 'Flour', 1652, 'g', 1.5),
(1388403974323, 'Sample', 1161, 'l', 4),
(1388447171672, 'Tomato Sauce', 484, 'l', 2.45),
(1392640055849, 'Sample 1', 500, 'ml', 0.02),
(1392830023725, 'Test', 1000, 'ml', 0.3),
(1392878059245, 'flour', 2000, 'g', 0.899);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_layout`
--

CREATE TABLE IF NOT EXISTS `pfi_layout` (
  `id` varchar(20) NOT NULL,
  `html` longtext,
  `layout_name` varchar(200) DEFAULT NULL,
  `date` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_layout`
--

INSERT INTO `pfi_layout` (`id`, `html`, `layout_name`, `date`, `status`) VALUES
('1', '<tr>\r\n<td height="9" colspan="5" class="bg5"></td>\r\n</tr>\r\n<tr>\r\n<td width="11"></td>\r\n<td width="500" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n  <tr>\r\n	<td width="15" valign="top"><img src="/images/box_top_left.jpg" /></td>\r\n<td class="bg6"></td>\r\n<td width="15" valign="top"><img src="/images/box_top_right.jpg" /></td>\r\n  </tr>\r\n  <tr>\r\n	<td class="bg7">&nbsp;</td>\r\n	<td bgcolor="#FFFFFF">\r\n{content}\r\n	</td>\r\n	<td class="bg8">&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n	<td class="bg9" height="157">&nbsp;</td>\r\n	<td class="bg10">&nbsp;</td>\r\n	<td class="bg11">&nbsp;</td>\r\n  </tr>\r\n</table></td>\r\n<td width="9">&nbsp;</td>\r\n<td width="11">&nbsp;</td>\r\n</tr>', 'Default Layout', 1231486877, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_links`
--

CREATE TABLE IF NOT EXISTS `pfi_links` (
  `id` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `title` varchar(300) DEFAULT NULL,
  `button` varchar(200) DEFAULT NULL,
  `rawurl` varchar(300) DEFAULT NULL,
  `seourl` mediumtext NOT NULL,
  `aliasurl` mediumtext NOT NULL,
  `parent_link` varchar(255) NOT NULL,
  `parent_tree` varchar(255) NOT NULL,
  `type` varchar(200) NOT NULL,
  `navid` bigint(20) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `folder` varchar(200) DEFAULT NULL,
  `plugins` text,
  `custom` varchar(2) NOT NULL,
  `refid` bigint(20) DEFAULT NULL,
  `template` int(11) DEFAULT '1',
  `class` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `refid` (`refid`,`status`),
  KEY `parent_link` (`parent_link`,`navid`,`sequence`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_links`
--

INSERT INTO `pfi_links` (`id`, `uid`, `title`, `button`, `rawurl`, `seourl`, `aliasurl`, `parent_link`, `parent_tree`, `type`, `navid`, `sequence`, `folder`, `plugins`, `custom`, `refid`, `template`, `class`, `status`, `date`) VALUES
(1302677983822, 1, 'Contact us', 'Contact us', 'contact-us.html', 'contact-us.html', 'contact-us', '0', '', 'index', 1302677919562, NULL, 'index', NULL, '', 0, 1, '', 1, '2011-04-13 20:00:42'),
(1309322822412, 1, 'Home', 'Home', 'index.html', '', 'index', '1', '', 'index', 1, 1, 'index', '||', 'Y', 0, 1, '', 0, '2011-06-29 17:47:49'),
(1379495089969, 1, 'Members List', 'Members List', 'members_list.html', 'members-list.html', 'members-list', '1375431385134', '', 'module', 1, NULL, 'members_list', NULL, '', 1379495024536, 1, '', 1, '2013-09-18 22:05:12'),
(1375430523370, 1, 'Events', 'Events', 'events.html', 'events.html', 'events', '1', '', 'module', 1, 2, 'events', '|1|', 'Y', 1378801940262, 1, '', 1, '2013-08-02 21:02:53'),
(1375431307192, 1, 'About Us', 'About', '100/1373529394711/about-us.html', 'pages/static/about-us.html', 'about-us', '1', '', 'article', 1, 3, 'article', NULL, '', 1373529394711, 1, '', 1, '2013-08-02 21:15:56'),
(1373339945105, 1, 'Blog', 'Blog', '/1373357060550/news-article-sample.html', 'news/news-article-sample.html', 'news-article-sample', '1373529436978', '', 'article', 1, NULL, 'article', '|1373339789731|1309320332234|1309323823376|1|', 'N', 1373357060550, 1, '', 1, '2013-07-09 16:19:32'),
(1375431385134, 1, 'Members', 'Members', 'members.html', 'member.html', 'member', '1', '', 'module', 1, 5, 'members', NULL, '', 1378881929825, 1, '', 1, '2013-08-02 21:17:22'),
(1373529436978, 1, 'Page 2', 'News', 'category/news.html', 'page-2.html', 'page-2', '1373279043917', '', 'category', 1, NULL, 'category', '||', 'N', 1, 1, '', 1, '2013-07-11 20:57:59'),
(1375431606858, 1, 'Contact Us', 'Contact Us', 'contact-us.html', 'contact-us.html', 'contact-us', '1', '', 'index', 1, 6, 'index', '|1|1311315778233|', 'Y', 0, 1, '', 1, '2013-08-02 21:21:00'),
(1375431776288, 1, 'Jobs', 'Job List', 'jobs.html', 'jobs.html', 'jobs', '1', '', 'module', 1, 9, 'jobs', '|1309320332234|1|', 'Y', 1378368763281, 1, '', 1, '2013-08-02 21:23:33'),
(1378429984937, 1, 'Property', 'Property', '100/1378429908343/property.html', 'pages/static/property.html', 'property', '1', '', 'article', 1, 7, 'article', NULL, '', 1378429908343, 1, '', 0, '2013-09-06 14:13:25'),
(1378429819985, 1, 'Blogs', 'Blog', 'blog.html', 'blog.html', 'blog', '1', '', 'module', 1, 4, 'blog', '|1|', 'Y', 1378881908799, 1, '', 1, '2013-09-06 14:10:59'),
(1378430026451, 1, 'Press', 'Press', 'press.html', 'press.html', 'press', '1', '', 'module', 1, 8, 'press', NULL, '', 1379297404166, 1, '', 1, '2013-09-06 14:14:05'),
(1379662527268, 1, 'Sample Section', 'pages', 'category/case-studies.html', 'pages.html', 'pages', '1', '', 'category', 1, NULL, 'category', NULL, '', 1378427683319, 1, '', 1, '2013-09-20 20:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_modules`
--

CREATE TABLE IF NOT EXISTS `pfi_modules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` bigint(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `file` varchar(50) NOT NULL,
  `template` int(11) NOT NULL DEFAULT '1',
  `pages` mediumtext,
  `custom` enum('N','Y') NOT NULL DEFAULT 'N',
  `content_src_id` bigint(20) NOT NULL,
  `content_src` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `pfi_modules`
--

INSERT INTO `pfi_modules` (`id`, `sid`, `title`, `folder`, `file`, `template`, `pages`, `custom`, `content_src_id`, `content_src`) VALUES
(1, 1280127299403, 'Index Page', 'index', 'index', 1, '|1|1379323864755|1379326160141|1379326215528|1379326200653|1379326183315|1379338564717|', 'Y', 0, ''),
(2, 1280152221875, 'Article Post', 'article', 'index', 1, '|1|1309320332234|1309323823376|', 'Y', 0, ''),
(5, 1311323492866, 'Login Form', 'login', 'index', 1, '|1309323823376|1|1309320332234|', 'Y', 0, ''),
(6, 1311335505167, 'Registration Form', 'registration', 'index', 1, '|1|1309323823376|1309320332234|', 'Y', 0, ''),
(7, 1311335505167, 'Forgot Password Form', 'registration', 'forgot', 1, '|1|1309323823376|1309320332234|', 'Y', 0, ''),
(8, 1311335505167, 'Edit Form', 'registration', 'edit', 1, '|1|1309323823376|1309320332234|', 'Y', 0, ''),
(9, 1296543273451, 'Article Section', 'section', 'index', 1, '|1309320332234|1309323823376|1|', 'Y', 0, ''),
(10, 1280155122381, 'Article Category', 'category', 'index', 1, '|1|1309323823376|1309320332234|', 'N', 0, ''),
(11, 1378368763281, 'Job Posting', 'jobs', 'index', 1, '|1|1309323823376|1309320332234|', 'Y', 1375431742967, 'section'),
(12, 1378801940262, 'Events', 'events', 'index', 1, '|1|', 'Y', 1375430135521, 'section'),
(13, 1378881908799, 'Blogs', 'blog', 'index', 1, '|1|', 'N', 1373342459253, 'section'),
(14, 1378881929825, 'Members', 'members', 'index', 1, '|1|', 'N', 1378427429384, 'section'),
(15, 1378881908799, 'Author', 'blog', 'author', 1, '|1|', 'N', 0, ''),
(17, 1379297404166, 'Press', 'press', 'index', 1, '|1|', 'Y', 1375430207857, 'section'),
(18, 1378801940262, 'Event Registration Form', 'events', 'register', 1, '|1|', 'N', 0, ''),
(19, 1379495024536, 'Members List', 'members_list', 'index', 1, '|1|', 'Y', 1379494794627, 'section'),
(20, 1380126217566, 'Search Page', 'search', 'index', 1, '|1|', 'Y', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_nav`
--

CREATE TABLE IF NOT EXISTS `pfi_nav` (
  `id` bigint(20) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` mediumtext NOT NULL,
  `css_prefix` varchar(50) DEFAULT NULL,
  `tags` varchar(200) NOT NULL,
  `parentid` bigint(20) DEFAULT '1',
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_nav`
--

INSERT INTO `pfi_nav` (`id`, `uid`, `title`, `date`, `description`, `css_prefix`, `tags`, `parentid`, `status`) VALUES
(1, '1', 'Main Navigator', '0000-00-00 00:00:00', 'Default Navigator ', '', 'navigator-1272241800107', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_order`
--

CREATE TABLE IF NOT EXISTS `pfi_order` (
  `id` bigint(20) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  `plugin_id` bigint(20) NOT NULL,
  `position_id` int(11) NOT NULL,
  `order` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_order`
--

INSERT INTO `pfi_order` (`id`, `module_id`, `plugin_id`, `position_id`, `order`) VALUES
(1309338141949, 1, 1, 1, 0),
(1309338330344, 3, 1309323823376, 2, 0),
(1309338332503, 3, 1309320332234, 9, 0),
(1309341858319, 3, 1, 1, 0),
(1309359802244, 4, 1, 1, 0),
(1309406238190, 4, 1309323823376, 2, 0),
(1309406241606, 4, 1309320332234, 9, 0),
(1311323560312, 5, 1309323823376, 2, 0),
(1311323563659, 5, 1, 1, 0),
(1311323576219, 5, 1309320332234, 9, 0),
(1311337049283, 6, 1, 1, 0),
(1311337052851, 6, 1309323823376, 2, 0),
(1311337056695, 6, 1309320332234, 9, 0),
(1311337064641, 7, 1, 1, 0),
(1311337070426, 7, 1309323823376, 2, 0),
(1311337073862, 7, 1309320332234, 9, 0),
(1311337080984, 8, 1, 1, 0),
(1311337083331, 8, 1309323823376, 2, 0),
(1311337086390, 8, 1309320332234, 9, 0),
(1311583975275, 2, 1, 1, 0),
(1311583980395, 2, 1309320332234, 9, 0),
(1311583983113, 2, 1309323823376, 2, 0),
(1373340903541, 1373339945105, 1373339789731, 5, 0),
(1373340914390, 1373339945105, 1309320332234, 9, 0),
(1373340917901, 1373339945105, 1309323823376, 1, 0),
(1373340933508, 1373339945105, 1, 1, 1),
(1373627177707, 1373279043917, 1, 1, 1),
(1373627217935, 1373279043917, 1309320332234, 9, 0),
(1373627221699, 1373279043917, 1309323823376, 1, 0),
(1373627308343, 1373357131164, 1309323823376, 1, 0),
(1373627316514, 1373357131164, 1, 1, 1),
(1373627326244, 1373357131164, 1309320332234, 9, 0),
(1375934182404, 1375431606858, 1, 1, 0),
(1375934186184, 1375431606858, 1311315778233, 5, 0),
(1378369355424, 1375431776288, 1309320332234, 9, 0),
(1378369359899, 1375431776288, 1, 1, 0),
(1378428947421, 0, 1309323823376, 1, 1),
(1378429230557, 0, 1311315778233, 1, 0),
(1378429640737, 9, 1309320332234, 9, 0),
(1378429644730, 9, 1309323823376, 1, 0),
(1378429647780, 9, 1, 1, 1),
(1378435379883, 11, 1, 1, 1),
(1378435383296, 11, 1309323823376, 1, 0),
(1378435385498, 11, 1309320332234, 9, 0),
(1378802002673, 1375430523370, 1, 1, 0),
(1378806173755, 12, 1, 1, 0),
(1378882819964, 1378429819985, 1, 1, 0),
(1379297538850, 17, 1, 1, 0),
(1379325527627, 1, 1379323864755, 5, 0),
(1379326251801, 1, 1379326160141, 5, 5),
(1379326258541, 1, 1379326215528, 5, 1),
(1379326267270, 1, 1379326183315, 5, 4),
(1379495060475, 19, 1, 1, 0),
(1380126277474, 20, 1, 1, 0),
(1380181081197, 1, 1379326200653, 5, 3),
(1380181084230, 1, 1379338564717, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_orders`
--

CREATE TABLE IF NOT EXISTS `pfi_orders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(200) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `pickup_time` time NOT NULL,
  `tbl` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1393211752246 ;

--
-- Dumping data for table `pfi_orders`
--

INSERT INTO `pfi_orders` (`id`, `customer_name`, `phone`, `address`, `pickup_time`, `tbl`, `date`, `status`) VALUES
(1392877655583, '', '', '', '14:29:30', 't2', '2014-02-20 06:30:15', 0),
(1392864999888, '', '', '', '10:57:00', 't1', '2014-02-20 02:59:34', 0),
(1392833441120, '', '', '', '02:10:30', 't2', '2014-02-19 18:11:01', 1),
(1392833291790, '', '', '', '02:08:00', 't2', '2014-02-19 18:08:33', 0),
(1392830666452, '', '', '', '01:24:15', 't6', '2014-02-19 17:25:03', 0),
(1392829669129, 'Raymund Deang', '123123123', 'asdf sadf sadf sadf', '01:08:15', 't3', '2014-02-19 17:08:45', 0),
(1392829608775, 'Raymund Deang', '1234567', 'asdf asdf asdf sd f', '01:06:45', 'p1', '2014-02-19 17:07:14', 2),
(1392896588902, '', '', '', '19:43:15', 't2', '2014-02-20 11:43:53', 2),
(1392897424140, '', '', '', '19:57:15', 't2', '2014-02-20 11:58:23', 2),
(1392897909410, '', '', '', '20:05:15', 't3', '2014-02-20 12:05:36', 1),
(1392905911324, '', '', '', '22:18:30', 't1', '2014-02-20 14:30:28', 2),
(1392974426819, '', '', '', '17:20:30', 't2', '2014-02-21 09:21:01', 1),
(1392974463886, '', '', '', '17:21:30', 't10', '2014-02-21 09:22:02', 1),
(1392995711123, '', '', '', '23:15:15', 't3', '2014-02-21 15:42:21', 0),
(1393045645640, '', '', '', '13:07:30', 't4', '2014-02-22 05:08:48', 1),
(1393060226410, '', '', '', '17:10:30', 't3', '2014-02-22 09:11:26', 1),
(1393106578269, '', '', '', '06:03:00', 't1', '2014-02-22 22:06:29', 1),
(1393211752245, '', '', '', '03:51:45', 't3', '2014-02-24 03:52:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_order_details`
--

CREATE TABLE IF NOT EXISTS `pfi_order_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `oid` bigint(20) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `grp` bigint(20) NOT NULL,
  `type` int(1) NOT NULL,
  `seq` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

--
-- Dumping data for table `pfi_order_details`
--

INSERT INTO `pfi_order_details` (`id`, `oid`, `pid`, `grp`, `type`, `seq`, `status`, `date`) VALUES
(40, 1392830666452, 1392830144897, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(39, 1392830666452, 1388415325688, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(38, 1392830666452, 1388417885290, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(37, 1392830666452, 1392830144897, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(36, 1392830666452, 1388415325688, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(35, 1392830666452, 1388404114362, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(34, 1392830666452, 1392830144897, 1392830689698, 3, 1, 3, '2014-02-19 17:25:03'),
(33, 1392830666452, 1392830446458, 1392830689698, 2, 1, 2, '2014-02-19 17:25:03'),
(32, 1392829669129, 1388415325688, 1392829706570, 1, 1, 1, '2014-02-19 17:08:45'),
(31, 1392829669129, 1388417885290, 1392829705455, 1, 1, 1, '2014-02-19 17:08:45'),
(30, 1392829608775, 1388404114362, 1392829611076, 1, 1, 3, '2014-02-19 17:07:14'),
(41, 1392829669129, 1392830144897, 1392832569005, 1, 1, 1, '2014-02-19 18:06:44'),
(42, 1392829669129, 1392830446458, 1392832602568, 2, 1, 2, '2014-02-19 18:06:44'),
(43, 1392829669129, 1392830144897, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(44, 1392829669129, 1388415325688, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(45, 1392829669129, 1388404114362, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(46, 1392829669129, 1388415325688, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(47, 1392829669129, 1392830144897, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(48, 1392829669129, 1392830144897, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(49, 1392829669129, 1392830144897, 1392832602568, 3, 1, 3, '2014-02-19 18:06:44'),
(50, 1392833291790, 1392830446458, 1392833305757, 2, 1, 2, '2014-02-19 18:08:33'),
(51, 1392833291790, 1392830144897, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(52, 1392833291790, 1388415325688, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(53, 1392833291790, 1388404114362, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(54, 1392833291790, 1388415325688, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(55, 1392833291790, 1392830144897, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(56, 1392833291790, 1392830144897, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(57, 1392833291790, 1392830144897, 1392833305757, 3, 1, 3, '2014-02-19 18:08:33'),
(58, 1392833441120, 1392830446458, 1392833454728, 2, 1, 1, '2014-02-19 18:11:01'),
(59, 1392833441120, 1392830144897, 1392833454728, 3, 1, 1, '2014-02-19 18:11:01'),
(60, 1392833441120, 1388415325688, 1392833454728, 3, 2, 1, '2014-02-19 18:11:01'),
(61, 1392833441120, 1388404114362, 1392833454728, 3, 3, 1, '2014-02-19 18:11:01'),
(62, 1392833441120, 1392830144897, 1392833454728, 3, 4, 1, '2014-02-19 18:11:01'),
(63, 1392833441120, 1392830144897, 1392833454728, 3, 5, 1, '2014-02-19 18:11:01'),
(64, 1392833441120, 1388415325688, 1392833454728, 3, 6, 1, '2014-02-19 18:11:01'),
(65, 1392833441120, 1392830144897, 1392833454728, 3, 7, 1, '2014-02-19 18:11:01'),
(66, 1392864999888, 1392830446458, 1392865068337, 2, 1, 0, '2014-02-20 02:59:34'),
(67, 1392864999888, 1392830144897, 1392865068337, 3, 1, 0, '2014-02-20 02:59:34'),
(68, 1392864999888, 1388404114362, 1392865068337, 3, 2, 0, '2014-02-20 02:59:34'),
(69, 1392864999888, 1388415325688, 1392865068337, 3, 3, 0, '2014-02-20 02:59:34'),
(70, 1392864999888, 1388415325688, 1392865068337, 3, 4, 0, '2014-02-20 02:59:34'),
(71, 1392864999888, 1392830144897, 1392865068337, 3, 5, 1, '2014-02-20 02:59:34'),
(72, 1392864999888, 1392830144897, 1392865068337, 3, 6, 0, '2014-02-20 02:59:34'),
(73, 1392864999888, 1392830144897, 1392865068337, 3, 7, 0, '2014-02-20 02:59:34'),
(74, 1392877655583, 1392830446458, 1392877799393, 2, 1, 0, '2014-02-20 06:30:15'),
(75, 1392877655583, 1392830144897, 1392877799393, 3, 1, 0, '2014-02-20 06:30:15'),
(76, 1392877655583, 1388404114362, 1392877799393, 3, 2, 0, '2014-02-20 06:30:15'),
(77, 1392877655583, 1388415325688, 1392877799393, 3, 3, 0, '2014-02-20 06:30:15'),
(78, 1392877655583, 1388415325688, 1392877799393, 3, 4, 0, '2014-02-20 06:30:15'),
(79, 1392877655583, 1388415325688, 1392877799393, 3, 5, 0, '2014-02-20 06:30:15'),
(80, 1392877655583, 1388417885290, 1392877799393, 3, 6, 0, '2014-02-20 06:30:15'),
(81, 1392877655583, 1392830144897, 1392877799393, 3, 7, 0, '2014-02-20 06:30:15'),
(82, 1392896588902, 1392882411260, 1392896606132, 1, 1, 3, '2014-02-20 11:43:53'),
(83, 1392896588902, 1392884450728, 1392896615943, 1, 1, 3, '2014-02-20 11:43:53'),
(84, 1392896588902, 1392893754219, 1392896629316, 1, 1, 3, '2014-02-20 11:43:53'),
(85, 1392897424140, 1392894180818, 1392897488718, 2, 1, 2, '2014-02-20 11:58:23'),
(86, 1392897424140, 1392893172754, 1392897500434, 2, 1, 2, '2014-02-20 11:58:23'),
(87, 1392897424140, 1392894123467, 1392897488718, 3, 1, 3, '2014-02-20 11:58:23'),
(88, 1392897424140, 1392894123467, 1392897488718, 3, 2, 3, '2014-02-20 11:58:23'),
(89, 1392897424140, 1392894123467, 1392897488718, 3, 3, 3, '2014-02-20 11:58:23'),
(90, 1392897424140, 1392894123467, 1392897488718, 3, 4, 3, '2014-02-20 11:58:23'),
(91, 1392897424140, 1392894123467, 1392897488718, 3, 5, 3, '2014-02-20 11:58:23'),
(92, 1392897424140, 1392892809367, 1392897500434, 3, 6, 2, '2014-02-20 11:58:23'),
(93, 1392897424140, 1392884565340, 1392897500434, 3, 7, 3, '2014-02-20 11:58:23'),
(94, 1392897424140, 1392884565340, 1392897500434, 3, 8, 3, '2014-02-20 11:58:23'),
(95, 1392897909410, 1392882288702, 1392897927925, 1, 1, 1, '2014-02-20 12:05:36'),
(96, 1392897909410, 1392882391856, 1392897932923, 1, 1, 1, '2014-02-20 12:05:36'),
(97, 1392897909410, 1392883067268, 1392897934538, 1, 1, 1, '2014-02-20 12:05:36'),
(98, 1392905911324, 1392893325678, 1392906623988, 2, 1, 2, '2014-02-20 14:30:28'),
(99, 1392905911324, 1392884654226, 1392906623988, 3, 1, 3, '2014-02-20 14:30:28'),
(100, 1392974426819, 1392882391856, 1392974456677, 1, 1, 3, '2014-02-21 09:21:01'),
(101, 1392974426819, 1392883067268, 1392974458137, 1, 1, 1, '2014-02-21 09:21:01'),
(102, 1392974426819, 1392882071366, 1392974459019, 1, 1, 1, '2014-02-21 09:21:01'),
(103, 1392974463886, 1392892975568, 1392974519225, 2, 1, 1, '2014-02-21 09:22:02'),
(104, 1392974463886, 1392882334776, 1392974519225, 3, 1, 1, '2014-02-21 09:22:02'),
(105, 1392974463886, 1392894123467, 1392974519225, 3, 2, 1, '2014-02-21 09:22:02'),
(106, 1392974463886, 1392894123467, 1392974519225, 3, 3, 1, '2014-02-21 09:22:02'),
(107, 1392974463886, 1392894123467, 1392974519225, 3, 4, 1, '2014-02-21 09:22:02'),
(108, 1392974463886, 1392894123467, 1392974519225, 3, 5, 1, '2014-02-21 09:22:02'),
(109, 1392974463886, 1392894123467, 1392974519225, 3, 6, 1, '2014-02-21 09:22:02'),
(110, 1392995711123, 1392893325678, 1392997328842, 2, 1, 2, '2014-02-21 15:42:21'),
(111, 1392995711123, 1392997068767, 1392997328842, 3, 1, 3, '2014-02-21 15:42:21'),
(112, 1392995711123, 1392884670374, 1392997328842, 3, 2, 3, '2014-02-21 15:42:21'),
(113, 1393045645640, 1392886612998, 1393045698783, 2, 1, 1, '2014-02-22 05:08:48'),
(114, 1393045645640, 1392882334776, 1393045698783, 3, 1, 1, '2014-02-22 05:08:48'),
(115, 1393045645640, 1392883240837, 1393045698783, 3, 2, 1, '2014-02-22 05:08:48'),
(116, 1393045645640, 1392883196910, 1393045698783, 3, 3, 1, '2014-02-22 05:08:48'),
(117, 1393045645640, 1392893460756, 1393045698783, 3, 4, 1, '2014-02-22 05:08:48'),
(118, 1393060226410, 1392883450394, 1393060250867, 1, 1, 1, '2014-02-22 09:11:26'),
(119, 1393060226410, 1392886725126, 1393060279439, 2, 1, 1, '2014-02-22 09:11:26'),
(120, 1393060226410, 1392882334776, 1393060279439, 3, 1, 1, '2014-02-22 09:11:26'),
(121, 1393060226410, 1392882366652, 1393060279439, 3, 2, 1, '2014-02-22 09:11:26'),
(122, 1393060226410, 1392883240837, 1393060279439, 3, 3, 1, '2014-02-22 09:11:26'),
(123, 1393060226410, 1392883240837, 1393060279439, 3, 4, 1, '2014-02-22 09:11:26'),
(124, 1393060226410, 1392883311117, 1393060279439, 3, 5, 1, '2014-02-22 09:11:26'),
(125, 1393060226410, 1392893589953, 1393060279439, 3, 6, 1, '2014-02-22 09:11:26'),
(126, 1393106578269, 1392883450394, 1393106745758, 1, 1, 1, '2014-02-22 22:06:29'),
(127, 1393106578269, 1392886725126, 1393106683397, 2, 1, 1, '2014-02-22 22:06:29'),
(128, 1393106578269, 1392892975568, 1393106713274, 2, 1, 1, '2014-02-22 22:06:29'),
(129, 1393106578269, 1392892934562, 1393106774560, 2, 1, 1, '2014-02-22 22:06:29'),
(130, 1393106578269, 1392882334776, 1393106683397, 3, 1, 1, '2014-02-22 22:06:29'),
(131, 1393106578269, 1392882366652, 1393106683397, 3, 2, 1, '2014-02-22 22:06:29'),
(132, 1393106578269, 1392883240837, 1393106683397, 3, 3, 1, '2014-02-22 22:06:29'),
(133, 1393106578269, 1392883311117, 1393106683397, 3, 4, 1, '2014-02-22 22:06:29'),
(134, 1393106578269, 1392883311117, 1393106683397, 3, 5, 1, '2014-02-22 22:06:29'),
(135, 1393106578269, 1392893460756, 1393106683397, 3, 6, 1, '2014-02-22 22:06:29'),
(136, 1393106578269, 1392882334776, 1393106713274, 3, 7, 1, '2014-02-22 22:06:29'),
(137, 1393106578269, 1392894123467, 1393106713274, 3, 8, 1, '2014-02-22 22:06:29'),
(138, 1393106578269, 1392894123467, 1393106713274, 3, 9, 1, '2014-02-22 22:06:29'),
(139, 1393106578269, 1392894123467, 1393106713274, 3, 10, 1, '2014-02-22 22:06:29'),
(140, 1393106578269, 1392894123467, 1393106713274, 3, 11, 1, '2014-02-22 22:06:29'),
(141, 1393106578269, 1392894123467, 1393106713274, 3, 12, 1, '2014-02-22 22:06:29'),
(142, 1393106578269, 1392882366652, 1393106774560, 3, 13, 1, '2014-02-22 22:06:29'),
(143, 1393106578269, 1392893460756, 1393106774560, 3, 14, 1, '2014-02-22 22:06:29'),
(144, 1393211752245, 1392886725126, 1393213934224, 2, 1, 1, '2014-02-24 03:52:40'),
(145, 1393211752245, 1392882334776, 1393213934224, 3, 1, 1, '2014-02-24 03:52:40'),
(146, 1393211752245, 1392882334776, 1393213934224, 3, 2, 1, '2014-02-24 03:52:40'),
(147, 1393211752245, 1392883240837, 1393213934224, 3, 3, 1, '2014-02-24 03:52:40'),
(148, 1393211752245, 1392883196910, 1393213934224, 3, 4, 1, '2014-02-24 03:52:40'),
(149, 1393211752245, 1392883196910, 1393213934224, 3, 5, 1, '2014-02-24 03:52:40'),
(150, 1393211752245, 1392893460756, 1393213934224, 3, 6, 1, '2014-02-24 03:52:40'),
(151, 1393106578269, 1392883067268, 1393235465230, 1, 1, 1, '2014-02-24 09:51:10'),
(152, 1393106578269, 1392882391856, 1393235465609, 1, 1, 1, '2014-02-24 09:51:10'),
(153, 1393106578269, 1392882288702, 1393235467563, 1, 1, 1, '2014-02-24 09:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_packages`
--

CREATE TABLE IF NOT EXISTS `pfi_packages` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(50) NOT NULL,
  `cost` float NOT NULL,
  `image` varchar(200) NOT NULL,
  `cid` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_packages`
--

INSERT INTO `pfi_packages` (`id`, `name`, `price`, `cost`, `image`, `cid`, `status`) VALUES
(1392886725126, 'Barkada Pack', '899', 0, '1392971068453.jpg', 8888, 1),
(1392886612998, 'Family Pack', '479', 0, '1392971073358.jpg', 8888, 1),
(1392892934562, 'Pizza n'' a Liter', '250', 0, '1392971078671.jpg', 8888, 1),
(1392892975568, 'Pizza n'' a Bucket', '399', 0, '1392971140420.jpg', 8888, 1),
(1392893047297, 'Ribs (Full Slab)', '560', 0, '1392970463612.jpg', 1378427740771, 1),
(1392893119581, 'Ribs (Half Slab)', '300', 0, '1392970469376.jpg', 1378427740771, 1),
(1392893172754, 'Chicken Half', '210', 0, '1392966375532.jpg', 1378427740771, 1),
(1392893272391, 'Chicken Quarter', '135', 0, '', 1378427740771, 1),
(1392893325678, 'Lengua', '150', 0, '', 1378427740771, 1),
(1392894180818, 'Bucket', '200', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_package_details`
--

CREATE TABLE IF NOT EXISTS `pfi_package_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `pkid` bigint(20) NOT NULL,
  `cost` float NOT NULL,
  `steps` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=570 ;

--
-- Dumping data for table `pfi_package_details`
--

INSERT INTO `pfi_package_details` (`id`, `pid`, `pkid`, `cost`, `steps`) VALUES
(168, 1388415325688, 1392717004406, 0, 2),
(167, 1388417885290, 1392717004406, 0, 2),
(166, 1388404114362, 1392717004406, 0, 1),
(191, 1392830144897, 1392830446458, 0, 4),
(190, 1388415325688, 1392830446458, 0, 3),
(189, 1388417885290, 1392830446458, 0, 3),
(188, 1392830144897, 1392830446458, 0, 3),
(187, 1392830332109, 1392830446458, 0, 3),
(186, 1388415325688, 1392830446458, 0, 2),
(185, 1388404114362, 1392830446458, 0, 2),
(184, 1392830144897, 1392830446458, 0, 1),
(192, 1392830144897, 1392830446458, 0, 3),
(193, 1388415325688, 1392830446458, 0, 3),
(495, 1392883196910, 1392886612998, 0, 2),
(494, 1392883196910, 1392886612998, 0, 2),
(493, 1392883240837, 1392886612998, 0, 2),
(492, 1392883240837, 1392886612998, 0, 2),
(491, 1392883252422, 1392886612998, 0, 2),
(490, 1392883252422, 1392886612998, 0, 2),
(489, 1392882334776, 1392886612998, 0, 1),
(488, 1392882366652, 1392886612998, 0, 1),
(516, 1392883328113, 1392886725126, 0, 2),
(515, 1392883196910, 1392886725126, 0, 2),
(514, 1392883196910, 1392886725126, 0, 2),
(513, 1392883196910, 1392886725126, 0, 2),
(512, 1392883240837, 1392886725126, 0, 2),
(511, 1392883240837, 1392886725126, 0, 2),
(510, 1392883240837, 1392886725126, 0, 2),
(509, 1392883252422, 1392886725126, 0, 2),
(508, 1392883252422, 1392886725126, 0, 2),
(507, 1392883328113, 1392886725126, 0, 2),
(506, 1392883328113, 1392886725126, 0, 2),
(505, 1392883311117, 1392886725126, 0, 2),
(504, 1392883311117, 1392886725126, 0, 2),
(503, 1392883311117, 1392886725126, 0, 2),
(502, 1392883252422, 1392886725126, 0, 2),
(501, 1392882334776, 1392886725126, 0, 1),
(500, 1392882334776, 1392886725126, 0, 1),
(499, 1392882366652, 1392886725126, 0, 1),
(498, 1392882366652, 1392886725126, 0, 1),
(485, 1392882366652, 1392892934562, 0, 1),
(484, 1392882334776, 1392892934562, 0, 1),
(468, 1392882366652, 1392892975568, 0, 1),
(467, 1392882334776, 1392892975568, 0, 1),
(470, 1392894123467, 1392892975568, 0, 2),
(539, 1392884670374, 1392893047297, 0, 2),
(538, 1392884565340, 1392893047297, 0, 2),
(537, 1392884565340, 1392893047297, 0, 2),
(536, 1392884565340, 1392893047297, 0, 2),
(535, 1392884632792, 1392893047297, 0, 2),
(534, 1392884632792, 1392893047297, 0, 2),
(533, 1392884632792, 1392893047297, 0, 2),
(532, 1392884654226, 1392893047297, 0, 2),
(531, 1392884654226, 1392893047297, 0, 2),
(530, 1392884654226, 1392893047297, 0, 2),
(529, 1392884670374, 1392893047297, 0, 2),
(528, 1392884670374, 1392893047297, 0, 2),
(469, 1392894123467, 1392892975568, 0, 2),
(548, 1392884670374, 1392893119581, 0, 2),
(547, 1392884670374, 1392893119581, 0, 2),
(546, 1392884654226, 1392893119581, 0, 2),
(545, 1392884654226, 1392893119581, 0, 2),
(544, 1392884632792, 1392893119581, 0, 2),
(543, 1392884632792, 1392893119581, 0, 2),
(542, 1392884565340, 1392893119581, 0, 2),
(541, 1392884565340, 1392893119581, 0, 2),
(471, 1392894123467, 1392892975568, 0, 2),
(557, 1392884670374, 1392893172754, 0, 2),
(556, 1392884670374, 1392893172754, 0, 2),
(555, 1392884654226, 1392893172754, 0, 2),
(554, 1392884654226, 1392893172754, 0, 2),
(553, 1392884632792, 1392893172754, 0, 2),
(552, 1392884632792, 1392893172754, 0, 2),
(551, 1392884565340, 1392893172754, 0, 2),
(550, 1392884565340, 1392893172754, 0, 2),
(563, 1393304061187, 1392893272391, 0, 1),
(562, 1392884565340, 1392893272391, 0, 2),
(561, 1392884632792, 1392893272391, 0, 2),
(560, 1392884654226, 1392893272391, 0, 2),
(559, 1392884670374, 1392893272391, 0, 2),
(472, 1392894123467, 1392892975568, 0, 2),
(568, 1392884565340, 1392893325678, 0, 2),
(567, 1392884632792, 1392893325678, 0, 2),
(566, 1392884654226, 1392893325678, 0, 2),
(565, 1392884670374, 1392893325678, 0, 2),
(276, 1392894004755, 1392894180818, 0, 1),
(277, 1392894004755, 1392894180818, 0, 1),
(278, 1392894004755, 1392894180818, 0, 1),
(279, 1392894004755, 1392894180818, 0, 1),
(280, 1392894004755, 1392894180818, 0, 1),
(281, 1392894123467, 1392894180818, 0, 1),
(282, 1392894123467, 1392894180818, 0, 1),
(283, 1392894123467, 1392894180818, 0, 1),
(284, 1392894123467, 1392894180818, 0, 1),
(285, 1392894123467, 1392894180818, 0, 1),
(286, 1392894143720, 1392894180818, 0, 1),
(287, 1392894143720, 1392894180818, 0, 1),
(288, 1392894143720, 1392894180818, 0, 1),
(289, 1392894143720, 1392894180818, 0, 1),
(290, 1392894143720, 1392894180818, 0, 1),
(473, 1392894123467, 1392892975568, 0, 2),
(474, 1392894004755, 1392892975568, 0, 2),
(475, 1392894004755, 1392892975568, 0, 2),
(476, 1392894004755, 1392892975568, 0, 2),
(477, 1392894004755, 1392892975568, 0, 2),
(478, 1392894004755, 1392892975568, 0, 2),
(479, 1392894143720, 1392892975568, 0, 2),
(480, 1392894143720, 1392892975568, 0, 2),
(481, 1392894143720, 1392892975568, 0, 2),
(482, 1392894143720, 1392892975568, 0, 2),
(483, 1392894143720, 1392892975568, 0, 2),
(486, 1392893460756, 1392892934562, 0, 2),
(487, 1392893589953, 1392892934562, 0, 2),
(496, 1392893460756, 1392886612998, 0, 3),
(497, 1392893589953, 1392886612998, 0, 3),
(517, 1392893460756, 1392886725126, 0, 3),
(518, 1392893589953, 1392886725126, 0, 3),
(564, 1392997068767, 1392893325678, 0, 1),
(540, 1393303941198, 1392893047297, 0, 1),
(549, 1393304004188, 1392893119581, 0, 1),
(558, 1393304032724, 1392893172754, 0, 1),
(569, 1393304077815, 1392893325678, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_package_group_details`
--

CREATE TABLE IF NOT EXISTS `pfi_package_group_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pkid` bigint(20) NOT NULL,
  `sid` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=138 ;

--
-- Dumping data for table `pfi_package_group_details`
--

INSERT INTO `pfi_package_group_details` (`id`, `pkid`, `sid`, `qty`) VALUES
(23, 1392717004406, 2, 1),
(22, 1392717004406, 1, 1),
(35, 1392830446458, 4, 1),
(34, 1392830446458, 3, 3),
(33, 1392830446458, 2, 2),
(32, 1392830446458, 1, 1),
(120, 1392886612998, 3, 1),
(119, 1392886612998, 2, 2),
(118, 1392886612998, 1, 1),
(123, 1392886725126, 3, 1),
(122, 1392886725126, 2, 3),
(121, 1392886725126, 1, 2),
(117, 1392892934562, 2, 1),
(116, 1392892934562, 1, 1),
(115, 1392892975568, 2, 5),
(114, 1392892975568, 1, 1),
(129, 1392893047297, 2, 3),
(128, 1392893047297, 1, 1),
(131, 1392893119581, 2, 2),
(130, 1392893119581, 1, 1),
(133, 1392893172754, 2, 2),
(132, 1392893172754, 1, 1),
(135, 1392893272391, 2, 1),
(134, 1392893272391, 1, 1),
(137, 1392893325678, 2, 1),
(136, 1392893325678, 1, 1),
(61, 1392894180818, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_plugins`
--

CREATE TABLE IF NOT EXISTS `pfi_plugins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` bigint(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `pages` varchar(250) DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `refid` bigint(20) NOT NULL,
  `content_src_id` bigint(20) NOT NULL,
  `content_src` varchar(50) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1379338564718 ;

--
-- Dumping data for table `pfi_plugins`
--

INSERT INTO `pfi_plugins` (`id`, `sid`, `title`, `pages`, `position`, `folder`, `tag`, `refid`, `content_src_id`, `content_src`, `order`, `status`) VALUES
(1, 1280058467516, 'Main Navigator', '|1375431606858|1375431776288|1378429819985|1375430523370|1373357131164|1373279043917|1373339945105|5|1311585874756|1309324505791|1309324415110|1300279513141|', '1', 'plug_navigator', '', 1, 0, '0', 0, 1),
(1309339877463, 1309339877465, 'Index Plugin', '|1373357131164|1373279043917|1373339945105|8|7|6|1311585874756|1309324505791|4|1309324415110|1300279513141|', '5', 'plug_index', 'plugin-1309339877463', 0, 0, '0', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_positions`
--

CREATE TABLE IF NOT EXISTS `pfi_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `pfi_positions`
--

INSERT INTO `pfi_positions` (`id`, `position`) VALUES
(1, 'navigator'),
(2, 'header'),
(3, 'top'),
(4, 'content_top'),
(5, 'content'),
(6, 'content_bottom'),
(7, 'right'),
(8, 'bottom'),
(9, 'footer'),
(10, 'footer2'),
(11, 'left'),
(12, 'breadcrumb'),
(13, 'head');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_products`
--

CREATE TABLE IF NOT EXISTS `pfi_products` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `cid` bigint(20) NOT NULL,
  `scid` bigint(20) NOT NULL,
  `price` float NOT NULL,
  `cost` float NOT NULL,
  `stocks` int(11) NOT NULL,
  `addon` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_products`
--

INSERT INTO `pfi_products` (`id`, `name`, `image`, `cid`, `scid`, `price`, `cost`, `stocks`, `addon`, `status`) VALUES
(1392882391856, 'Bacon Cheeseburger 12&quot;', '1392968524935.jpg', 1378427683319, 0, 289, 0, 0, 0, 1),
(1388555918604, '12&quot; Dough', '', 9999, 0, 8, 3, 20, 1, 1),
(1392700879702, 'sampl2', '', 9999, 0, 200, 188.15, 2, 0, 1),
(1392882366652, 'Pepperoni &amp; Cheese 12&quot;', '1392967934837.jpg', 1378427683319, 0, 229, 0, 0, 0, 1),
(1392882288702, 'Bacon Cheeseburger 10&quot;', '1392968522179.jpg', 1378427683319, 0, 235, 0, 0, 0, 1),
(1392882334776, 'Classic Hawaiian 12&quot;', '1392969152571.jpg', 1378427683319, 0, 229, 0, 0, 0, 1),
(1392830402998, 'test', '', 9999, 0, 300, 62.45, 2, 0, 1),
(1392882190103, 'Pepperoni &amp; Cheese 10&quot;', '1392967939991.jpg', 1378427683319, 0, 189, 0, 0, 0, 1),
(1392882071366, 'Classic Hawaiian 10&quot;', '1392969158582.jpg', 1378427683319, 0, 189, 0, 0, 0, 1),
(1392882411260, 'Uncle Ton''s Special 12&quot;', '1392967819657.jpg', 1378427683319, 0, 379, 0, 0, 0, 1),
(1392882458359, 'Seafood Special 12&quot;', '', 1378427683319, 0, 399, 0, 0, 0, 1),
(1392882982415, 'Lengua Special 12&quot;', '', 1378427683319, 0, 429, 0, 0, 0, 1),
(1392883018457, 'Classic Hawaiian 14&quot;', '1392969165900.jpg', 1378427683319, 0, 270, 0, 0, 0, 1),
(1392883048240, 'Pepperoni &amp; Cheese 14&quot;', '1392967945388.jpg', 1378427683319, 0, 270, 0, 0, 0, 1),
(1392883067268, 'Bacon Cheeseburger 14&quot;', '1392968520879.jpg', 1378427683319, 0, 340, 0, 0, 0, 1),
(1392883137447, 'Uncle Ton''s Special 14&quot;', '1392967808565.jpg', 1378427683319, 0, 440, 0, 0, 0, 1),
(1392883161201, 'Seafood Special 14&quot;', '', 1378427683319, 0, 465, 0, 0, 0, 1),
(1392883196910, 'Meatball Spaghetti', '1392967606109.jpg', 1378427715353, 0, 129, 0, 0, 0, 1),
(1392883240837, 'Creamy Boscaiola', '1392967614211.jpg', 1378427715353, 0, 129, 0, 0, 0, 1),
(1392883252422, 'Shrimp &amp; Ham Cream', '', 1378427715353, 0, 129, 0, 0, 0, 1),
(1392883311117, 'Lasagna', '1392968229610.jpg', 1378427715353, 0, 149, 0, 0, 0, 1),
(1392883328113, 'Seafood Marinara', '1392969172400.jpg', 1378427715353, 0, 175, 0, 0, 0, 1),
(1392883410331, 'Lasagna Tray (Family Size)', '1392965046268.jpg', 1378427715353, 0, 599, 0, 0, 0, 1),
(1392883450394, 'Lasagna Tray (Barkada Size)', '1392965054707.jpg', 1378427715353, 0, 799, 0, 0, 0, 1),
(1392884417489, 'Macaroni Salad', '1392966455218.jpg', 1378428102254, 0, 85, 0, 0, 0, 1),
(1392884450728, 'Chicken &amp; Chips', '1392966441777.jpg', 1378428102254, 0, 99, 0, 0, 0, 1),
(1392884478760, 'French Fries', '1392966448815.jpg', 1378428102254, 0, 39, 0, 0, 0, 1),
(1392884521649, 'Italian Bread (Whole)', '1392969180460.jpg', 1378428102254, 0, 45, 0, 0, 0, 1),
(1392884565340, 'Italian Bread (Sides)', '1392969186656.jpg', 1378428102254, 0, 20, 0, 0, 0, 1),
(1392884612204, 'Italian Bread (Piece)', '1392969193499.jpg', 1378428102254, 0, 5, 0, 0, 0, 1),
(1392884632792, 'Mashed Potato', '1392965762561.jpg', 1378428102254, 0, 20, 0, 0, 0, 1),
(1392884654226, 'Rice', '1392996050149.JPG', 1378428102254, 0, 10, 0, 0, 0, 1),
(1392884670374, 'Mixed Vegetables', '1392965755779.jpg', 1378428102254, 0, 20, 0, 0, 0, 1),
(1392893419211, 'Coke Regular', '1392975447843.jpg', 1378428102253, 0, 25, 0, 0, 0, 1),
(1392893442943, 'Coke (Can)', '1392974460258.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893460756, 'Coke 1.5 L', '1392974465573.jpg', 1378428102253, 0, 65, 0, 0, 0, 1),
(1392893483444, 'Coke Zero (Can)', '1392974472808.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893515308, 'Sprite Regular', '1392975455542.jpg', 1378428102253, 0, 25, 0, 0, 0, 1),
(1392893569343, 'Sprite (Can)', '1392974500144.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893589953, 'Sprite 1.5 L', '1392974507706.jpg', 1378428102253, 0, 65, 0, 0, 0, 1),
(1392893615721, 'Royal (Can)', '1392975461793.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893633492, 'Coke (Bottomless)', '', 1378428102253, 0, 55, 0, 0, 0, 1),
(1392893665584, 'Sprite (Bottomless)', '', 1378428102253, 0, 55, 0, 0, 0, 1),
(1392893728613, 'Iced Tea (Single)', '', 1378428102253, 0, 25, 0, 0, 0, 1),
(1392893754219, 'Iced Tea (Bottomless)', '', 1378428102253, 0, 55, 0, 0, 0, 1),
(1392893820437, 'Pineapple Juice', '1392975767687.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893838491, 'Orange Juice', '1392975773332.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893856198, 'Mango Juice', '1392975779325.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392893877785, 'Four Seasons Juice', '1392975786474.jpg', 1378428102253, 0, 35, 0, 0, 0, 1),
(1392894004755, 'San Mig Light', '1392894453540.jpg', 1378428102253, 0, 45, 0, 0, 0, 1),
(1392894123467, 'San Mig Apple', '1392894480739.png', 1378428102253, 0, 45, 0, 0, 0, 1),
(1392894143720, 'Tanduay Ice', '1392996478934.jpg', 1378428102253, 0, 45, 0, 0, 0, 1),
(1392972330662, 'Chicken Half', '', 9999, 0, 210, 0, 0, 0, 1),
(1392997068767, 'Lengua', '', 2222, 0, 150, 0, 0, 0, 1),
(1393303941198, 'Ribs (Full Slab)', '1393303998902.jpg', 2222, 0, 560, 0, 0, 0, 1),
(1393304004188, 'Ribs (Half Slab)', '1393304026104.jpg', 2222, 0, 300, 0, 0, 0, 1),
(1393304032724, 'Chicken Half', '1393304056804.jpg', 2222, 0, 210, 0, 0, 0, 1),
(1393304061187, 'Chicken Quarter', '', 2222, 0, 135, 0, 0, 0, 1),
(1393304077815, 'Lengua', '', 2222, 0, 150, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_product_addons`
--

CREATE TABLE IF NOT EXISTS `pfi_product_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `aid` bigint(20) NOT NULL,
  `cost` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `pfi_product_addons`
--

INSERT INTO `pfi_product_addons` (`id`, `pid`, `aid`, `cost`) VALUES
(28, 1388404114362, 1388555918604, 3),
(29, 1392865598175, 1388555918604, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_product_details`
--

CREATE TABLE IF NOT EXISTS `pfi_product_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `iid` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` float NOT NULL,
  `addon` int(1) NOT NULL DEFAULT '0',
  `addon_products` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Dumping data for table `pfi_product_details`
--

INSERT INTO `pfi_product_details` (`id`, `pid`, `iid`, `qty`, `cost`, `addon`, `addon_products`) VALUES
(12, 1388400054789, 1, 222, 0, 0, ''),
(123, 1388404114362, 1388447171672, 2, 4.9, 0, ''),
(120, 1388415325688, 1388403974323, 2, 8, 0, ''),
(121, 1388417885290, 1, 3, 4.5, 0, ''),
(112, 1388555918604, 1, 2, 3, 0, ''),
(122, 1388404114362, 1, 2, 3, 0, ''),
(113, 1392700879702, 1388403974323, 6, 24, 0, ''),
(114, 1392700879702, 1388447171672, 67, 164.15, 0, ''),
(119, 1388415325688, 1, 4, 6, 0, ''),
(131, 1392830144897, 1388447171672, 3, 7.35, 0, ''),
(130, 1392830144897, 1388403974323, 2, 8, 0, ''),
(133, 1392830332109, 1388447171672, 3, 7.35, 0, ''),
(132, 1392830332109, 1, 3, 4.5, 0, ''),
(128, 1392830402998, 1388447171672, 1, 2.45, 0, ''),
(129, 1392830402998, 1, 40, 60, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_profile`
--

CREATE TABLE IF NOT EXISTS `pfi_profile` (
  `uid` varchar(20) NOT NULL,
  `fname` varchar(200) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `bday` date NOT NULL,
  `gender` varchar(2) NOT NULL,
  `contact` mediumtext NOT NULL,
  `notification` varchar(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='pfi_profile';

--
-- Dumping data for table `pfi_profile`
--

INSERT INTO `pfi_profile` (`uid`, `fname`, `mname`, `lname`, `bday`, `gender`, `contact`, `notification`) VALUES
('1', 'Prime', '', 'Factors', '0000-00-00', 'M', '', ''),
('1311399176439', 'Jocelynne', '', 'Dela Cruz', '1990-09-18', 'F', '02-1234567', ''),
('1373270402110', 'Mon', '', 'Deang', '0000-00-00', 'M', '', ''),
('1385622275511', 'Test', '', 'test', '0000-00-00', '', '', ''),
('1388466741586', 'Pau', '', 'Yu', '0000-00-00', 'F', '', ''),
('1388467114554', 'Pauline', '', 'Yuchingtat', '0000-00-00', 'F', '', ''),
('1392885232705', 'Tab1', '', 'UTS', '0000-00-00', 'F', '', ''),
('1393292196495', 'Chef', '', '1', '0000-00-00', 'M', '', ''),
('1393292341460', 'Dining', '', '1', '0000-00-00', 'M', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_revenue`
--

CREATE TABLE IF NOT EXISTS `pfi_revenue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` bigint(20) NOT NULL,
  `cost` float NOT NULL,
  `subtotal` float NOT NULL,
  `total` float NOT NULL,
  `discount` float NOT NULL,
  `vat` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discount_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `pfi_revenue`
--

INSERT INTO `pfi_revenue` (`id`, `oid`, `cost`, `subtotal`, `total`, `discount`, `vat`, `date`, `discount_name`) VALUES
(32, 1392974463886, 0, 356.25, 399, 0, 42.75, '2014-02-25 03:58:45', ''),
(31, 1393211752245, 0, 802.68, 899, 0, 96.32, '2014-02-25 03:58:38', ''),
(30, 1392974426819, 0, 730.36, 818, 0, 87.64, '2014-02-25 03:58:33', ''),
(29, 1393106578269, 0, 2866.96, 3211, 0, 344.04, '2014-02-25 03:58:27', ''),
(28, 1393060226410, 0, 1394.79, 1562.16, 121.28, 167.37, '2014-02-22 09:12:17', 'Senior Citizen'),
(27, 1393045645640, 0, 393.46, 440.68, 34.22, 47.22, '2014-02-22 05:09:36', 'Senior Citizen'),
(26, 1392897909410, 0, 740.57, 829.44, 30.86, 88.87, '2014-02-20 14:48:37', 'Senior Citizen'),
(25, 1392833441120, 100.3, 415.84, 465.74, 29.7, 49.9, '2014-02-19 18:22:44', 'Senior Citizen');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_sections`
--

CREATE TABLE IF NOT EXISTS `pfi_sections` (
  `id` varchar(20) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `folder` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1',
  `content` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `padd` longtext NOT NULL,
  `pedit` longtext NOT NULL,
  `pdelete` longtext NOT NULL,
  `pview` longtext NOT NULL,
  `papprove` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_sections`
--

INSERT INTO `pfi_sections` (`id`, `uid`, `title`, `date`, `description`, `folder`, `type`, `tag`, `sequence`, `content`, `status`, `padd`, `pedit`, `pdelete`, `pview`, `papprove`) VALUES
('10', '1', 'Template Editor', 0, 'Template editor', 'tempeditor', 'staticpages', '', 1, 0, 1, '', '1', '', '1', ''),
('11', '1', 'User Management', 0, 'User and access manager', 'userman', 'systems', '', 1, 0, 1, '1,2', '1,2', '1,2', '1,2', ''),
('12', '1', 'User Access', 0, '', 'userpermission', 'systems', '', 1, 0, 1, '', '1', '', '1', ''),
('1280127299403', '1', 'Index Module', 1280127299, 'Home Page', 'index', 'modules', '', 1, 0, 1, '', '', '', '1,2,3,1387342794866,1387342803532', ''),
('13', '1', 'User Groups', 0, 'User Group Manager', 'usergroup', 'systems', '', 1, 0, 1, '1', '1', '1', '1', ''),
('1311323492866', '1', 'Login Module', 1311323492, 'Login Form', 'login', 'modules', '', 1, 0, 1, '', '', '', '1,2,3,1387342794866,1387342803532', ''),
('1311335505167', '1', 'Registration Module', 1311335505, 'Registration', 'registration', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('1378881929825', '1', 'Members', 1378881929, 'Members Posting', 'members', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1,2', '1'),
('1379297404166', '1', 'Press', 1379297404, 'Blog Posting', 'press', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('1379495024536', '1', 'Inventory', 1379495024, 'Product Inventory Records', 'inventory', 'modules', '', 1, 0, 1, '1,2,3,1387342803532', '1,2,3,1387342803532', '1,2,3,1387342803532', '1,2,3,1387342803532', '1,2,3,1387342803532'),
('1380126217566', '1', 'Search', 1380126217, 'Search Page Module', 'search', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('15', '1', 'Snippet', 0, 'Mushroom Manager', 'snippet', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('17', '1', 'User Logs', 0, 'User Logs', 'userlogs', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('18', '1', 'Templates Manager', 0, 'Templates installer', 'tempmanager', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', ''),
('20', '1', 'System Configuration', 123123, 'Global Configuration', 'system', 'modules', '', 1, 0, 1, '', '1', '', '1', ''),
('5', '1', 'Module and Plugins Installation', 0, 'Modules and Plug-ins installer', 'installation', 'staticpages', '', 1, 0, 1, '1', '', '', '', ''),
('6', '1', 'Link Manager', 0, 'Link navigator manager', 'links', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('7', '1', 'Modules', 0, 'Module manager', 'modules', 'staticpages', '', 1, 0, 1, '1', '1', '1', '1', '1'),
('8', '1', 'Plugins', 0, 'Plug-in manager', 'plugins', 'staticpages', '', 1, 0, 1, '', '', '1', '1', '1'),
('9', '1', 'Orders', 0, 'Staticpage Manager', 'orders', 'systems', '', 1, 0, 1, '1,2,3,1387342794866,1387342803532', '1,2,3,1387342794866,1387342803532', '1,2,3,1387342794866,1387342803532', '1,2,3,1387342794866,1387342803532', '1,1387342794866,1387342803532');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_settings`
--

CREATE TABLE IF NOT EXISTS `pfi_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `varid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `pfi_settings`
--

INSERT INTO `pfi_settings` (`id`, `name`, `value`, `varid`) VALUES
(1, 'Session Type', 'cookies', 'SESSION_TYPE'),
(2, 'Contact Email', 'raymund.deang@primefactors.ph', 'CONTACT_EMAIL'),
(3, 'Session Prefix', 'pfi_514_', 'SESSION_PREFIX'),
(4, 'Session Timeout', '604800', 'SESSION_TIMEOUT'),
(5, 'Authentication Method (email, username or mixed)', 'mixed', 'AUTHUSE'),
(6, 'Email Activation', '1', 'USER_ACTIVATION'),
(7, 'Root Directory', '/rms', 'ROOT_DIR'),
(8, 'MODRewrite', '1', 'MODREWRITE'),
(9, 'Global Meta Title', 'Uncle Tons - Restaurant Management System', 'META_TITLE'),
(10, 'Global Meta Keywords', 'Restaurant Management System', 'META_KEYWORDS'),
(11, 'Global Meta Description', 'Restaurant Management System', 'META_DESCRIPTION'),
(12, 'Cache Enabled', '0', 'CACHE_ENABLED'),
(13, 'Cache Expiration', '60', 'CACHE_TIME'),
(15, 'Cache Folder Path', 'cache/', 'CACHE_PATH'),
(16, 'Custom Variable 1', ' 12', 'TABLES'),
(17, 'Custom Variable 2', '', ''),
(18, 'Custom Variable 3', '', ''),
(19, 'Custom Variable 4', '', ''),
(20, 'Custom Variable 5', '', ''),
(21, 'Custom Variable 6', '', ''),
(22, 'Custom Variable 7', '', ''),
(23, 'Custom Variable 8', '', ''),
(24, 'Custom Variable 9', '', ''),
(25, 'Custom Variable 10', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_stocks_sale_log`
--

CREATE TABLE IF NOT EXISTS `pfi_stocks_sale_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(100) NOT NULL,
  `oid` bigint(20) NOT NULL,
  `iid` bigint(20) NOT NULL,
  `stocks` int(11) NOT NULL,
  `cost` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `pfi_stocks_sale_log`
--

INSERT INTO `pfi_stocks_sale_log` (`id`, `action`, `oid`, `iid`, `stocks`, `cost`, `date`) VALUES
(17, 'PREP', 0, 1388447171672, -2, 4.9, '2014-02-19 17:20:28'),
(16, 'PREP', 0, 1, -80, 120, '2014-02-19 17:20:28'),
(15, 'PREP', 0, 1388447171672, -30, 73.5, '2014-02-19 17:19:17'),
(14, 'PREP', 0, 1, -30, 45, '2014-02-19 17:19:17'),
(13, 'PREP', 0, 1388447171672, -30, 73.5, '2014-02-19 17:16:38'),
(12, 'PREP', 0, 1388403974323, -20, 80, '2014-02-19 17:16:38'),
(11, 'ADD', 0, 1392830023725, 1000, 0.3, '2014-02-19 17:14:05'),
(10, 'ADD', 0, 1392829835535, 1000, 300, '2014-02-19 17:10:59'),
(18, 'SOLD', 1392833441120, 1, -4, 6, '2014-02-19 18:22:44'),
(19, 'SOLD', 1392833441120, 1388403974323, -2, 8, '2014-02-19 18:22:44'),
(20, 'SOLD', 1392833441120, 1, -4, 6, '2014-02-19 18:22:44'),
(21, 'SOLD', 1392833441120, 1388403974323, -2, 8, '2014-02-19 18:22:44'),
(22, 'ADD', 0, 1392878059245, 1000, 0.899, '2014-02-20 06:35:02'),
(23, 'ADD', 0, 1392878059245, 1000, 0.7, '2014-02-20 06:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_templates`
--

CREATE TABLE IF NOT EXISTS `pfi_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `folder` varchar(200) NOT NULL,
  `isdefault` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pfi_templates`
--

INSERT INTO `pfi_templates` (`id`, `name`, `folder`, `isdefault`) VALUES
(1, 'cms_default', 'default', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pfi_unit_conversion`
--

CREATE TABLE IF NOT EXISTS `pfi_unit_conversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `pfi_unit_conversion`
--

INSERT INTO `pfi_unit_conversion` (`id`, `unit`, `name`) VALUES
(1, 'cm', '(cm) Centimeter'),
(2, 'in', '(in) Inch'),
(3, 'm', '(m) Meter'),
(4, 'ft', '(ft) Feet'),
(5, 'yd', '(yd) Yard'),
(6, 'g', '(g) Gram'),
(7, 'mg', '(mg) Milligram'),
(8, 'kg', '(kg) Kiloggram'),
(9, 'oz', '(oz) Ounce'),
(10, 'lb', '(lb) Pound'),
(11, 'ml', '(ml) Milliliter'),
(12, 'l', '(l) Liter'),
(13, 'fl', '(fl oz) Fluid Ounce'),
(14, 'qt', '(qt) Quart'),
(15, 'gal', '(gal) Gallon'),
(16, 'pt', '(pt) Pint'),
(17, 'pc', '(pc) Piece');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_usergroup`
--

CREATE TABLE IF NOT EXISTS `pfi_usergroup` (
  `id` varchar(20) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_usergroup`
--

INSERT INTO `pfi_usergroup` (`id`, `group_name`) VALUES
('1', 'Super Admin'),
('1387342794866', 'Dining'),
('1387342803532', 'Kitchen'),
('2', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `pfi_users`
--

CREATE TABLE IF NOT EXISTS `pfi_users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_create` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `login_time` varchar(20) NOT NULL,
  `login_attemps` int(11) NOT NULL,
  `said` varchar(50) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `activate` int(1) NOT NULL,
  `level` int(5) NOT NULL,
  `vid` varchar(50) NOT NULL,
  `browser` mediumtext NOT NULL,
  `ip` varchar(50) NOT NULL,
  `user_group` varchar(20) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pfi_users`
--

INSERT INTO `pfi_users` (`username`, `password`, `salt`, `email`, `date_create`, `last_login`, `login_time`, `login_attemps`, `said`, `uid`, `activate`, `level`, `vid`, `browser`, `ip`, `user_group`) VALUES
('admin', '83b67274682f52b37536e7f67d750e86', '$2a$05$q.ilj4qXB4Ciu9WSft4k8$', 'fortius@primefactors.ph', 1270884212, 1393302361, '0', 0, '1ff02dcb29e160e1141a683438066e18', '1', 1, 0, '', 'mozilla/5.0 (linux; android 4.1.2; b1-a71 build/jzo54k) applewebkit/537.36 (khtml, like gecko) chrome/33.0.1750.126 safari/537.36', '112.208.105.143', '1'),
('mondeang', '$2a$05$$2a$05$L5LTYYaW3xFpjN.rmasC2iM9xZwH/O3zquPkDIhfTX6aQW ', '$2a$05$L5LTYYaW3xFpjNIVGmMGX$', 'raymund.deang@primefactors.ph', 1373270453, 1387337747, '0', 0, '43a69087415f238a3db98d5a55ef31fa', '1373270402110', 1, 0, '3b665e4323eefd11f92525888b4a2a26', 'mozilla/5.0 (macintosh; intel mac os x 10_9_0) applewebkit/537.36 (khtml, like gecko) chrome/32.0.1700.55 safari/537.36', '::1', '2'),
('pln3123', '21f12c8ed366cb3baebb372adf939429', '$2a$05$q.ilj4qXB4Ciu9WSft4k8$', 'test@asfsad.com', 1388467168, 1393304343, '0', 0, '54c699b26ea68c595069757b6cc8aecc', '1388467114554', 1, 0, '6dd41212c47f9d0c22de4e7987050550', 'mozilla/5.0 (macintosh; intel mac os x 10_9_1) applewebkit/537.36 (khtml, like gecko) chrome/33.0.1750.117 safari/537.36', '125.212.122.234', '1'),
('tab00001', 'e273767f8c556b25b0abe4a9b9bbb465', '$2a$05$SlA.OWCbbXH6rJ.rOLWxJ$', 'tab1@gmail.com', 1392885308, 1392972961, '0', 0, '883d37615ad3b627743488ccbe5e4acf', '1392885232705', 1, 0, '9cb186ce4dacb819976b25700f9ae8fc', 'mozilla/5.0 (ipad; cpu os 7_0_4 like mac os x) applewebkit/537.51.1 (khtml, like gecko) version/7.0 mobile/11b554a safari/9537.53', '203.215.120.135', '1387342794866'),
('chef1', '608a0f351a923f210775e0f53bee00fe', '$2a$05$u7BJh1BO/1W.nL71vRg3F$', 'chef@uncletons.com', 1393292331, 1393300946, '0', 0, '7055ebe0711b5234aca559d7f923eea9', '1393292196495', 1, 0, '4c82127259b5464278076411b8139eae', 'mozilla/5.0 (macintosh; intel mac os x 10.9; rv:27.0) gecko/20100101 firefox/27.0', '112.208.105.143', '1387342803532'),
('dining1', '6272a8b73bfb4f5b92d1e305064d4225', '$2a$05$CqUzOxh52uFssgs19ev4U$', 'dining@uncletons.com', 1393292400, 1393302634, '0', 0, '7e001b92d6361700f04947990728ae92', '1393292341460', 1, 0, 'a61141ea488fc90b3cc87896e71b4123', 'mozilla/5.0 (linux; android 4.1.2; b1-a71 build/jzo54k) applewebkit/537.36 (khtml, like gecko) chrome/33.0.1750.126 safari/537.36', '112.208.105.143', '1387342794866');
