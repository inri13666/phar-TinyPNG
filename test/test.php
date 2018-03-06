<?php

require_once dirname(__FILE__) . '/../compiled/tinypng.phar';

\Tinify\setKey("YOUR_API_KEY");

$source = \Tinify\fromFile(dirname(__FILE__) . '/unoptimized.jpg');
$source->toFile(dirname(__FILE__) . '/optimized.jpg');
