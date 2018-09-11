<?php

/*
|--------------------------------------------|
| Cross Domain Headers
|--------------------------------------------|
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Accept, Origin, Content-Type, Authorization, x-xsrf-token, x-csrf-token, Charset');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');