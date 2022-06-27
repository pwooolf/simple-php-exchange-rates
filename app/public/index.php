<?php


require __DIR__ . '/../vendor/autoload.php';

try {

    echo 'hello, world';

} catch (Throwable $e) {
    echo $e->getMessage();
}
