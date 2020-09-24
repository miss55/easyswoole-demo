<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/17
 * Time: 15:05
 */
use App\Command\BootstrapCommandProvider;
use App\Provider\LoggerProvider;


LoggerProvider::register();
BootstrapCommandProvider::register();
