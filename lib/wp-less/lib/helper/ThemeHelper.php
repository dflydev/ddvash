<?php
/**
 * LESSify a stylesheet on the fly
 * 
 * <pre>
 * <head>
 *  <title><?php wp_title() ?></title>
 *  <link rel="stylesheet" media="all" type="text/css" href="<?php echo wp_lessify(get_bloginfo('template_dir').'/myfile.less') ?>" />
 * </head>
 * </pre>
 * 
 * @todo hook on WordPress cache system
 * @author oncletom
 * @since 1.2
 * @version 1.0
 * @param string $stylesheet_uri
 * @param string $cache_key
 * @param string $version_prefix
 * @return string processed URI
 */
function wp_lessify($stylesheet_uri, $cache_key = null, $version_prefix = '?ver=')
{
  static $wp_less_uri_cache;
  $cache_key = 'wp-less-'.($cache_key === '' ? md5($stylesheet_uri) : $cache_key);

  if (is_null($wp_less_uri_cache))
  {
    $wp_less_uri_cache = array();
  }

  if (isset($wp_less_uri_cache[$cache_key]))
  {
    return $wp_less_uri_cache[$cache_key];
  }

  /*
   * Register a fake stylesheet to make the process possible
   * It relies on a _WP_Dependency object
   */
  wp_register_style($cache_key, $stylesheet_uri);
  $stylesheet = WPLessPlugin::getInstance()->processStylesheet($cache_key);
  wp_deregister_style($cache_key);
  $wp_less_uri_cache[$cache_key] = $stylesheet->getTargetUri(true, $version_prefix);

  unset($stylesheet);
  return $wp_less_uri_cache[$cache_key];
}

