<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'portfolio' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '~j_1iZ.:QThisD?%nRS&vEj2NMvCl<B-}V,=i;r@7Z$Ogg;VY.w~hI]xfBare>yM' );
define( 'SECURE_AUTH_KEY',  't*v?:;sBm7BcL~g^Ol/bJXu+YUDMqc.O+z|xXDHFN74#TWM{{ZM*RNj*2Pcyw+]G' );
define( 'LOGGED_IN_KEY',    'g,ks^iE=M Sc5d9lJ{nvWboc]Dc]I*t&GAYTfWMdk_R4wHWOihUh^4F]Zp7~r.GY' );
define( 'NONCE_KEY',        '~PoEZ0LOhM8dh];5:OGrL7V>e7b|2?3iVaN?RB^6B|/0#bsAZ)nhA=2zxP4 EE/E' );
define( 'AUTH_SALT',        '29i&Ok$d-bNUsm/g-b;/eveLfoM%k;%CgpyEh SXgyZzoLR4R9$H|HxJ$uM!> VW' );
define( 'SECURE_AUTH_SALT', '9H48w4T/$0AxQ@LA;xZ#]5Hj|FEpLSyXb5&+j9K1:|L}s,q*/v@M&Od)0yke5vH|' );
define( 'LOGGED_IN_SALT',   '1K66lvJj$Km8V8UM$sGOhCFAOaPpT$!NayrB2rhiCa@(W3;E`o[}^M-tP*A$2!,5' );
define( 'NONCE_SALT',       '-;Pf)ZOkvAvPwit%H@CQuA&7&Jna1eO:+OD[wu^eY,fg_;sX4|*3C9E1(lHL6.ez' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
	ini_set('display_errors','Off');
	ini_set('error_reporting', E_ALL );
	define('WP_DEBUG', false);
	define('WP_DEBUG_DISPLAY', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
