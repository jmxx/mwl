<?php

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

if (! function_exists('manifest')) {
  /**
   * Get the path to a versioned Mix file.
   *
   * @param  string  $path
   * @param  string  $manifestDirectory
   * @return \Illuminate\Support\HtmlString
   *
   * @throws \Exception
   */
  function manifest($path, $manifestDirectory = '', $manifestFile = 'manifest.json') {
    static $manifest;

    // if (! starts_with($path, '/')) {
    //   $path = "/{$path}";
    // }

    if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
      $manifestDirectory = "/{$manifestDirectory}";
    }

    // if (file_exists(public_path($manifestDirectory.'/hot'))) {
    //   return new HtmlString("//localhost:8080{$path}");
    // }

    if (! $manifest) {
      if (! file_exists($manifestPath = public_path($manifestDirectory . '/' . $manifestFile))) {
        throw new Exception('The Mix manifest does not exist.');
      }

      $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    if (! array_key_exists($path, $manifest)) {
      return '';

      throw new Exception(
        "Unable to locate Mix file: {$path}. Please check your ".
        'webpack.mix.js output paths and try again.'
      );
    }

    return new HtmlString($manifestDirectory . $manifest[$path]);
  }
}

if (!function_exists('cdn')) {

  /**
   * cdn helper
   *
   * @param string $name
   * @return string
   */
  function cdn($file) {
    return env('APP_ENV', 'production') === 'local'
      ? manifest($file)
      : env('APP_CDN', '//cdn.mwl.mx/') . manifest($file);
  }
}
