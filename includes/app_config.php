<?php
// Cambiá esto si tu app vive en una subcarpeta (ej: '/creditorg')
if (!defined('APP_BASE')) define('APP_BASE', '/public');

function url_path(string $path): string {
  $base = rtrim(APP_BASE, '/');
  return ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
}
