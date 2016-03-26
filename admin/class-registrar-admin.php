<?php
class RegistrarAdmin implements IAdminPage {

  public function addSubMenuPage() {
    add_submenu_page('bhaa', 'BHAA', 'Registrar',
      'manage_options','bhaa_admin_registrar',
      array($this, 'bhaa_admin_registrar_page'));
    // use 'null' to register a hidden page
    add_submenu_page(null, 'BHAA', 'Registrar',
      'manage_options','bhaa-admin-registrar-monthly',
      array($this, 'bhaa_admin_registrar_monthly_page'));
    add_submenu_page(null, 'BHAA', 'Registrar',
      'manage_options','bhaa-admin-registrar-deactivate',
      array($this, 'bhaa_admin_registrar_deactivate_page'));
  }

  function bhaa_admin_registrar_page() {
    if ( !current_user_can( 'manage_options' ) ) {
  		wp_die(__('You do not have sufficient permissions to access this page.'));
  	}
    $SQL = 'select
        MONTH(DATE(dor.meta_value)) as month,
        MONTHNAME(DATE(dor.meta_value)) as monthname,
        YEAR(DATE(dor.meta_value)) as year,
        count(m_status.umeta_id) as count
      from wp_users
        join wp_usermeta m_status
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

  /**
   * List all runners that renewed in a specifc month/year.
   */
  function bhaa_admin_registrar_monthly_page() {
    if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
    $results = $this->getMemberStatusRunnersForMonthYear($_GET['month'],$_GET['year']);
    include_once('views/bhaa_admin_registrar_monthly.php');
  }

  /**
   * Deactive runners for a specific month/year.
   */
  function bhaa_admin_registrar_deactivate_page() {
    if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
    $results = $this->getMemberStatusRunnersForMonthYear($_GET['month'],$_GET['year']);
    foreach($results as $row) {
      // TODO move this to the Runner class and update the modification date.
      update_user_meta($row->ID, Runner::BHAA_RUNNER_STATUS,'I');
      error_log('Marking runner "'.$row->display_name.'/'.$row->ID.'" as status "I".');
    }
    $this->bhaa_admin_registrar_page();
  }

  private function getMemberStatusRunnersForMonthYear($month,$year) {
    global $wpdb;
    $SQL = $wpdb->prepare('select u.ID, u.display_name, dor.meta_value as dor
      from wp_users u
        join wp_usermeta m_status
          on (m_status.user_id=u.id
            and m_status.meta_key="bhaa_runner_status"
            and m_status.meta_value="M")
        join wp_usermeta dor
          on (dor.user_id=u.id
            and dor.meta_key="bhaa_runner_dateofrenewal")
      where MONTH(dor.meta_value)=%d AND YEAR(dor.meta_value)=%d
        order by dor.meta_value ASC',$month,$year);
    error_log($SQL);
    return $wpdb->get_results($SQL,OBJECT);
  }
}
?>
