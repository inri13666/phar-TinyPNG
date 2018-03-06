<?php
define('DS', DIRECTORY_SEPARATOR);

if (!is_dir(__DIR__ . DS . 'compiled')) {
    if (!mkdir(__DIR__ . DS . 'compiled')) {
        throw new Exception('Cant Create Folder');
    };
}

require_once 'vendor/tinify/tinify/lib/Tinify.php';

define('VERSION', \Tinify\VERSION);

$filename = dirname(__FILE__) . DS . 'compiled' . DS . 'tinypng';

/**
 * Remove Previous Compiled Archives
 */
if (is_readable($filename)) {
    unlink($filename);
}

$archive = new Phar($filename . '.phar', 0, 'TinyPNG');
$archive->buildFromDirectory('vendor');
$bootstrap = file_get_contents(dirname(__FILE__) . DS . 'phar-bootstrap.php');
$archive->setStub($bootstrap);
$archive = null;
unset($archive);
file_put_contents($filename . '-' . VERSION . '.phar', file_get_contents($filename . '.phar'));

if (extension_loaded('zlib')) {
    //Create GZ Archive, That will use Phar's Stub
    if (function_exists('gzopen')) {
        if (is_readable($filename . '.gz')) {
            unlink($filename . '.gz');
        }
        $gz = gzopen($filename . '.gz', 'w9');
        gzwrite($gz, file_get_contents($filename . '.phar'));
        gzclose($gz);
        file_put_contents($filename . '-' . VERSION . '.gz', file_get_contents($filename . '.gz'));
    }
}

if (extension_loaded('bz2')) {
    //Create BZ2 Archive, That will use Phar's Stub
    if (function_exists('bzopen')) {
        if (is_readable($filename . '.bz2')) {
            unlink($filename . '.bz2');
        }
        $bz2 = bzopen($filename . '.bz2', 'w');
        bzwrite($bz2, bzcompress(file_get_contents($filename . '.phar'), 9));
        bzclose($bz2);
        file_put_contents($filename . '-' . VERSION . '.bz2', file_get_contents($filename . '.bz2'));
    }
}
