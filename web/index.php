<?php
require_once __DIR__ . '/../' . 'vendor' . '/autoload.php';

use Sincco\Tools\Gantt;

$data = array();

$data[] = array(
    'label' => 'Project 1',
    'start' => '2017-07-20',
    'end'   => '2017-08-12'
);

$data[] = array(
    'label' => 'Project 2',
    'start' => '2017-07-22',
    'end'   => '2017-08-22',
    'class' => 'important',
);

$data[] = array(
    'label' => 'Project 3',
    'start' => '2017-08-25',
    'end'   => '2017-06-20',
  'class' => 'urgent',
);

$gantti = new Gantt($data, array(
    'title'      => 'Demo',
    'cellwidth'  => 25,
    'cellheight' => 35
));

echo $gantti->render();
