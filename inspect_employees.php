<?php
require 'system/bootstrap.php';
$db = \Config\Database::connect();
$fields = $db->getFieldNames('employees');
echo implode("\n", $fields);
