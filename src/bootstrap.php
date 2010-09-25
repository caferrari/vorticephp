<?php
header('Content-Type: text/plain; charset=utf-8');

ini_set('error_reporting', true);
error_reporting(-1);
ini_set('display_errors', true);

require_once __DIR__ . "/SplClassLoader.php";
$loader = new \SplClassLoader();
$loader->setIncludePath('./');
$loader->register();

require_once 'functions.php';

echo new Vortice\Vortice;

