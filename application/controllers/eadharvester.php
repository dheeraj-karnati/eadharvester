<?php
class eadharvester extends CI_Controller
{

    function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('home_view');

    }

     public function validate(){

         $instituteName = $_POST['institute'];

         $userid = $_POST['gituserId'];
         $repository = $_POST['repository'];
         $branch = $_POST['branch'];
         $directory = $_POST['directory'];
         $file_List = json_decode($_POST['fileList'], true);


         $data["file_list"] = $file_List;
         $num_files = sizeof($file_List);
         $req_id = $this->insert_inst_info($instituteName,$userid, $repository, $branch,$directory, $num_files);
         if($req_id > 0) {
             for ($i = 0; $i < sizeof($file_List); $i++) {
                 $rules_valid= array();
                 $rules_failed= array();
                 $filename = $file_List[$i];
                 $path_to_file = "https://raw.githubusercontent.com/" . $userid . "/" . $repository . "/" . $branch . "/" . $directory . "/" . $filename;
                 $xml = simplexml_load_file($path_to_file);

                 /* Rule #: Collection Title Validation */

                 if ($xml->archdesc->did->unittitle) {
                     $title = $xml->archdesc->did->unittitle;
                     if ($title != null or $title != "") {

                         $rules_valid[] = 1;
                     } else {

                         $rules_failed[] = 1;
                     }
                 } else {

                     $rules_failed[] = 1;
                 }

                 /* Collection Creator Validation  */
                 if (isset($xml->archdesc->did->origination)) {

                     $rules_valid[] = 2;


                 } else {

                     $rules_failed[] = 2;

                 }

                 /* Collection Dates Validation*/
                 if (isset($xml->archdesc->did->unitdate)) {

                     $rules_valid[] = 3;

                 } else {

                     $rules_failed[] = 3;

                 }


                 /* Abstract Validation */
                 if (isset($xml->archdesc->did->abstract)) {

                     $rules_valid[] = 4;


                 } else {

                     $rules_failed[] = 4;


                 }

                 /* Repository Validation */
                 if (isset($xml->archdesc->did->repository->corpname)) {

                     $rules_valid[] = 5;


                 } else {

                     $rules_failed[] = 5;


                 }
                 /* Language of Material Validation */
                 if (isset($xml->archdesc->did->langmaterial->language)) {

                     $rules_valid[] = 6;

                 } else {

                     $rules_failed[] = 6;

                 }

                 /* Physical Description Validation */
                 if (isset($xml->archdesc->did->physdesc->extent)) {

                     $rules_valid[] = 7;


                 } else {

                     $rules_failed[] = 7;

                 }

                 /* Access Restrictions Validation */
                 if (isset($xml->archdesc->accessrestrict)) {

                     $rules_valid[] = 8;

                 } else {

                     $rules_failed[] = 8;

                 }
                 /* Biography or Historical Note Validation */
                 if (isset($xml->archdesc->bioghist)) {

                     $rules_valid[] = 9;

                 } else {

                     $rules_failed[] = 9;

                 }

                 /* Controlled Access Headings Validation */
                 if (isset($xml->archdesc->controlaccess)) {

                     $rules_valid[] = 10;

                 } else {

                     $rules_failed[] = 10;

                 }
                 /* Scope and Content Note Validation */

                 if (isset($xml->archdesc->scopecontent)) {

                     $rules_valid[] = 11;

                 } else {

                     $rules_failed[] = 11;

                 }
                 /* Use Restrictions Validation */

                 if (isset($xml->archdesc->userestrict->p)) {

                     $rules_valid[] = 12;

                 } else {

                     $rules_failed[] = 12;

                 }
                 if(sizeof($rules_valid)>0) {
                     $rules_valid_to_string = implode(",", $rules_valid);
                 }else{

                     $rules_valid_to_string = " ";
                 }
                 if(sizeof($rules_failed) > 0) {
                     $rules_failed_to_string = implode(",", $rules_failed);
                 }else {
                     $rules_failed_to_string = " ";
                 }
                 $data = array(
                     'req_id'   => $req_id,
                     'file_name'    => $filename,
                     'rules_valid'  => $rules_valid_to_string,
                     'rules_failed'    => $rules_failed_to_string
                 );

                 $this->load->model('eadharvester_model');
                 $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');
                 if($_result == 0 ){
                  break;
                 }
             }
             $validation_array = $this -> eadharvester_model -> getResults($req_id);
             if(sizeof($validation_array)>0) {

                 $validation_list = json_encode($validation_array);
                 echo $validation_list;

             }else{

                 echo "";

             }

         }else {
             echo 0;
         }
     }

     public function getValidationResults(){

         $req_id =$this -> input -> get('reqId');

         $this->load->model('eadharvester_model');
          $validation_array = $this -> eadharvester_model -> getResults($req_id);
          if($validation_array > 0){


              $validation_list = json_decode(json_encode($validation_array),true);
            return $validation_list;

          }else{

              return "";
          }

     }


    /**
     *
     */
    public function result(){
         $data["repository"] = "dkarnati174";
         $data["branch"] = "master";
         $data["directory"] = "EADs";
         $rules_valid=array(1,2,3, 4, 5, 11, 12);
         $rules_failed=array(6,7,8, 9, 10);
         $rv= implode(",",$rules_valid);
         $rf = implode(",", $rules_failed);
         $file_list=array("1.xml","2.xml","3.xml", "4.xml", "5.xml");

//         foreach ($file_list as $file){
//             $rules_failed=array("6","7","8", "9", "10");
//             $rules_valid=array("1","2","3", "4", "5", "11", "12");
//
//              $rv= implode(",",$rules_valid);
//              $rf = implode(",", $rules_failed);
//
//         }
         $data["file_list"] = $file_list;
         $data["rules_valid"] = $rv;
         $data["rules_failed"] = $rf;
         $this->load->view('results_view', $data);

     }


public function insert_inst_info($instName,$gitUserId, $repository,$branch , $directory, $num_files){

    date_default_timezone_set('US/Eastern');
    $date = date("m/d/Y");
    $data = array(
        'institute_name'   => $instName,
        'git_username'    => $gitUserId,
        'git_repo_name'  => $repository,
        'repo_branch'    => $branch,
        'branch_dir'    => $directory,
        'num_files'       => $num_files,
    );

    $this->load->model('eadharvester_model');
    $_result = $this->eadharvester_model->insert_institute($data, 'institute_request_info');

    if($_result > 0){
        return $_result;
    }else {

        return 0;
    }
}
    public function insert_val_log($req_id,$file, $rules_valid,$rules_failed ){


        $data = array(
            'req_id'   => $req_id,
            'file_name'    => $file,
            'rules_valid'  => $rules_valid,
            'rules_failed'    => $rules_failed
        );

        $this->load->model('eadharvester_model');
        $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');

        if($_result > 0){
            return $_result;
        }else {

            return 0;
        }
    }

    public function insert_val_log_test(){
        $rules_valid=array(1,2,3, 4, 5, 11, 12);
        $rules_failed=array(6,7,8, 9, 10);
        $rv= implode(",",$rules_valid);
        $rf = implode(",", $rules_failed);

        date_default_timezone_set('US/Eastern');
        $date = date("m/d/Y");
        $data = array(
            'req_id'   => 1,
            'file_name'    => "test.xml",
            'rules_valid'  => $rv,
            'rules_failed'    => $rf,

        );

        $this->load->model('eadharvester_model');
        $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');

        if($_result > 0){
            echo $_result;
        }else {

            echo "failed";
        }
    }


public function insert_user_info()
{

   $this->load->model('eadharvester_model');

$data = array(
    'institute_name'   => "dkarnati",
    'git_username'    => "dkarnati174",
    'git_repo_name'  => "EADs",
    'repo_branch'    => "master",
    'branch_dir'    =>"test",
    "num_files" => 2

);
    $_result = $this->eadharvester_model->insert_institute($data, 'institute_request_info');
if($_result > 0){
    echo $_result;
}else {

    echo "failed";
}

}
    public function sendEmail()
    {

        /****
         * $cart_items = json_decode($_POST['final_cart'], true);
         * $user =$_POST["firstName"]." ".$_POST["lastName"];
         *
         * $emailId = $_POST["emailId"];
         * if($_POST["message"]!= null) {
         * $user_message = $_POST["message"];
         * }else{
         * $user_message = "";
         *
         * }
         * //       $message = '<html><body>';
         * //
         * //       $message .= '<table width="100%"; rules="all" style="border:1px solid #3A5896;" cellpadding="10">';
         * //
         * //       $message .= '<tr><td align="center"><img src="https://www.empireadc.org/sites/www.empireadc.org/files/ead_logo.gif" /><h3>Research Request </h3>';
         * //
         * //       $message .= "<br/><br/><h4 align='left'>Dear $user,</h4> <h4><br/> </h4><br/></br/><h4 align='left' style='font-style: italic'>Thanks & Regards,</h4><h4 align='left' style='font-style: italic'>Empire Archival Discovery Co-Operative.</h4></td></tr>";
         * //
         * //       $message .= "<tr><td><h3>your final ......:</h3></br></br>" ;
         * //       $message .= '<table width="80%"; rules="all" style="border:1px solid #3A5896;" align="center" cellpadding="10">';
         * //
         * //       for($i=0;$i<sizeof($cart_items);$i++){
         * //           $Sno = $i+1;
         * //           $message .=  "<tr><td>$Sno</td><td>".urldecode($cart_items[$i])."</td></tr>";
         * //       };
         * //       $message .= "</table></br></td></tr><tr><td><h3>Your Message:</h3></br>$user_message</h3></td></tr></table>";
         * //
         * //       $message .= "</body></html>";
         *
         * $ci = get_instance();
         * $ci->load->library('email');
         * $config['protocol'] = "smtp";
         * $config['smtp_host'] = "tls://smtp.googlemail.com";
         * $config['smtp_port'] = "465";
         * $config['smtp_user'] = "***************REUQIRED VALUE****************";
         * $config['smtp_pass'] = "***************REQUIRED VALUE****************";
         * $config['charset'] = "utf-8";
         * $config['mailtype'] = "html";
         * $config['newline'] = "\r\n";
         *
         * $ci->email->initialize($config);
         *
         * $ci->email->from('user@gmail.com', "user");
         * $ci->email->cc('user@gmail.com');
         * $ci->email->to($emailId);
         * $ci->email->reply_to('user@gmail.com', "user");
         * //   $ci->email->message($message);
         *
         * $ci->email->subject("email subject");
         * if($ci->email->send()){
         * echo 1;
         * }else{
         * echo 0;
         * }
         ****/
    }
}
?>
