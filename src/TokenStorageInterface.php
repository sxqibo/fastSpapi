<?php
namespace Sxqibo\FastSpapi;

interface TokenStorageInterface {
  public function getToken($key): ?array;
  public function storeToken($key, $value);
}
