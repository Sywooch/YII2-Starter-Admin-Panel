<?php
/**
 * Created by PhpStorm.
 * User: disha
 * Date: 07/11/16
 * Time: 4:30 PM
 */


namespace backend\components;

use Yii;
use yii\base\Component;


class Email extends Component
{
    public function send($type, $to_email, $from_email, $description, $attachment_path = "",$cc=[])
    {
        switch ($type) {
            case WELCOME_MAIL:
                $view = 'email';
                $subject = 'Welcome';
                break;
            case FACILITY_CC_RESERVATION:
                $view = 'facility_cc_reservation';
                $subject = 'Facility reservation #'.$description['reservation']." for check in date: ".$description['check_in_date'];
                break;
            case FACILITY_DIRECT_BILL_RESERVATION:
                $view = 'facility_direct_bill_reservation';
                $subject = 'Facility reservation #'.$description['reservation']." for check in date: ".$description['check_in_date'];
                break;
            case RESERVATION_CONFIRMATION_MAIL:
                $view = 'reservation_confirmation';
                $subject = 'Reservation confirmation #'.$description['reservation']." for check in date: ".$description['check_in_date'];
                break;
            case FORGOT_PASSWORD_MAIL:
                $view = 'forgot_password';
                $subject = 'Password reset link';
                break;
            case WELCOME_STAFF_USER:
                $view = 'welcome_admin';
                $subject = 'Welcome staff user';
                break;
            case RESERVATION_CANCELLATION_CLIENT:
                $view = 'client_cancellation_reservation';
                $subject = 'RESERVATION CANCELLATION for #'.$description['reservation_no'];
                break;
            case RESERVATION_CANCELLATION_FACILITY:
                $view = 'facility_cancellation_reservation';
                $subject = 'FACILITY RESERVATION CANCELLATION - #'.$description['reservation_no'];
                break;
            case EXTEND_TO_CLIENT:
                $view = 'client_extend_reservation';
                $subject = 'CLIENT RESERVATION CHANGED -  #'.$description['reservation_no'];
                break;
        }
        //call sent mail
        return $this->sendMail($type,$view, $to_email, $from_email, $subject, $description, $attachment_path, $cc);
    }

    //innner function which will send composed mail

    public function sendMail($type,$view, $to_email, $from_email, $subject, $description, $attachment_path = "",$cc=[])
    {
         $mailer = \Yii::$app->mailer;
        if(DEV){
            if($type == WELCOME_STAFF_USER || $type == WELCOME_MAIL){
                $to_email = ANDREA;
            }else if($type == FACILITY_CC_RESERVATION || $type == FACILITY_DIRECT_BILL_RESERVATION){
                    //do nothing
            }else{
                $to_email = ADMIN_EMAIL;
            }

        }
        if($type == FACILITY_CC_RESERVATION || $type== FACILITY_DIRECT_BILL_RESERVATION){
             if (!empty($attachment_path) && !empty($cc)) {
                return $mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setBcc(FACILTY_BCC)
                ->setCc($cc)
                ->setTo($to_email)
                ->setSubject($subject)
                ->attach($attachment_path)
                ->send();
             }else if(empty($attachment_path) && !empty($cc)){
                 return $mailer->compose($view, $description)
                     ->setFrom([$from_email => 'Team CrewFacilities.com'])
                     ->setBcc(FACILTY_BCC)
                     ->setCc($cc)
                     ->setTo($to_email)
                     ->setSubject($subject)
                     ->send();
             }else{
                 return $mailer->compose($view, $description)
                     ->setFrom([$from_email => 'Team CrewFacilities.com'])
                     ->setBcc(FACILTY_BCC)
                     ->setTo($to_email)
                     ->setSubject($subject)
                     ->send();
             }
        }elseif($type == RESERVATION_CONFIRMATION_MAIL){
            if (!empty($attachment_path)) {
                return $mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setBcc(CLIENT_BCC)
                ->setTo($to_email)               
                ->setSubject($subject)
                ->attach($attachment_path)
                ->send();
             }else{
                return $mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setBcc(CLIENT_BCC)
                ->setTo($to_email)               
                ->setSubject($subject)
                ->send();
             }
        }elseif($type == RESERVATION_CANCELLATION_CLIENT || $type == EXTEND_TO_CLIENT){

            return $mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setBcc(CLIENT_BCC)
                ->setTo($to_email)
                ->setSubject($subject)
                ->send();
        }elseif($type == RESERVATION_CANCELLATION_FACILITY){

            return $mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setBcc(FACILTY_BCC)
                ->setTo($to_email)
                ->setSubject($subject)
                ->send();
        }else{
            if (!empty($attachment_path)) {
            return \Yii::$app->mailer->compose($view, $description)
                ->setFrom([$from_email => 'Team CrewFacilities.com'])
                ->setTo($to_email)
                ->setSubject($subject)
                ->attach($attachment_path)
                ->send();
            } else {
                return \Yii::$app->mailer->compose($view, $description)
                    ->setFrom([$from_email => 'Team CrewFacilities.com'])
                    ->setTo($to_email)
                    ->setSubject($subject)
                    ->send();
                    }
            }       
    }
}