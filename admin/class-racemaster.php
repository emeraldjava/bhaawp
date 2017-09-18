<?php

class RaceMaster {

    protected static $instance = null;

    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // private function __construct() {
    //     //add_action('admin_action_bhaa_admin_racemaster_export_csv',array($this,'bhaa_admin_racemaster_export_csv'));
    //     //add_action('admin_action_bhaa_admin_racemaster_preregistered',array($this,'bhaa_admin_racemaster_preregistered'));
    // }

    /**
     * https://gist.github.com/steve-jansen/7589478
     *
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */

    function bhaa_admin_racemaster_export() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        error_log("bhaa_admin_racemaster_export " . $_GET['status']);

       $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
       $sheet = $spreadsheet->getActiveSheet();
       $sheet->setTitle('Members');

       $user = new RunnerModel();
       $memberDetails = $user->getRegistrationRunnerDetails(array('M'),2000,'OBJECT',$resultCount);
       $this->populateSheet($sheet,$memberDetails);

       $sheet = $spreadsheet->createSheet(1);
       $sheet->setTitle('Inactive');
       $memberDetails = $user->getRegistrationRunnerDetails(array('I'),10000,'OBJECT',$resultCount);
       $this->populateSheet($sheet,$memberDetails);

       $sheet = $spreadsheet->createSheet(2);
       $sheet->setTitle('Day');
       $memberDetails = $user->getRegistrationRunnerDetails(array('D'),10000,'OBJECT',$resultCount);
       $this->populateSheet($sheet,$memberDetails);

       $sheet = $spreadsheet->createSheet(3);
       $sheet->setTitle('Pre-Registered');
       $memberDetails = $user->exportPreRegisteredData($resultCount);
       $this->populateSheet($sheet,$memberDetails);

       $sheet = $spreadsheet->createSheet(4);
       $sheet->setTitle('Event Details');
       $sheet->setCellValue('A1', 'Name');
       $sheet->setCellValue('A2', 'Event Date');
       $sheet->setCellValue('A3', 'File Generated Date');

       $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
       header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");// application/vnd.ms-excel.sheet.macroEnabled.12");//application/vnd.ms-excel");
       header("Content-Disposition: attachment; filename=05featuredemo.xlsx");
       header("Pragma: no-cache");
       header("Expires: 0");
       $writer->save('php://output');
    }

    private function populateSheet($sheet,$memberDetails) {

        // set the header
        $sheet->setCellValueByColumnAndRow(0,1,'BHAA_ID');
        $sheet->setCellValueByColumnAndRow(1,1,'DISPLAY_NAME');
        $sheet->setCellValueByColumnAndRow(2,1,'FIRST_NAME');
        $sheet->setCellValueByColumnAndRow(3,1,'LAST_NAME');
        $sheet->setCellValueByColumnAndRow(4,1,'STATUS');
        $sheet->setCellValueByColumnAndRow(5,1,'EMAIL');
        $sheet->setCellValueByColumnAndRow(6,1,'GENDER');
        $sheet->setCellValueByColumnAndRow(7,1,'COMPANY');
        $sheet->setCellValueByColumnAndRow(8,1,'COMPANYNAME');
        $sheet->setCellValueByColumnAndRow(9,1,'STANDARD');
        $sheet->setCellValueByColumnAndRow(10,1,'DATE_OF_BIRTH');
        $sheet->setCellValueByColumnAndRow(11,1,'PAID');

        $row = 2;
        foreach ($memberDetails as $member) {
            $sheet->setCellValueByColumnAndRow(0,$row,$member->id);
            $sheet->setCellValueByColumnAndRow(1,$row,$member->label);
            $sheet->setCellValueByColumnAndRow(2,$row,$member->firstname);
            $sheet->setCellValueByColumnAndRow(3,$row,$member->lastname);
            $sheet->setCellValueByColumnAndRow(4,$row,$member->status);
            $sheet->setCellValueByColumnAndRow(5,$row,$member->email);
            $sheet->setCellValueByColumnAndRow(6,$row,$member->gender);
            $sheet->setCellValueByColumnAndRow(7,$row,$member->company);
            $sheet->setCellValueByColumnAndRow(8,$row,$member->companyname);
            $sheet->setCellValueByColumnAndRow(9,$row,$member->standard);
            $sheet->setCellValueByColumnAndRow(10,$row,$member->dob);
            $sheet->setCellValueByColumnAndRow(11,$row,$member->paid);
            $row++;
        }
    }
}
?>
