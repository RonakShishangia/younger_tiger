<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
    public $mailto = "abhishekparmar32@gmail.com";
    public $mailtoPrsn = "Abhishek Parmar";

    public function basic_email(){
        $data = array('name'=>"Ronak Shishangia");
        Mail::send(['text'=>'mail'], $data, function($message) {
        $message->to($this->mailto, $this->mailtoPrsn)->subject('Laravel Basic Testing Mail');
         $message->from('info.nkonnect@gmail.com','Ronak Shishangia');
      });
      echo "Basic Email Sent. Check your inbox.";
   }

   public function html_email(){
      $data = array('name'=>"Ronak Shishangia");
      Mail::send('mail', $data, function($message) {
         $message->to('mohit.rathod020@gmail.com', 'Mohit Rathod')->subject('Laravel HTML Testing Mail');
         $message->from('info.nkonnect@gmail.com','Ronak Shishangia');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   
   public function attachment_email(){
      $data = array('name'=>"Ronak Shishangia");
      Mail::send('mail', $data, function($message) {
         $message->to('mohit.rathod020@gmail.com', 'Mohit Rathod')->subject('Laravel Testing Mail with Attachment');
         $message->attach('/var/www/html/ronak/laravel/younger_tiger/public/uploads/Lonavala.jpg');
         $message->attach('/var/www/html/ronak/laravel/younger_tiger/public/uploads/test.txt');
         $message->from('info.nkonnect@gmail.com','Ronak Shishangia');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}