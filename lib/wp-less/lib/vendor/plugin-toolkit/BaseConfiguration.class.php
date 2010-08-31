<?php

abstract class WPPluginToolkitConfiguration
{
  const UNIX_NAME = null;
  const I18N_DIR =  'i18n';

  protected static  $upload_dir,
                    $upload_url;

  protected $base_class_name,
            $base_dirname,
            $base_filename,
            $dirname,
            $filename,
            $i18n_path,
            $i18n_path_from_plugins,
            $options,
            $plugin_path,
            $unix_name;

  /**
   * Launch the configure process
   * It is generally totally specific to each plugin.
   * 
   * @author oncletom
   * @protected
   */
  protected function configure()
  {
    $this->configureOptions();
    do_action($this->unix_name.'_configuration_configure', $this);
  }

  /**
   * Let the plugin configure its own options
   * 
   * @author oncletom
   * @abstract
   * @protected
   */
  abstract protected function configureOptions();

  /**
   * Base constructor for a plugin configuration
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @param string $baseClassName
   * @param string $baseFileName
   */
  public function __construct($baseClassName, $baseFileName)
  {
    $unix_name_pattern = $baseClassName.'Configuration::UNIX_NAME';
    $this->unix_name =   constant($unix_name_pattern);

    if (is_null($this->unix_name))
    {
      throw new Exception(sprintf('%s has not been configured for %sConfiguration.', $unix_name_pattern, $baseClassName));
    }

    $this->base_class_name =  $baseClassName;
    $this->setupPath($baseFileName, constant($unix_name_pattern));
    $this->setupPathGlobal();
    //$this->options = new $baseClassName.'OptionCollection';

    $this->configure();
    do_action($this->unix_name.'_configuration_construct', $this);
  }

  /**
   * Returns resolved plugin full path location
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getDirname()
  {
    return $this->dirname;
  }

  /**
   * Returns resolved plugin full path filename
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }

  /**
   * Returns plugin prefix for classes
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getPrefix()
  {
    return $this->base_class_name;
  }

  /**
   * Returns resolved plugin path location, from plugin path
   * 
   * In theory, it's the same as Unix path but in fact, if the plugin is renamed it can helps
   * Not very used yet, though.
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getPluginPath()
  {
    return $this->plugin_path;
  }  

  /**
   * Returns unix name of the plugin
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getUnixName()
  {
    return $this->unix_name;
  }

  /**
   * Returns the upload dir for this configuration class (common to all instances)
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getUploadDir()
  {
    return self::$upload_dir;
  }

  /**
   * Returns the upload URL for this configuration class (common to all instances)
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return string
   */
  public function getUploadUrl()
  {
    return self::$upload_url;
  }

  /**
   * Build paths for various access
   * 
   * @author oncletom
   * @protected
   * @since 1.0
   * @version 1.0
   * @param string $baseFileName
   * @param string $unix_name
   */
  protected function setupPath($baseFileName, $unix_name)
  {
    $this->base_filename =    $baseFileName;
    $this->base_dirname =     dirname($baseFileName);

    /*
     * Plugin & i18n path
     */
    if (function_exists('is_link') && is_link(WP_PLUGIN_DIR.'/'.$unix_name))
    {
      $this->filename =                 WP_PLUGIN_DIR.'/'.$unix_name.'/'.basename($this->base_filename);
      $this->i18n_path =                PLUGINDIR.'/'.$unix_name.'/i18n';
      $this->i18n_path_from_plugins =   $unix_name.'/i18n';
    }
    else
    {
      $this->filename =                 $this->base_filename;
      $this->i18n_path =                PLUGINDIR.'/'.dirname(plugin_basename($this->filename)).'/i18n';
      $this->i18n_path_from_plugins =   dirname(plugin_basename($this->filename)).'/i18n';
    }

    $this->dirname =      dirname($this->filename);
    $this->plugin_path =  preg_replace('#(.+)([^/]+/[^/]+)$#sU', "$2", $this->filename);
    do_action($this->unix_name.'_configuration_setup_path', $this);
  }

  /**
   * Resolves global upload path as WP does not provide any clean and independant solution for that
   * 
   * It's barely based on the logic of `wp_upload_dir` function.
   * 
   * @author oncletom
   * @since 1.0
   * @version 1.0
   * @return boolean
   */
  protected function setupPathGlobal()
  {
    if (isset(self::$upload_url))
    {
      return false;
    }

    $siteurl =      get_option('siteurl');
    $upload_path =  trim(get_option('upload_path'));
    $subdir =       '/'.$this->unix_name;

    if (defined('UPLOADS'))
    {
      $dir = ABSPATH.UPLOADS;
      $url = trailingslashit($siteurl).UPLOADS;
    }
    else
    {
      $dir = $upload_path ? $upload_path : WP_CONTENT_DIR.'/uploads';
      $dir = path_join(ABSPATH, $dir);

      if (!$url = get_option( 'upload_url_path'))
      {
        $url = (empty($upload_path) or ($upload_path == $dir))
                ? WP_CONTENT_URL . '/uploads'
                : trailingslashit($siteurl).$upload_path;
      }
    }
    
    $uploads = apply_filters('upload_dir', array(
      'path' =>     $dir,
      'url' =>      $url,
      'subdir' =>   $subdir,
      'basedir' =>  $dir.$subdir,
      'baseurl' =>  $url.$subdir,
      'error' =>    false,
    ));

    self::$upload_dir = $uploads['basedir'];
    self::$upload_url = $uploads['baseurl'];

    return $uploads['error'] ? false : true;
  }
}
