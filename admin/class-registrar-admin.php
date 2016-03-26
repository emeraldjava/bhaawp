<?php
class RegistrarAdmin implements IAdminPage {

  public function addSubMenuPage(){
    add_submenu_page('bhaa', 'BHAA', 'Registrar',
      'manage_options','bhaa_admin_registrar',
      array($this, 'registrar_page'));
  }

  function registrar_page() {
      include_once('views/bhaa_admin_registrar.php');
      //return 'bhaa_admin_registrar';
  }
}
?>
