<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Http\Requests\Admin\ImageRequest;
use App\Mail\XeGroupMail;
use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::where("deleted_at", null)->paginate(10);
        return view("pages.admin.company.index", compact("companies"));
    }

    public function companyList()
    {
        $companies = Company::where("deleted_at", null)->get();
        return response()->json(['code'=> 0, 'success'=>'Successfully ', 'data'=>$companies], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.admin.company.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $params = $request->all();
        try{


            /*$saveImage = self::saveImage( $request->file("logo") );
            $logoUrl = url("/").Storage::url("public/company/images/").$saveImage;
            $params["logo"] = $saveImage;
            $params["logo_url"] = $logoUrl;*/

            $createCompany = Company::createCompany( $params );
            if( $createCompany ){

                $details = [
                    'title' => 'Your Company registered on XE-Group Platform.',
                    'body' => 'Verify email account to get more. \n Email: '.$createCompany->email
                ];

                if( !empty($createCompany->email) ){
                    Mail::to($createCompany->email)->send(new XeGroupMail($details));
                }

                return response()->json(['code'=> 0, 'success'=>'Successfully '], 200);
            }
            abort( 403, "Not Created Company" );

        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $company = Company::find( $id );
        return view("pages.admin.company.edit", compact("id"));
    }

    public function getCurrentEditCompany($id)
    {
        $company = Company::find( $id );
        return response()->json(['code'=> 0, 'success'=>'Successfully ', 'data'=>$company], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $id)
    {
        $params = $request->all();
        $companyId = $id;

        try{
            //$company = Company::find($companyId);
            //$logoExist = $company->logo;
            /*if( !empty($company->logo) && !empty($request->file("logo")) ){
                if( Storage::exists( "public/company/images/".$logoExist ) ){
                    Storage::delete( "public/company/images/".$logoExist );
                }

                $saveImage = self::saveImage( $request->file("logo") );
                $logoUrl = url("/").Storage::url("public/company/images/").$saveImage;
                $params["logo"] = $saveImage;
                $params["logo_url"] = $logoUrl;
            }*/

            $updateCompany = Company::updateCompany( $companyId, $params );
            if( $updateCompany ){
                return response()->json(['code'=> 0, 'success'=>'Successfully '], 200);
            }
            abort( 403, "Not Created Company" );

        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companyId = $id;

        try{
            $deleteCompany = Company::deleteCompany( $companyId );
            if( $deleteCompany ){
                return redirect()->route("admin.company.index" );
            }
            abort( 403, "Not deleted Company" );

        } catch ( \Exception $e ){
            abort( 403, $e );
        }
    }


    public function uploadCompanyLogo(ImageRequest $request){

        $saveImage = self::saveImage( $request->file("logo") );
        $logoUrl = url("/").Storage::url("public/company/images/").$saveImage;
        $params["logo"] = $saveImage;
        $params["logo_url"] = $logoUrl;

        return response()->json(['code'=> 0, 'success'=>'Successfully ', "data"=>$params], 200);
    }


    public function saveImage( $img ){
        $file = $img;

        // Get filename with extension
        $filenameWithExt = $file->getClientOriginalName();

        // Get file path
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        // Remove unwanted characters
        $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
        $filename = preg_replace("/\s+/", '-', $filename);

        // Get the original image extension
        $extension = $file->getClientOriginalExtension();

        // Create unique file name
        $fileNameToStore = $filename.'_'.time().'.'.$extension;

        // Refer image to method resizeImage
        $save = $this->resizeImage($file, $fileNameToStore);
        return $fileNameToStore;

    }

    public function resizeImage($file, $fileNameToStore) {
        // Resize image
        $resize = Image::make($file)->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        // Create hash value
        $hash = md5($resize->__toString());

        // Prepare qualified image name
        $image = $hash."jpg";

        // Put image to storage
        $save = Storage::put("public/company/images/{$fileNameToStore}", $resize->__toString());

        /*$imageName = time().$companyId.'.'.$request->logo->extension();
        // $request->logo->move(public_path('company/images'), $imageName);
        $request->logo->storeAs('public/company/images', $imageName)->resize(100,100);
        $logoUrl = url("/").Storage::url("public/company/images/").$imageName;*/

        if($save) {
            return true;
        }
        return false;
    }
}
