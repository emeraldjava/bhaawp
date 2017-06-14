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

    private function __construct() {
        //add_action('admin_action_bhaa_admin_racemaster_export_csv',array($this,'bhaa_admin_racemaster_export_csv'));
        //add_action('admin_action_bhaa_admin_racemaster_preregistered',array($this,'bhaa_admin_racemaster_preregistered'));
    }

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
        $memberDetails = $user->getRegistrationRunnerDetails(array('M'),20000,'OBJECT',$resultCount);
        //error_log('results '.$resultCount);

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
            $row++;
        }
//        $sheet->setCellValueByColumnAndRow()
  //      $csv_fields=array('id','displayname','firstname','lastname','email','status','gender','company','companyname','standard','dob');
//        $sheet->setCellValue('A1', 'Sheet 1');

        $sheet = $spreadsheet->createSheet(1);
        $sheet->setTitle('Inactive');
        $sheet->setCellValue('A1', 'Sheet 2');

        $sheet = $spreadsheet->createSheet(2);
        $sheet->setTitle('Day');
        $sheet->setCellValue('A1', 'Sheet 3');

        $sheet = $spreadsheet->createSheet(3);
        $sheet->setTitle('Pre-Registered');
        $sheet->setCellValue('A1', 'Sheet 4');

        $sheet = $spreadsheet->createSheet(4);
        $sheet->setTitle('Event Details');
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('A2', 'Event Date');
        $sheet->setCellValue('A3', 'File Generated Date');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
//        $writer->save('05featuredemo.xlsx');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");// application/vnd.ms-excel.sheet.macroEnabled.12");//application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=05featuredemo.xlsx");
        header("Pragma: no-cache");
        header("Expires: 0");
        $writer->save('php://output');

//        error_log(PhpOffice\PhpSpreadsheet\IOFactory::identify("/home/pauloconnell/projects/github/bhaawp/admin/BHAA.Race.Master.xlsm"));
//        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load("/home/pauloconnell/projects/github/bhaawp/admin/BHAA.Race.Master.xlsm");
//        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
//        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");// application/vnd.ms-excel.sheet.macroEnabled.12");//application/vnd.ms-excel");
//        header("Content-Disposition: attachment; filename=05featuredemo.xlsm");
//        header("Pragma: no-cache");
//        header("Expires: 0");
//        $writer->save('php://output');
//

        //
        // Using https://github.com/infostreams/excel-merge
        //

//        $files = array("/home/pauloconnell/projects/github/bhaawp/admin/BHAA.Race.Master.xlsm",
//            "/home/pauloconnell/projects/github/bhaawp/admin/clean-data-file.xlsx");
//        $merged = new ExcelMerge\ExcelMerge($files);
//        $merged->download("my-filename.xlsm");
//        $filename = $merged->save("/home/pauloconnell/projects/github/bhaawp/admin/my-filename.xlsm");
    }

//    function bhaa_admin_racemaster_export_csv() {
//        if ( !current_user_can( 'manage_options' ) )  {
//            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
//        }
//        error_log("bhaa_admin_racemaster_export_csv ".$_GET['status']);
//
//        $user = new RunnerModel();
//        // array('M','I','D')
//        $memberDetails = $user->exportRaceMasterData(array($_GET['status']),20000,'ARRAY_A',$resultCount);
//        $csv_fields=array('id','displayname','firstname','lastname','email','status','gender','company','companyname','standard','dob');
//        $output_filename = 'bhaa_members_'.$_GET['status'].'.csv';
//
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Content-type: text/csv');
//        header('Content-Description: File Transfer' );
//        header('Content-Disposition: attachment; filename='.$output_filename);
//        header('Expires: 0' );
//        header('Pragma: public' );
//
//        // use fputcsv : http://imtheirwebguy.com/exporting-the-results-of-a-custom-wpdb-query-to-a-downloaded-csv/
//        $output_handle = @fopen( 'php://output', 'w' );
//        fputcsv( $output_handle, $csv_fields );
//
//        // Parse results to csv format
//        foreach ($memberDetails as $member) {
//            fputcsv( $output_handle, (array) $member);
//        }
//
//        //$end = round(microtime(true) * 1000);
//        error_log('bhaa_admin_racemaster_export_csv ['.sizeof($memberDetails).']');
//
//        // Close output file stream
//        fclose( $output_handle );
//        die;
//    }
//
//    function bhaa_admin_racemaster_preregistered() {
//        if (!current_user_can('manage_options')) {
//            wp_die(__('You do not have sufficient permissions to access this page.'));
//        }
//
//        $user = new RunnerModel();
//        // array('M','I','D')
//        $memberDetails = $user->exportPreRegisteredData($resultCount);
//        $csv_fields=array('id','displayname','firstname','lastname','email','status','gender','company','companyname','standard','dob','paid');
//        $output_filename = 'bhaa_pre_registered_runners.csv';
//
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Content-type: text/csv');
//        header('Content-Description: File Transfer' );
//        header('Content-Disposition: attachment; filename='.$output_filename);
//        header('Expires: 0' );
//        header('Pragma: public' );
//
//        // use fputcsv : http://imtheirwebguy.com/exporting-the-results-of-a-custom-wpdb-query-to-a-downloaded-csv/
//        $output_handle = @fopen( 'php://output', 'w' );
//        fputcsv( $output_handle, $csv_fields );
//
//        // Parse results to csv format
//        foreach ($memberDetails as $member) {
//            fputcsv( $output_handle, (array) $member);
//        }
//
//        //$end = round(microtime(true) * 1000);
//        error_log('bhaa_admin_racemaster_preregistered ['.sizeof($memberDetails).']');
//
//        // Close output file stream
//        fclose( $output_handle );
//        die;
//    }
}
?>