<?php

namespace OCup;

spl_autoload_register(function ($class_name) {
    include __DIR__ . '/' .  str_replace('\\','/',$class_name) . '.php';
});

$cup = new Cup();
$cup->calc();
