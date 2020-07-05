<?php

use App\Command\BootstrapCommandProvider;
use App\Provider\LoggerProvider;


LoggerProvider::register();
BootstrapCommandProvider::register();
