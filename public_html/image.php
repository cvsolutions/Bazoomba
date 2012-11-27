<?php

include_once realpath(dirname(__FILE__) . '/../application') . '/plugins/imageTransform.php';

$mode = $_GET['mode'];
$image = sprintf('%s/uploaded/%s/%s', realpath(dirname(__FILE__)), $_GET['folder'], $_GET['image']);
$size = sprintf('%dx%d', $_GET['width'], $_GET['height']);

/** http://bazoomba/image.php?mode=crop&folder=ads&image=example.jpg&width=100&height=50 */

$obj = new imageTransform();
$obj->cache = 'cache/';
$obj->view($mode, $image, $size);
exit();