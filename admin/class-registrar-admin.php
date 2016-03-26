<?php
class RegistrarAdmin implements IAdminPage {

  public function addSubMenuPage(){
    add_submenu_page('bhaa', 'BHAA', 'Registrar',
      'manage_options','bhaa_admin_registrar',
      array($this, 'registrar_page'));
  }

  function registrar_page() {
      $SQL = 'select
          MONTHNAME(DATE(dor.meta_value)) as month,
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
      error_log($SQL);
  		global $wpdb;
  		$results = $wpdb->get_results($SQL,OBJECT);
      include_once('views/bhaa_admin_registrar.php');
  }
}
?>
