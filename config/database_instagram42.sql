-- MySQL dump 10.13  Distrib 5.7.21, for osx10.12 (x86_64)
--
-- Host: localhost    Database: Instagram42
-- ------------------------------------------------------
-- Server version	5.7.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `content` varchar(3000) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1390 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1376,83,21,'Perfect bang','2018-06-02 08:08:51'),(1389,102,24,'Inception bridge ?','2018-10-24 21:52:42');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow`
--

DROP TABLE IF EXISTS `follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `following_id` int(10) unsigned NOT NULL,
  `follower_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `following_id` (`following_id`),
  KEY `follower_id` (`follower_id`),
  CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`),
  CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow`
--

LOCK TABLES `follow` WRITE;
/*!40000 ALTER TABLE `follow` DISABLE KEYS */;
INSERT INTO `follow` VALUES (35,21,24),(36,24,21);
/*!40000 ALTER TABLE `follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (21,83,'2018-06-01 15:15:03'),(24,83,'2018-06-01 15:58:04'),(24,96,'2018-06-02 09:03:10'),(24,102,'2018-10-24 21:52:35');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `receiver_id` int(10) unsigned NOT NULL,
  `read_notif` tinyint(1) NOT NULL DEFAULT '0',
  `content` enum('follow','taggued_post','liked','commented') NOT NULL,
  `post_id` int(10) unsigned DEFAULT NULL,
  `comment_id` int(10) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `post_id` (`post_id`),
  KEY `comment_id` (`comment_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `notifications_ibfk_4` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=380 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (84,24,21,1,'follow',NULL,NULL,'2018-05-25 17:14:07'),(85,24,21,1,'follow',NULL,NULL,'2018-05-25 17:14:09'),(273,21,24,1,'liked',83,NULL,'2018-05-31 11:58:26'),(274,21,24,0,'liked',83,NULL,'2018-05-31 11:58:29'),(306,21,24,0,'liked',83,NULL,'2018-05-31 12:33:48'),(309,21,24,0,'liked',83,NULL,'2018-05-31 12:36:47'),(311,21,24,0,'liked',83,NULL,'2018-05-31 13:34:53'),(312,21,24,0,'liked',83,NULL,'2018-05-31 13:34:54'),(313,21,24,0,'liked',83,NULL,'2018-05-31 13:34:55'),(314,21,24,0,'liked',83,NULL,'2018-05-31 13:34:56'),(315,21,24,0,'liked',83,NULL,'2018-05-31 13:41:39'),(316,21,24,0,'liked',83,NULL,'2018-05-31 13:41:40'),(317,21,24,0,'liked',83,NULL,'2018-05-31 13:41:41'),(318,21,24,0,'liked',83,NULL,'2018-05-31 13:41:41'),(319,21,24,0,'liked',83,NULL,'2018-05-31 13:41:42'),(320,21,24,0,'liked',83,NULL,'2018-05-31 13:41:49'),(321,21,24,0,'liked',83,NULL,'2018-05-31 13:41:50'),(322,21,24,0,'liked',83,NULL,'2018-05-31 13:41:51'),(323,21,24,0,'liked',83,NULL,'2018-05-31 13:41:51'),(324,21,24,0,'liked',83,NULL,'2018-05-31 13:41:54'),(325,21,24,0,'liked',83,NULL,'2018-05-31 13:41:55'),(326,21,24,0,'liked',83,NULL,'2018-05-31 13:48:44'),(327,21,24,0,'liked',83,NULL,'2018-05-31 13:48:46'),(328,21,24,0,'liked',83,NULL,'2018-05-31 13:48:46'),(329,21,24,0,'liked',83,NULL,'2018-05-31 13:48:52'),(330,21,24,0,'liked',83,NULL,'2018-05-31 13:48:52'),(331,21,24,1,'liked',83,NULL,'2018-05-31 13:57:25'),(332,21,24,0,'liked',83,NULL,'2018-05-31 13:57:40'),(333,21,24,0,'liked',83,NULL,'2018-05-31 13:57:52'),(334,21,24,0,'liked',83,NULL,'2018-05-31 13:59:10'),(335,21,24,0,'liked',83,NULL,'2018-05-31 14:02:41'),(336,21,24,1,'liked',83,NULL,'2018-05-31 14:04:01'),(337,21,24,1,'liked',83,NULL,'2018-05-31 14:04:08'),(338,21,24,1,'liked',83,NULL,'2018-05-31 14:04:19'),(339,21,24,1,'liked',83,NULL,'2018-05-31 14:04:23'),(340,21,24,1,'liked',83,NULL,'2018-05-31 14:04:39'),(341,21,24,1,'liked',83,NULL,'2018-05-31 14:06:06'),(346,21,24,1,'liked',83,NULL,'2018-05-31 14:42:15'),(358,21,24,1,'liked',83,NULL,'2018-06-01 12:22:53'),(359,21,24,1,'liked',83,NULL,'2018-06-01 13:16:50'),(360,21,24,1,'liked',83,NULL,'2018-06-01 15:15:03'),(361,24,21,0,'follow',NULL,NULL,'2018-06-01 18:32:10'),(362,24,21,1,'follow',NULL,NULL,'2018-06-01 18:32:12'),(363,24,21,0,'follow',NULL,NULL,'2018-06-01 18:32:17'),(370,21,24,1,'commented',83,1376,'2018-06-02 08:08:51'),(371,24,21,1,'follow',NULL,NULL,'2018-06-02 08:20:39'),(372,21,24,1,'follow',NULL,NULL,'2018-06-02 08:49:15'),(373,21,24,1,'taggued_post',96,NULL,'2018-06-02 08:50:12'),(375,24,21,1,'liked',96,NULL,'2018-06-02 09:03:10'),(378,24,21,0,'liked',102,NULL,'2018-10-24 21:52:35'),(379,24,21,0,'commented',102,1389,'2018-10-24 21:52:42');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `img` blob NOT NULL,
  `friend` varchar(100) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (83,24,'img/posts/83','Alice ','Yellow Blue','2018-05-30 22:44:41'),(94,21,'img/posts/94','Ugo ','Pink White','2018-06-02 08:07:36'),(95,24,'img/posts/95','Alice ','Pollution','2018-06-02 08:32:33'),(96,21,'img/posts/96','Ugo Alice ','Buttes Chaumont','2018-06-02 08:50:12'),(99,24,'img/posts/99','Alice ','Street bike','2018-06-02 09:02:47'),(101,21,'img/posts/101','Ugo Steeve','Earthcore Festival','2018-06-02 09:10:46'),(102,21,'img/posts/102','Ugo ','Papel Bridge','2018-06-02 09:12:41'),(104,24,'img/posts/104','Alice ','Blue sky','2018-06-02 09:17:13'),(108,21,'img/posts/114',NULL,'42','2018-10-24 19:54:10');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stickers`
--

DROP TABLE IF EXISTS `stickers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stickers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `img` blob NOT NULL,
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stickers`
--

LOCK TABLES `stickers` WRITE;
/*!40000 ALTER TABLE `stickers` DISABLE KEYS */;
INSERT INTO `stickers` VALUES (1,'gaz.png','img/stickers/gaz.png'),(2,'lunettes_bleues.png','img/stickers/lunettes_bleues.png'),(3,'bang.png','img/stickers/bang.png'),(6,'casa_de_papel.png','img/stickers/casa_de_papel.png'),(7,'anonymous.png','img/stickers/anonymous.png');
/*!40000 ALTER TABLE `stickers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `mdp` varchar(200) DEFAULT NULL,
  `mail` varchar(30) NOT NULL,
  `photo` blob,
  `notif_read` tinyint(1) NOT NULL DEFAULT '0',
  `cle` varchar(32) NOT NULL,
  `activate` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (21,'Ugo','2b689fd512021ee8914f06fa42187799a6db5e30554251ae8fc72ae3368e35c191e99f1e530e305ddd654b344048357016a299dc595f8fca28ce1e7a94a5599c','ugo@sharklasers.com','img/users/21',1,'4fdaf6984cc69be7f02b8941ecaf1140',1),(22,'Paco','2b689fd512021ee8914f06fa42187799a6db5e30554251ae8fc72ae3368e35c191e99f1e530e305ddd654b344048357016a299dc595f8fca28ce1e7a94a5599c','paco@sharklasers.com',NULL,0,'acc623bfe462e43f8db0c19712361ecf',1),(23,'Luca','2b689fd512021ee8914f06fa42187799a6db5e30554251ae8fc72ae3368e35c191e99f1e530e305ddd654b344048357016a299dc595f8fca28ce1e7a94a5599c','luca@sharklasers.com',NULL,0,'8f3dd9159f8ab124576458a3a19df1d8',1),(24,'Alice','2b689fd512021ee8914f06fa42187799a6db5e30554251ae8fc72ae3368e35c191e99f1e530e305ddd654b344048357016a299dc595f8fca28ce1e7a94a5599c','alice@sharklasers.com','img/users/24',0,'318510a4b9e2d8549343b27a004ab3c4',1),(25,'Alicea','2b689fd512021ee8914f06fa42187799a6db5e30554251ae8fc72ae3368e35c191e99f1e530e305ddd654b344048357016a299dc595f8fca28ce1e7a94a5599c','alicea@sharklasers.com',NULL,0,'f1e1ca909799c171832a28d9327fb927',1),(48,'Gautes','e9bc175fa7c087035491b35bf1e9f1fda5237efb9cab483205ab3fe0ca460dda300379f4e999f252872c24c58367d18dd5d54b1e3ea053ccc04f7d34570918a7','gauthier.grandamme@gmail.com','img/users/48',0,'8aa60bf0e78fb829c79366f2378f0df1',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-24 23:54:12
