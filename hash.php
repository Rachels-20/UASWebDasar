<?php
$password = 'user';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
