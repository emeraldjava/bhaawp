<?php
class RegistrarAdmin implements IAdminPage {

  //public function __construct() {
    //add_action('admin_action_registrar_monthly',array($this,'registrar_monthly'));
  //}

  public function addSubMenuPage() {
    add_submenu_page('bhaa', 'BHAA', 'Registrar',
      'manage_options','bhaa_admin_registrar',
      array($this, 'bhaa_admin_registrar_page'));
    // use 'null' to register a hidden page
    add_submenu_page(null, 'BHAA', 'Registrar',
      'manage_options','bhaa-admin-registrar-monthly',
      array($this, 'bhaa_admin_registrar_monthly_page'));
  }

  function bhaa_admin_registrar_page() {
      $SQL = 'select
          MONTH(DATE(dor.meta_value)) as month,
          MONTHNAME(DATE(dor.meta_value)) as monthname,
          YEAR(DATE(dor.meta_value)) as year,
          count(m_status.umeta_id) as count
        from wp_users
          left join wp_usermeta m_status
            on (m_status.user_id=wp_users.id
              and m_status.meta_key="bhaa_runner_status"
              and m_status.meta_value="M")
          join wp_usermeta dor
            on (dor.user_id=wp_users.id
              and dor.meta_key="bhaa_runner_dateofrenewal")
        where DATE(dor.meta_value)>=DATE("2014-01-01")
          group by YEAR(DATE(dor.meta_value)), MONTHNAME(DATE(dor.meta_value))
          order by YEAR(DATE(dor.meta_value)) DESC, MONTH(DATE(dor.meta_value))';
      global $wpdb;
  		$results = $wpdb->get_results($SQL,OBJECT);
      include_once('views/bhaa_admin_registrar.php');
  }

  function bhaa_admin_registrar_monthly_page() {
    //error_log('here '.var_dump($_GET));
    include_once('views/bhaa_admin_registrar_monthly.php');
  }
}
?>
