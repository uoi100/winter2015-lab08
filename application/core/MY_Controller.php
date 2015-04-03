<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['title'] = "Top Secret Government Site";    // our default title
        $this->errors = array();
        $this->data['pageTitle'] = 'welcome';   // our default page
    }

    // Present the menu that is available depending on the user's rights
    function makeMenu(){
        // the menu basics (text navbar)

        // get role and name from session
        $userRole = $this->session->userdata('userRole');

        
        $userName = $this->session->userdata('userName');
        
        if(empty($userName))
        // make array, with menu choice for alpha
        $config['menu_choices'] = array();
        // if not logged in, add menu choice to login
        if(!$userRole == ROLE_ADMIN && !$userRole == ROLE_USER){
            $config['menu_choices'] = array(
                'menudata' => array(
                    array('name' => "Login", 'link' => '/auth'),
                )
            );
        }
        // if user, add menu choice for beta and logout
        if($userRole == ROLE_USER){
            $config['menu_choices'] = array(
                'menudata' => array(
                    array('name' => $userName,'link' => "/"),
                    array('name' => "Beta", 'link' => '/beta'),
                    array('name' => "Logout", 'link' => '/auth/logout')
                )
            );
        }
        // if admin, add menu choices for beta, gamma, and logout
        if($userRole == ROLE_ADMIN){
            $config['menu_choices'] = array(
                'menudata' => array(
                    array('name' => $userName,'link' => "/"),
                    array('name' => "Alpha", 'link' => '/alpha'),
                    array('name' => "Beta", 'link' => '/beta'),
                    array('name' => "Gamma", 'link' => '/gamma'),
                    array('name' => "Logout", 'link' => '/auth/logout'),
                )
            );
        }
        // return the choices array
        return $config['menu_choices'];
    }
    /**
     * Render this page
     */
    function render() {
        $this->data['sessionid'] = session_id();
        $this->data['menubar'] = $this->parser->parse('_menubar', $this->makeMenu(),true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);

        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }
    
    // access control to enforce on user rights
    function restrict($roleNeeded = null){
        $userRole = $this->session->userdata('userRole');
        if($roleNeeded != null){
         if(is_array($roleNeeded)){
          if(!in_array($userRole, $roleNeeded)){
           redirect("/");
           return;
          }
         } else if ($userRole != $roleNeeded){
          redirect("/");
          return;
         }
        }

    }
}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */