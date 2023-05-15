<?php 
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once  APPPATH.'/libraries/PHPWord/src/PhpWord/Autoloader.php';
    use PhpOffice\PhpWord\Autoloader as Autoloader;
    Autoloader::register();

    class Word extends Autoloader {

    }

    ?>
