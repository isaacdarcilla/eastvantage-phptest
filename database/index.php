<?php

/**
 * --------------------------------------------------------------------------
 *  PHP Autoloader
 * --------------------------------------------------------------------------
 * 
 *   We attempts to load the classes from the files database folder respectively.
 *   https://www.php.net/manual/en/language.oop5.autoload.php
 * 
 */
require_once 'database/autoload.php';
$load = new \Utils\ClassLoader();
$load->register();

use database\Http\Connection;
use database\Model\Comments;
use database\Model\News;

/**
 * --------------------------------------------------------------------------
 *  Configuration
 * --------------------------------------------------------------------------
 * 
 *   Load the configuration in the config ini and
 *   parse in the connection.
 * 
 */
$keys = parse_ini_file("database/config.ini");
Connection::makeConnection($keys, dirname(__FILE__));

/**
 * --------------------------------------------------------------------------
 *  Sample model queries
 * --------------------------------------------------------------------------
 */

// We fetch all the data in the `news` table.
$all = News::all();

// Print the response in the console.
// The `body` here is the column in the news table.
foreach ($all as $value) {
    echo ($value->body) . "\r\n";
}

// We can use the find() method to select one data,
// Provided the id = 1
$news = News::find(1);
echo $news[0]->body . "\r\n";

// Alternatively, we can use the first() method to select one data in the `comments`,
// with only the selected columns
$comments = Comments::first(2,
    ['id', 'body', 'created_at']
);
echo $comments->body . "\r\n";

// We can load the relation to the `news` table.
echo $comments->news()->title;

/**
 * --------------------------------------------------------------------------
 *  Console output
 * --------------------------------------------------------------------------
 *
 * this is the description of our fist news
 * this is the description of our second news
 * this is the description of our third news
 * this is the description of our fist news
 * i have no opinion about that
 * news 2
 *
 */
