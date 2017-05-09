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
use kartik\mpdf\Pdf;
	use common\models\Media;
use common\models\ReservationMedia;
use SoapClient;
use yii\base\Object;

class Fax extends Component
{
	public function welcome() {    	
		echo "Hello..Welcome to Fax Component";
	}
	public function send($type,$reservation)
	{
		switch ($type) {
			case FACILITY_CC_RESERVATION:
				$view ='@common/mail/facility_cc_reservation_fax';
				$cc_info_view = '@common/mail/cc_info';
				$subject = 'FACILITY RESERVATION';
				break;
			case FACILITY_DIRECT_BILL_RESERVATION:
				$view = '@common/mail/facility_direct_bill_reservation_fax';
				$subject = 'FACILITY RESERVATION';
				break;
		}

		$guestname =[];
		$roomsrate =[];
		foreach($reservation->reservationRooms as $rooms){
			foreach($rooms->reservationGuests as $rg){
				array_push($guestname,$rg->person->first_name." ".$rg->person->last_name);
			}
		}
		foreach($reservation->reservationRooms as $rooms){
			foreach($rooms->reservationRoomData as $rd){
				if($rd->buy_rate){
					$tax = ($rd->tax_type)?$rd->tax.'%':'$'.$rd->tax;
					$rate_string =  '$'.$rd->buy_rate.' with tax '.$tax;
					array_push($roomsrate,$rate_string);
				}

			}
		}

		$description = [
			'reservation' => $reservation->reservation_no,
			'single_rooms' => $reservation->single_rooms,
			'double_rooms' => $reservation->double_rooms,
			'check_in_date' => date('m/d/Y', strtotime($reservation->check_in_date)),
			'check_out_date' => date('m/d/Y',strtotime($reservation->check_out_date)),
			'guest_name' => implode(', ',$guestname), 'rate' => implode(', ', $roomsrate)];

		if($type==FACILITY_CC_RESERVATION){
			$flag = 0;
			$content = Yii::$app->controller->renderPartial($view,$description);
			//create pdf from layout
			$pdf = $this->createPDFNew($content,$reservation->id);
			if($pdf){
				if(isset($reservation->facilities) && !empty($reservation->facilities)){
				if($reservation->facilities->fax){
					$number = $reservation->facilities->fax;

					$search = array("(", ")", "-"," ");
					$number = str_replace($search,"",$number);
    				$fax_number =  "+1".$number;    				
					$faxResult = $this->sendFax($pdf,$fax_number);
					if($faxResult > 0){
						$flag = 1;
					}
					if($flag){

						$cc_content = Yii::$app->controller->renderPartial($cc_info_view,['model'=>$reservation]);
						$cc_pdf = $this->createPDF($cc_content,$reservation->id);
						$cc_faxResult = $this->sendFax($cc_pdf,$fax_number);
						if($cc_faxResult > 0){
							return true;
						}else{							
							return false;
						}
					}else{
						return false;
					}
				}else{					
					return false;
				}
				}else{					
					return false;
				}
			}else{				
				return false;
			}
			
		}elseif($type==FACILITY_DIRECT_BILL_RESERVATION){
			$content = Yii::$app->controller->renderPartial($view,$description);
			//create pdf from layout

			$pdf = $this->createPDF($content,$reservation->id);
			if($pdf){
			if(isset($reservation->facilities) && !empty($reservation->facilities)){	
			if($reservation->facilities->fax){				
				$number = $reservation->facilities->fax;
				$search = array("(", ")", "-"," ");
				$number = str_replace($search,"",$number);
    			$fax_number =  "+1".$number;    			
				$faxResult = $this->sendFax($pdf,$fax_number);
				if($faxResult > 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{					
					return false;
				}
		}else{
			return false;
		}
		}

	}
	public function createPDF($content,$reservation_id,$upload=false)
	{

		try{
			$path = Yii::getAlias('@backend').'/web/uploads/temp/';
			$file_name = time().'_file_to_fax.pdf';
			$pdf_file_path = $path.$file_name;


			if(!is_dir($path)){
					mkdir('temp',0777);
			}
			$pdf = new Pdf([
				// set to use core fonts only
				'mode' => Pdf::MODE_CORE,
				// A4 paper format
				'format' => Pdf::FORMAT_A4,
				// portrait orientation
				'orientation' => Pdf::ORIENT_PORTRAIT,
				// stream to browser inline
				'destination' => Pdf::DEST_FILE,
				'filename' => $pdf_file_path,
				// your html content input
				'content' => $content,
				// format content from your own css file if needed or use the
				// enhanced bootstrap css built by Krajee for mPDF formatting
				'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
				// any css to be embedded if required
				'cssInline' => '.invoice-box{max-width:800px;margin:auto;padding:30px;border:1px solid #eee;box-shadow:0 0 10px rgba(0,0,0,.15);font-size:16px;line-height:24px;font-family:"Helvetica Neue",Helvetica,Helvetica,Arial,sans-serif;color:#555}.invoice-box table{width:100%;line-height:inherit;text-align:left}.invoice-box table td{padding:5px;vertical-align:top}.invoice-box table tr td:nth-child(2){text-align:right}.invoice-box table tr.top table td{padding-bottom:20px}.invoice-box table tr.top table td.title{font-size:45px;line-height:45px;color:#333}.invoice-box table tr.information table td{padding-bottom:40px}.invoice-box table tr.heading td{background:#eee;border-bottom:1px solid #ddd;font-weight:700}.invoice-box table tr.details td{padding-bottom:20px}.invoice-box table tr.item td{border-bottom:1px solid #eee}.invoice-box table tr.item.last td{border-bottom:none}.invoice-box table tr.total td:nth-child(2){border-top:2px solid #eee;font-weight:700}@media only screen and (max-width:600px){.invoice-box table tr.information table td,.invoice-box table tr.top table td{width:100%;display:block;text-align:center}}',
				// set mPDF properties on the fly
				'options' => ['title' => 'Krajee Report Title'],
				// call mPDF methods on the fly
			]);
			$pdf->render();
			//print_r($pdf_file_path);die;
			if($upload)
			$this->uploadMedia($file_name,$pdf_file_path,$reservation_id);
			return $pdf_file_path;
		}catch (\Exception $e){
				return false;
		}
	}
	public function createPDFNew($content,$reservation_id,$upload=false)
	{

		try{
			$path = Yii::getAlias('@backend').'/web/uploads/temp/';
			$file_name = time().'_file_to_fax.pdf';
			$pdf_file_path = $path.$file_name;


			if(!is_dir($path)){
					mkdir('temp',0777);
			}
			$pdf = new Pdf([
				// set to use core fonts only
				'mode' => Pdf::MODE_CORE,
				// A4 paper format
				'format' => Pdf::FORMAT_A4,
				// portrait orientation
				'orientation' => Pdf::ORIENT_PORTRAIT,
				// stream to browser inline
				'destination' => Pdf::DEST_FILE,
				'filename' => $pdf_file_path,
				// your html content input
				'content' => $content,
				// format content from your own css file if needed or use the
				// enhanced bootstrap css built by Krajee for mPDF formatting
				'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
				// any css to be embedded if required
				'cssInline' => '#outlook a,h1,h2,h3,h4,h5,h6,p{padding:0}#templateFooter,#templatePreheader{background-color:#FAFAFA}#templateFooter,#templateHeader,#templatePreheader{border-bottom:0;padding-top:9px}#templateBody,#templateFooter,#templatePreheader{border-top:0;padding-bottom:9px}p{margin:10px 0}table{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}h1,h2,h3,h4,h5,h6{display:block;margin:0}a img,img{border:0;height:auto;outline:0;text-decoration:none}#bodyCell,#bodyTable,body{height:100%;margin:0;padding:0;width:100%}img{-ms-interpolation-mode:bicubic}.ExternalClass,.ReadMsgBody{width:100%}a,blockquote,li,p,td{mso-line-height-rule:exactly}a[href^=sms],a[href^=tel]{color:inherit;cursor:default;text-decoration:none}a,blockquote,body,li,p,table,td{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}.ExternalClass,.ExternalClass div,.ExternalClass font,.ExternalClass p,.ExternalClass span,.ExternalClass td{line-height:100%}a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}h1,h2,h3,h4{color:#202020;font-style:normal;font-weight:700;line-height:125%;letter-spacing:normal;text-align:left;font-family:Helvetica}#bodyCell{padding:10px;border-top:0}.templateContainer{max-width:600px!important;border:0}a.mcnButton{display:block}.mcnImage{vertical-align:bottom}.mcnTextContent{word-break:break-word}.mcnTextContent img{height:auto!important}.mcnDividerBlock{table-layout:fixed!important}#bodyTable,body{background-color:#e6e6e6}h1{font-size:26px}h2{font-size:22px}h3{font-size:20px}h4{font-size:18px}#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{color:#656565;font-family:Helvetica;font-size:12px;line-height:150%;text-align:left}#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{color:#656565;font-weight:400;text-decoration:underline}#templateHeader{background-color:#FFF;border-top:0;padding-bottom:0}#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{color:#202020;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left}#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{color:#2BAADF;font-weight:400;text-decoration:underline}#templateBody{background-color:#FFF;border-bottom:2px solid #EAEAEA;padding-top:0}#templateBody .mcnTextContent,#templateBody .mcnTextContent p{color:#202020;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left}#templateBody .mcnTextContent a,#templateBody .mcnTextContent p a{color:#2BAADF;font-weight:400;text-decoration:underline}#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{color:#656565;font-family:Helvetica;font-size:12px;line-height:150%;text-align:center}#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{color:#656565;font-weight:400;text-decoration:underline}',
				// set mPDF properties on the fly
				'options' => ['title' => 'Krajee Report Title'],
				// call mPDF methods on the fly
			]);
			$pdf->render();
			//print_r($pdf_file_path);die;
			if($upload)
			$this->uploadMedia($file_name,$pdf_file_path,$reservation_id);
			return $pdf_file_path;
		}catch (\Exception $e){
				return false;
		}
	}
	public function uploadMedia($file_name,$pdf_file_path,$reservation_id)
	{
		$connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $media =  new Media();
        $media->file_name = pathinfo($file_name,PATHINFO_BASENAME);
        $media->file_url =Yii::$app->urlManager->createAbsoluteUrl('/web/uploads/temp/'.$file_name);
        $media->file_path =  $pdf_file_path;
        $media->original_name = $file_name;
        $media->staus       =   ACTIVE;
        $media->created_date  = time();
        $media->updated_date  = time();
        $media->created_by = yii::$app->user->identity->id;
        $media->updated_by = yii::$app->user->identity->id;
        $media->is_deleted  =   NOT_DELETED;
        if($media->save()){
            $reservationMedia = new ReservationMedia();
            $reservationMedia->reservation_id = $reservation_id;
            $reservationMedia->media_id = $media->id;
            if($reservationMedia->save()){
                $transaction->commit();
            }else{
                $transaction->rollback();               
            }
        }else{
             $transaction->rollback();             
        }
	}

	private function sendFax($pdf,$fax_number)
	{
		$username          = FAX_USERNAME; // Insert your InterFAX username here
		$password          = FAX_PASSWORD;  // Insert your InterFAX password here	
		$faxnumber         = (FAX_TEST)?FAX_ADMIN:$fax_number;  // Enter the destination fax number here, e.g. +497 116 589 658
		$filename          = $pdf; // A file in your filesystem
		$filetype          = 'PDF'; // File format; supported types are listed at 

		// Open File
		if( !($fp = fopen($filename, "r"))){
         // Error opening file
			$responseNegetive = 0;
			return $responseNegetive;			
		}

       // Read data from the file into $data
		$data = "";
		while (!feof($fp)) $data .= fread($fp,filesize($pdf));
		fclose($fp);

        $client = new SoapClient(FAXURL);
		$params = new \stdClass();
		$params->Username  = $username;
		$params->Password  = $password;
		$params->FaxNumber = $faxnumber;
		$params->FileData  = $data;
		$params->FileType  = $filetype;


		$result = $client->Sendfax($params);
	//	print_r([$result->SendfaxResult]);die;
		return $result->SendfaxResult;
	}
}