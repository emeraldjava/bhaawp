<?php

/**
 * Handles query based on the BHAA standard
 */
class StandardModel extends BaseModel {

    public function getName() {
        return 'wp_bhaa_standard';
    }

    /**
     * Return all standard details
     * SELECT standard from wp_bhaa_standard
     */
    function getStandards() {
        return $this->wpdb->get_results(
            $this->wpdb->prepare('select * FROM %s',$this->getName())
        );
    }

    /**
     * Return the count of members per standard
     */
    function getMemberStandardProfile() {
        return $this->wpdb->get_results(
            $this->wpdb->prepare('SELECT standard,count(m_std.umeta_id) as count from wp_bhaa_standard
                join wp_usermeta m_std
                  on (m_std.meta_value=wp_bhaa_standard.standard and m_std.meta_key="bhaa_runner_standard")
                join wp_usermeta m_status
                  on (m_status.user_id=m_std.user_id and m_status.meta_key="bhaa_runner_status" and m_status.meta_value="M")
                group by wp_bhaa_standard.standard')
        );
    }

    //SELECT standard,count(std.umeta_id) as count from wp_bhaa_standard
    //join wp_usermeta std
    //on (std.meta_value=wp_bhaa_standard.standard and std.meta_key="bhaa_runner_standard")
    //group by wp_bhaa_standard.standard;
}
?>