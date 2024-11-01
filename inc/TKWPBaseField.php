<?php



class TKWPBaseField
{

  public $acro ='PG';
  public $name;
  public $version;
  public function __construct()
    {
        $this->coreAction();
        $this->adminPages();
        $this->frontPages();
        $this->shortcode();
        $this->widget();
        
    }


    public function getVersion()
    { 
    	 return $this->version;
    }

    public function directoryServer($directory, $filetype ='php', $location='admin' )
    {
    	foreach (new DirectoryIterator($directory) as $fileInfo) {
        if($fileInfo->isDot()) continue;
        if($fileInfo->getExtension() != $filetype) continue;
           if($filetype =='js'){
           	  wp_enqueue_script($fileInfo->getBasename(), $location.$fileInfo->getFilename(), array('jquery'), '', true);

           }
           elseif($filetype =='css'){
               wp_enqueue_style($fileInfo->getBasename(), $location.$fileInfo->getFilename(), array(), $this->getVersion(), 'All');
           }
           elseif($filetype =='php'){
           	 require_once $fileInfo->getPathname();

           }

           elseif($filetype =='widget'){
           	    require_once $fileInfo->getPathname();
           	     register_widget($fileInfo->getBasename());
           }

         }
    }

    public function adminScripts()
    {
      
         

       if(is_dir(plugin_dir_path(dirname(__FILE__)).'assets/js/')){
         $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'assets/js/', 'js', plugins_url().'/'.$this->name.'/assets/js/');
       }
       if(is_dir(plugin_dir_path(dirname(__FILE__)).'admin/js/')){
         $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'admin/js/', 'js',plugins_url().'/'.$this->name.'/admin/js/');
       }
        
        if(is_dir(plugin_dir_path(dirname(__FILE__)).'assets/css/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'assets/css/', 'css',plugins_url().'/'.$this->name.'/assets/css/');
     }
      if(is_dir(plugin_dir_path(dirname(__FILE__)).'admin/css/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'admin/css/', 'css',plugins_url().'/'.$this->name.'/admin/css/');
     }

        
    



    }



    public function adminPages()
    {
           add_action('admin_enqueue_scripts', array($this, 'adminScripts'));


          if(is_dir(plugin_dir_path(dirname(__FILE__)).'admin/')){
           $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'admin/', 'php');
         }


    }


    public function coreAction()
    {
        if(is_dir(plugin_dir_path(dirname(__FILE__)).'core/')){
    	  $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'core/', 'php');
      }
    }


    public function frontScripts()
    {
       if(is_dir(plugin_dir_path(dirname(__FILE__)).'assets/js/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'assets/js/', 'js',plugins_url().'/'.$this->name.'/assets/js/');
     }
     if(is_dir(plugin_dir_path(dirname(__FILE__)).'front/js/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'front/js/', 'js',plugins_url().'/'.$this->name.'/front/js/');
     }
      
       if(is_dir(plugin_dir_path(dirname(__FILE__)).'assets/css/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'assets/css/', 'css',plugins_url().'/'.$this->name.'/assets/css/');
       }
       if(is_dir(plugin_dir_path(dirname(__FILE__)).'front/css/')){
       $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'front/css/', 'css', plugins_url().'/'.$this->name.'/front/css/');
     }
    }


    public function frontPages()
    {

    	   add_action('wp_enqueue_scripts', array($this, 'frontScripts'));
          if(is_dir(plugin_dir_path(dirname(__FILE__)).'front/')){
           $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'front/', 'php');
         }
    }



    public function shortcode()
    {
        if(is_dir(plugin_dir_path(dirname(__FILE__)).'shortcode/')){
       require_once plugin_dir_path(dirname(__FILE__)).'shortcode/'.$this->acro.'Shortcode.php';
      }
    }

    public function widget()
    {

        add_action('widgets_init', array($this, 'widgetClass'));
    }


 
    public function widgetClass()
    {   
       if(is_dir(plugin_dir_path(dirname(__FILE__)).'widget/')){
        $this->directoryServer(plugin_dir_path(dirname(__FILE__)).'widget/', 'widget');
      }
    }






}


