<?php

namespace Xenyo;

use Ramsey\Uuid\Uuid;

class UniScripts {
  public static function resetUuid() {
    $yml_path = "config/sync/system.site.yml";
    $sql_path = "database.sql";

    // Generate a random UUID
    $uuid = Uuid::uuid4()->toString();

    // Replace the UUID in system.site.yml
    $yml = file_get_contents($yml_path);
    if ($yml === false) {
      echo "Error: could not read $yml_path";
      exit(1);
    }
    $yml = preg_replace("/uuid: ?[0-9a-f-]+/", "uuid: $uuid", $yml);
    $yml_result = file_put_contents($yml_path, $yml);
    if ($yml_result === false) {
      echo "Error: could not write $yml_path";
      exit(1);
    }

    // Replace the UUID in database.sql
    $sql = file_get_contents($sql_path);
    if ($sql === false) {
      echo "Error: could not read $sql_path";
      exit(1);
    }
    $sql = preg_replace("/('system\.site'.*\\\"uuid\\\";s:36:\\\")[0-9a-f-]+/", "$1$uuid", $sql);
    $sql_result = file_put_contents($sql_path, $sql);
    if ($sql_result === false) {
      echo "Error: could not write $sql_path";
      exit(1);
    }

    echo "Updated UUID to $uuid. Please import the database and commit the changes.";
  }
}