<?php
class eadharvestor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();

    }


    public function index()
    {
        $this->load->view('institute_view');

    }

     public function validate(){
         $username = $_POST['username'];
        $repository = $_POST['repository'];
        $branch = $_POST['branch'];
        $directory = $_POST['directory'];
         $file_List = json_decode($_POST['fileList'], true);
         $collection = array();

                for($i=0;$i<sizeof($file_List);$i++){

                    $filename = $file_List[$i];
                     $path_to_file = "https://raw.githubusercontent.com/".$username."/".$repository."/".$branch."/".$directory."/".$filename;
                    $xml = simplexml_load_file($path_to_file);
                    if($xml->archdesc->did->unittitle) {
                        $title = $xml->archdesc->did->unittitle;
                        echo $title;
                        $collection[] = $xml->archdesc->did->unittitle;
                    }
                }

             print_r($collection);


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
