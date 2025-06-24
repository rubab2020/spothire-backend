<?php

namespace App\Http\Controllers;

use App\SpotHire\Helpers\EncodeHelper;
use Illuminate\Http\Request;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\User;
use App\CompanyImage;
use App\CompanyBackground;

class CompanyBackgroundController extends ApiController
{
    /**
    * @var integer
    **/
    private $clientInfo;
    private $encodeHelper;

    function __construct(EncodeHelper $encodeHelper)
    {
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
    }

    public function getCompanyBackground(){
        $data = CompanyBackground::where('user_id', $this->clientInfo->id)->first();
        return $this->respond(['data'=> [
            'industry' => $data['industry'],
            'location' => $data['location'],
            'inception_date' => $data['inception_date'],
            'annual_turnover' => $data['annual_turnover']
        ]]);

    }

    public function saveCompanyBackground(Request $request){
        $data = $request->all();
        $background = CompanyBackground::where('user_id',$this->clientInfo->id)->first();
        $background = $background ?: new CompanyBackground;
        $background->user_id = $this->clientInfo->id;
        $background->industry = $data['industry'];
        $background->location = $data['location'];
        $background->location_data = $data['location_data']? serialize($data['location_data']): null;
        $background->inception_date = $data['inception_date'];
        $background->annual_turnover = $data['annual_turnover'];
        $background->save();

        return $this->respondCreatingResource('Company background saved successfully');
    }

    public function getCompanyValue(){
        $totalEmployees = null;

        $companyBackground = CompanyBackground::where('user_id', $this->clientInfo->id)->first();
        if($companyBackground) 
            $totalEmployees = $companyBackground->total_employees;

        $images = CompanyImage::where('user_id', $this->clientInfo->id)->get();
        $images = $this->transformImages($images);

        return $this->respond([
            'data'=> [
                'total_employees' => $totalEmployees,
                'images' => $images
            ]
        ]);
    }


    public function saveCompanyValue(Request $request){
        $data = json_decode($request->input('data'), true);
        $alluploadImages = $request->file('images');

        $this->saveTotalEmployees($data['total_employees']);
        $this->saveImages($data['images'], $alluploadImages);

        return $this->getCompanyValue();
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function saveImages($images, $alluploadImages)
    {
        $uploadImgIndex = 0;

        foreach($images as $image) {
            if($image['id']){ // exsiting data
                $obj = CompanyImage::find($this->encodeHelper->decodeData($image['id']));
                if($obj == null) continue;
                
                if($image['image'] == 'delete'){
                    $this->deleteOldImage($obj->image);
                    $this->deleteOldImage($obj->image_sm);

                    $obj->delete();
                }
                else{
                    if($image['image'] == null) continue;

                    $imgName = $this->saveImage($alluploadImages[$uploadImgIndex], null, 500);
                    if($imgName == null) continue;
                    
                    $this->deleteOldImage($obj->image);
                    $this->deleteOldImage($obj->image_sm);

                    $obj->image = $imgName;
                    $obj->image_sm = $this->saveImage($alluploadImages[$uploadImgIndex], null, 180);
                    
                    $obj->description = $image['description'];
                    $obj->save(); 

                    $uploadImgIndex++;
                }
            }
            else{ // new data
                if($image['image'] == null) continue;

                $obj = new CompanyImage();

                $imgName = $this->saveImage($alluploadImages[$uploadImgIndex], null, 500);
                if($imgName == null) continue;

                $this->deleteOldImage($obj->image);
                $this->deleteOldImage($obj->image_sm);

                $obj->image = $imgName;
                $obj->image_sm = $this->saveImage($alluploadImages[$uploadImgIndex], null, 180);
                $obj->description = $image['description'];
                $obj->user_id = $this->clientInfo->id;
                $obj->save();

                $uploadImgIndex++;
            }
        }
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    private function saveImage($image, $width, $height)
    {  
        $imgName = date('mdYHis').uniqid();
        $path = public_path('/images/profile/' . $this->encodeHelper->encodeData($this->clientInfo->id) . '/company_value_images/');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $imageLink = $path.$imgName.'.jpg';
        $img = \Image::make($image->getRealPath());
        $img->orientate();
        $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($imageLink);

        return $img ? $imgName.'.jpg' : null;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    private function deleteOldImages($images)
    {
        foreach($images as $item){
            $this->deleteOldImage($item->image);
            $this->deleteOldImage($item->image_sm);
        }
    }

    /**
     * delete old image
     *
     * @param string $iamge
     * @return void
     **/
    public function deleteOldImage($image)
    {   
        if($image == null) return;

        $fileName = CompanyImage::getPhotoPath($this->clientInfo->id).$image;
        if(file_exists($fileName))  \File::delete($fileName);
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function saveTotalEmployees($totalEmployees)
    {
        $background = CompanyBackground::where('user_id', $this->clientInfo->id)->first();
        if(!$background) {
            $background = new CompanyBackground;
        }
        $background->user_id = $this->clientInfo->id;
        $background->total_employees = $totalEmployees;
        $background->save();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function isImageUploaded($images)
    {
        foreach($images as $image) {
            if($image['image']) {
                return true;
            }
        }

        return false;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformImages($images)
    {
        return array_map([$this, 'transformImage'], is_array($images) ? $images : $images->toArray());
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformImage($image)
    {
        return [
            'id' => $this->encodeHelper->encodeData($image['id']),
            'image' => CompanyImage::getPhotoPath($this->clientInfo->id).$image['image'],
            'description' => $image['description'],
        ];
    }
}
