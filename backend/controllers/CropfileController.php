<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 5/10/16
 * Time: 12:36 PM
 */
namespace backend\controllers;

use common\models\BuyLeads;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use common\models\Media;
use yii\widgets\ActiveForm;
use yii\helpers\FileHelper;
class CropfileController extends Controller {

    public function actionIndex() {

        return $this->render('index');

    }
    public function actionImageCropToFile($id) {
        /*
        *	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
        */
        /*
        */
        $imgUrl = $_POST['imgUrl'];
// original sizes
        $imgInitW = $_POST['imgInitW'];
        $imgInitH = $_POST['imgInitH'];
// resized sizes
        $imgW = $_POST['imgW'];
        $imgH = $_POST['imgH'];
// offsets
        $imgY1 = $_POST['imgY1'];
        $imgX1 = $_POST['imgX1'];
// crop box
        $cropW = $_POST['cropW'];
        $cropH = $_POST['cropH'];
// rotation angle
        $angle = $_POST['rotation'];

        $jpeg_quality = 100;
        $newname = "croppedImg_".rand();
        $what = getimagesize($imgUrl);

        switch(strtolower($what['mime']))
        {
            case 'image/png':
                $img_r = imagecreatefrompng($imgUrl);
                $source_image = imagecreatefrompng($imgUrl);
                $type = '.png';
                break;
            case 'image/jpeg':
                $img_r = imagecreatefromjpeg($imgUrl);
                $source_image = imagecreatefromjpeg($imgUrl);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                $img_r = imagecreatefromgif($imgUrl);
                $source_image = imagecreatefromgif($imgUrl);
                $type = '.gif';
                break;
            default: die('image type not supported');
        }
     
            //my copy
        $upload = Yii::getAlias('@backend')."/web/uploads/";
        if(!file_exists($upload))  {
             //mkdir($upload,0777);
            FileHelper::createDirectory($upload, $mode = 0777, $recursive = true);

        }
        $card_upload_path =  Yii::getAlias('@backend')."/web/uploads/$id/";
        if(!file_exists($card_upload_path))
        {
            FileHelper::createDirectory($card_upload_path, $mode = 0777, $recursive = true);
        }
        //end my copy


                $path = Yii::getAlias('@backend')."/web/uploads/$id/";
                $url = Yii::$app->urlManager->createAbsoluteUrl("uploads/$id/".$newname.$type);

     
       // $path = Yii::getAlias('@backend')."/web/uploads/temp/";
       // $newname = "croppedImg_".rand();
        $output_filename = $path.$newname;

// uncomment line below to save the cropped image in the same location as the original image.
//$output_filename = dirname($imgUrl). "/croppedImg_".rand();

       


//Check write Access to Directory

        if(!is_writable(dirname($output_filename))){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t write cropped File'
            );
        }else{
            // resize the original image to size of editor
            $resizedImage = imagecreatetruecolor($imgW, $imgH);
            imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
            // rotate the rezized image
            $rotated_image = imagerotate($resizedImage, -$angle, 0);
            // find new width & height of rotated image
            $rotated_width = imagesx($rotated_image);
            $rotated_height = imagesy($rotated_image);
            // diff between rotated & original sizes
            $dx = $rotated_width - $imgW;
            $dy = $rotated_height - $imgH;
            // crop rotated image to fit into original rezized rectangle
            $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
            imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
            imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
            // crop image into selected area
            $final_image = imagecreatetruecolor($cropW, $cropH);
            imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
            imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
            // finally output png image
            //imagepng($final_image, $output_filename.$type, $png_quality);
            imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
          //  $id = 555;
            $media_id=$this->mediaUpload($output_filename,$url);
            $response = Array(
                "status" => 'success',
                "id"=>$media_id,
                "url" => $url,
            );
        }
        print json_encode($response);

    }

    public function actionImageSaveToFile() {
        /*
     *	!!! THIS IS JUST AN EXAMPLE !!!, PLEASE USE ImageMagick or some other quality image processing libraries
     */
        //my copy
$upload = Yii::getAlias('@backend')."/web/uploads/";
        if(!file_exists($upload))  {
             //mkdir($upload,0777);
            FileHelper::createDirectory($upload, $mode = 0777, $recursive = true);

        }
        $temp_upload_path =  Yii::getAlias('@backend')."/web/uploads/temp/";
        if(!file_exists($temp_upload_path))
        {
            FileHelper::createDirectory($temp_upload_path, $mode = 0777, $recursive = true);
        }

        //end my copy

        $imagePath  =   Yii::getAlias('@backend')."/web/uploads/temp/";
       // print_r($imagePath);die;
        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
        $temp = explode(".", $_FILES["img"]["name"]);
        $extension = end($temp);

        //Check write Access to Directory

        if(!is_writable($imagePath)){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t upload File; no write Access'
            );
            print json_encode($response);
            return;
        }

        if ( in_array($extension, $allowedExts))
        {
            if ($_FILES["img"]["error"] > 0)
            {
                $response = array(
                    "status" => 'error',
                    "message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
                );
            }
            else
            {

                $filename = $_FILES["img"]["tmp_name"];
                list($width, $height) = getimagesize( $filename );

                move_uploaded_file($filename,  $imagePath . $_FILES["img"]["name"]);

                $response = array(
                    "status" => 'success',
                    "url" => Yii::$app->urlManager->createAbsoluteUrl("uploads/temp/".$_FILES["img"]["name"]),
                    "width" => $width,
                    "height" => $height
                );

            }
        }
        else
        {
            $response = array(
                "status" => 'error',
                "message" => REST_DEFAULT_MESSAGE_STRING,
                //"message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
            );
        }

        print json_encode($response);
    }


    protected function mediaUpload($uploadfile,$url){
        $result = [];

        $media = new Media();
        $media->file_name   =   'logo'.time();
        $media->file_path   =   $uploadfile;
        $media->file_url    =  $url;
        $media->created_by  =   yii::$app->user->identity->id;
        $media->original_name = "name";
        $media->staus       =   ACTIVE;
        $media->created_date  = time();
        $media->updated_date  = time();
        $media->updated_by = yii::$app->user->identity->id;
        $media->is_deleted  =   NOT_DELETED;
        if($media->save()){
            return $media->id;
        }else{
            //print_r($media->getErrors());die;
            return false;
        }
    }

}

?>

