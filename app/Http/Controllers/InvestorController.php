<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Auth;
use App\Models\Risk;
use DB;

class InvestorController extends Controller  {
    /**
     * Display a listing of the resource.
     * uses from admin
     * @return Response
     */
    
      public function admin_index()
      {
      $sessionid = Session::get('userid');
      if (!isset($sessionid) && $sessionid=='')
      {
      return Redirect::route('admin')->with('message','Please Login');
      }
      $all_investors= Investor::all();
      return View::make('admin.investors.index')->with('all_investors', $all_investors);
      }
     

    /**
     * Show the form for creating a new resource.
     * Uses from admin
     * @return Response
     */
    
      public function create()
      {
      $sessionid = Session::get('userid');
      if (!isset($sessionid) && $sessionid=='')
      {
      return Redirect::route('admin')->with('message','Please Login');
      }

      $categoryList=Category::with('children')->where('parent_id',0)->get();

      return View::make('admin.investors.create',array('categoryList' => $categoryList));
      }
     

    public function edit_profile() {

        if (Auth::check()) {
            $id = Auth::id();
            $email = Auth::user()->user_email;
            $categoryuser = array();
            $categoryuser = CategoryUser::where('user_id', $id)->lists('category_id');
            $investor = Investor::find($id);
            $user = User::find($id);

            $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', '1')->orderBy('name')->get();

            $investortypeList = InvestorType::where('status', '1')->orderBy('ordering')->get();

            $fundingList = FundingType::where('status', '1')->orderBy('name')->get();

            $countryList = Country::where('status', '1')->orderBy('name')->get();

            if (isset($investor) && !empty($investor)) {
                $country_id = $user->country_id;
            } else {
                $country_id = "";
            }



            $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();


            //$countryList=Country::where('status','1')->orderBy('name')->get();

            return View::make('investors.edit_profile', compact('categoryList', 'email', 'investor', 'user', 'categoryuser', 'investortypeList', 'fundingList', 'countryList', 'cityList'));
        } else {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('login');
        }
    }

    public function profile() {
        if (Auth::check()) {
            $id = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_ent = Auth::user()->filled_ent;
            $email = Auth::user()->user_email;
        } else {
            //Session::flash('flash_message', 'Please login.');
            //return Redirect::route('home');

            $id = 0;
            $user_type = 0;
            $filled_ent = 0;
            $email = 0;
        }
        if ($user_type == 'e' && $filled_ent = 0) {
            return Redirect::back();
        }

        if (isset($_GET['det']) && $_GET['det'] != '') {

            $id = stripcslashes(base64_decode($_GET['det']));
        } else {
            if ($user_type == 'i') {
                $id = Auth::id();
            }
        }


        //$investor=Investor::find($id);

        $investor = Investor::with('investertype', 'fundingtype')->where('id', $id);

        if ($investor->count() == 0) {
            //$investor = new investor;

            Session::flash('flash_message', 'Sorry!! some problem redirecting.');
            return Redirect::route('investor.list');
        }

        $investor = $investor->first();

        $user = User::where('id', $id);
        $user = $user->first();

        //$categorie_arr=CategoryUser::with('category')->where('user_id',$id);

        return View::make('investors.profile', compact('id', 'email', 'investor', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     * Uses from admin
     * @return Response
     */
    public function store() {

        if (Auth::check()) {
            $userid = Auth::id();
            $email = Auth::user()->user_email;
            $user = User::find($userid);
            $investor = Investor::find($userid);
        } else {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('login');
        }

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {
            // add investor /mentor 



            $user->name = stripslashes(trim($_POST['name']));
            $user->individual_name = stripslashes(trim($_POST['individual_name']));

            $user->zip_code = stripslashes(trim($_POST['zip_code']));

            $user->address = stripslashes(trim($_POST['address']));
            $user->country_id = stripslashes(trim($_POST['country_id']));
            $user->city_id = stripslashes(trim($_POST['city_id']));

            $user->phone = stripslashes(trim($_POST['phone']));

            $user->linkedin_url = stripslashes(trim($_POST['linkedin_url']));

            // photo upload 
            // saving invester indicator	



            if (Input::hasFile('photo')) {
                $file = Input::file('photo');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/investor_profile/', $name);
                $photo = 'investor_profile/' . $name;

                $user->photo = $photo;

                if (isset($_POST['hid_photo']) && $_POST['hid_photo'] != "" && file_exists('public/' . $_POST['hid_photo'])) {
                    unlink('public/' . $_POST['hid_photo']);
                }
            }


            $user->filled_inv = '1';
            $user->save();





            // cv upload


            if (Input::hasFile('cv')) {
                $file = Input::file('cv');
                $name = time() . '-' . $file->getClientOriginalName();
                $name = str_replace(" ", "-", $name);
                $file = $file->move('public/cv/', $name);
                $cv = 'cv/' . $name;

                $investor->cv = $cv;

                if (isset($_POST['hid_cv']) && $_POST['hid_cv'] != "" && file_exists('public/' . $_POST['hid_cv'])) {
                    unlink('public/' . $_POST['hid_cv']);
                }
            }





            $investor->type = stripslashes(trim($_POST['type']));


            $investor->investor_type = stripslashes(trim($_POST['investor_type']));

            if ($investor->investor_type != '4') {
                $investor->other_company = "";
            } else {
                $investor->other_company = stripslashes(trim($_POST['other_company'])); // if investor type is other
            }


            $investor->brief_profile = stripslashes(trim($_POST['brief_profile']));


            $investor->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
            //$investor->professional_qualification=stripslashes(trim($_POST['professional_qualification']));
            $investor->past_details = stripslashes(trim($_POST['past_details']));

            $investor->investment_objective = stripslashes(trim($_POST['investment_objective']));
            $investor->from_investment = stripslashes(trim($_POST['from_investment']));
            $investor->to_investment = stripslashes(trim($_POST['to_investment']));
            $investor->funding_type = stripslashes(trim($_POST['funding_type']));

            if ($investor->save()) {

                if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
                    // for edit section only
                    $categoryuser = CategoryUser::where('user_id', $userid)->lists('id', 'category_id');
                    if (isset($categoryuser) && !empty($categoryuser)) {
                        foreach ($categoryuser as $cat_id) {
                            $categoryuser = CategoryUser::find($cat_id);
                            $categoryuser->delete();
                        }
                    }



                    foreach ($_POST['cat_id'] as $cat_id) {

                        $category_user = new CategoryUser;
                        $category_user->category_id = $cat_id;
                        $category_user->user_id = $userid;
                        $category_user->user_type = 'i';
                        $category_user->save();
                    }
                }

                if (isset($_POST['other_category']) && !empty($_POST['other_category'])) {
                    $other_category_name = trim(strip_tags($_POST['other_category']));

                    $othercategory = new Othercategory;

                    $othercategory->user_id = $userid;
                    $othercategory->name = $other_category_name;

                    $othercategory->save();
                }

                Session::flash('flash_message', 'Profile has been updated sucessfully.');
                return Redirect::route('investor.profile');
            } else {

                Session::flash('flash_message', 'Sorry!! Profile details cannot be saved.');
                return Redirect::back();
            }
        }
    }

    public function listing() {
        if (!Auth::check()) {
            // Session::flash('flash_message', 'Please login.');
            // return Redirect::route('home');
        } else {
            $userid = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_ent = Auth::user()->filled_ent;
            if ($user_type == 'e' && $filled_inv = 0) {
                return Redirect::back();
            }
            if ($user_type == 'i') {
                return Redirect::back();
            }
        }

        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', '1')->orderBy('name')->get();

        $country_id = "";

        if (isset($_GET['country_id']) && $_GET['country_id'] != "") {
            $country_id = $_GET['country_id'];
        }

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        $category = "";
        $country_id = "";
        $city_id = "";

        //$searchbox="";
        //$sort="";

        if (isset($_GET['category']) && $_GET['category'] != "") {
            $category = stripslashes(trim($_GET['category']));
        }

        if (isset($_GET['country_id']) && $_GET['country_id'] != "") {
            $country_id = stripslashes(trim($_GET['country_id']));
        }

        if (isset($_GET['city_id']) && $_GET['city_id'] != "") {
            $city_id = stripslashes(trim($_GET['city_id']));
        }

        /*
          if(isset($_GET['searchbox']) && $_GET['searchbox']!="")
          {
          $searchbox = stripslashes(trim($_GET['searchbox']));
          }

          if(isset($_GET['sort']) && $_GET['sort']!="")
          {
          $sort = stripslashes(trim($_GET['sort']));
          }

         */



        $all_investors = Investor::leftjoin('category_users', 'category_users.user_id', '=', 'investors.id')->leftjoin('categories', 'categories.id', '=', 'category_users.category_id')->join('users', 'users.id', '=', 'investors.id')->select('category_users.category_id', 'investors.id', 'users.name', 'users.individual_name', 'users.photo', 'investors.professional_qualification', 'investors.type', 'investors.brief_profile', 'investors.investment_objective')->where('users.status', 'Active');



        // $all_investors=Investor::select('investors.id', 'investors.name', 'investors.photo', 'investors.professional_qualification', 'investors.type');

        if ($category != "") {
            $all_investors = $all_investors->where('category_users.category_id', $category);
        }

        if ($country_id != "") {
            $all_investors = $all_investors->where('users.country_id', $country_id);
        }

        if ($city_id != "") {
            $all_investors = $all_investors->where('users.city_id', $city_id);
        }

        /*

          if($searchbox!=="")
          {
          $all_investors=$all_investors->where('investors.name','like','%'.$searchbox.'%');
          $all_investors=$all_investors->orWhere('investors.highest_qualification','like','%'.$searchbox.'%');
          $all_investors=$all_investors->orWhere('investors.professional_qualification','like','%'.$searchbox.'%');
          $all_investors=$all_investors->orWhere('investors.past_details','like','%'.$searchbox.'%');
          }
         */

        $all_investors = $all_investors->orderByRaw('IF (investors.ordering IS NOT NULL, ordering, 5000000)')->orderBy('investors.id', 'desc')->groupBy('investors.id')->paginate(20);







        //$all_investors=Investor::orderBy('id', 'desc')->groupBy('investors.id')->paginate(20);

        /*
          $all_investors=Investor::orderBy('id', 'desc')->groupBy('investors.id');

          $all_investors=$all_investors->paginate(20);
         */

        return View::make('investors.list', compact('all_investors', 'categoryList', 'countryList', 'cityList'));
    }

     public function listingApi() {
        if (!Auth::check()) {
            // Session::flash('flash_message', 'Please login.');
            // return Redirect::route('home');
        } else {
            $userid = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_ent = Auth::user()->filled_ent;
            if ($user_type == 'e' && $filled_inv = 0) {
                return Redirect::back();
            }
            if ($user_type == 'i') {
                return Redirect::back();
            }
        }

        $all_investors = Investor::leftjoin('category_users', 'category_users.user_id', '=', 'investors.id')->leftjoin('categories', 'categories.id', '=', 'category_users.category_id')->join('users', 'users.id', '=', 'investors.id')->select('category_users.category_id', 'investors.id', 'users.name', 'users.individual_name', 'users.photo', 'investors.professional_qualification', 'investors.type', 'investors.brief_profile', 'investors.investment_objective')->where('users.status', 'Active');


        $all_investors = $all_investors->orderByRaw('IF (investors.ordering IS NOT NULL, ordering, 5000000)')->orderBy('investors.id', 'desc')->groupBy('investors.id')->limit(25)->get();
        $retArray = array();
        if( count($all_investors) > 0 ) {

            foreach ($all_investors as $key => $investor) {
              
                $investorPhoto = '';
                if($investor->photo!='') {
                    $investorPhoto = asset('public/'.$investor->photo);
                } else {
                    $investorPhoto = asset('public/images/noimage.png');
                }
               
               $retArray[] = array(
                        'name' => $investor->individual_name,
                        'investorPhoto' => $investorPhoto,
                        'investorDetailUrl' => URL::route('investor.profile',array('det'=>base64_encode($investor->id)))
                  );
            }
        }
        echo json_encode($retArray);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    /* 	
      public function edit($id)
      {
      //
      }
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    /*
      public function update($id)
      {
      //
      }
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    /*
      public function destroy($id)
      {
      //
      }
     */

  

    public function investor_csv() {
        $all_investor = User::join('investors', 'users.id', '=', 'investors.id')->select('users.id', 'users.user_email', 'users.status', 'users.name', 'users.individual_name', 'users.phone', 'users.photo', 'investors.id', 'investors.type', 'investors.brief_profile', 'cities.city_name')->leftJoin('cities', 'users.city_id', '=', 'cities.id')->orderByRaw('IF (ordering IS NOT NULL, ordering, 5000000)')->orderBy('investors.id', 'desc')->where('users.status', '!=', 'Delete')->get();


        $header = "Name" . "\t";
        $header .= "Company Name" . "\t";
        $header .= "Email" . "\t";
        $header .= "Phone" . "\t";
        $header .= "Want to Join" . "\t";
        $header .= "Area of interest" . "\t";
        //$header .= "Brief Profile"."\t";
        $header .= "City" . "\t";
        $header .= "\n";


        $data = '';

        foreach ($all_investor as $key => $investor) {
            $investor_id = $investor->id;

            $user_category = CategoryUser::join('categories', 'categories.id', '=', 'category_users.category_id')->select('categories.name')->where('category_users.user_id', '=', $investor_id)->get();

            $category_name = '';

            foreach ($user_category as $k => $v) {
                if ($k == 0) {
                    $category_name = addslashes($v->name);
                } else {
                    $category_name .= ", " . addslashes($v->name);
                }
            }

            $line = '';
            $line .= ($investor->individual_name != "") ? addslashes($investor->individual_name) . "\t" : "-" . "\t";
            $line .= ($investor->name != "") ? addslashes($investor->name . "\t") : "-" . "\t";
            $line .= ($investor->user_email != "") ? addslashes($investor->user_email) . "\t" : "-" . "\t";
            $line .= ($investor->phone != "") ? addslashes($investor->phone) . "\t" : "-" . "\t";
            $line .= ($investor->type != "") ? ucwords($investor->type) . "\t" : "-" . "\t";
            $line .= ($category_name != "") ? addslashes($category_name) . "\t" : "-" . "\t";
            //$line .= ($investor->brief_profile!="")?addslashes(strip_tags($investor->brief_profile))."\t":"-"."\t";
            $line .= ($investor->city_name != "") ? $investor->city_name . "\t" : "-" . "\t";

            $data .= trim($line) . "\n";
        }

        $data = str_replace("\r", "", $data);

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=investor" . '_' . date('m-d-Y_H:i') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        print "$header\n$data";
    }

    

    public function admin_store() {
        if (isset($_POST['_token']) && !empty($_POST['_token'])) {

            $email = stripslashes(trim($_POST['user_email']));
            $default_password = 'etp@' . rand(100, 999);

            if (Input::hasFile('photo')) {

                $file = Input::file('photo');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/investor_profile/', $name);
                $photo = 'investor_profile/' . $name;
            } else {
                $photo = "";
            }

            $user = new User;
            $user->user_email = $email;
            //$user->password = Hash::make($default_password);
            $user->user_type = 'i';
            $user->filled_ent = '1';
            $user->status = 'Inactive';
            $user->name = stripslashes(trim($_POST['company_name']));
            $user->individual_name = stripslashes(trim($_POST['individual_name']));

            $user->zip_code = stripslashes(trim($_POST['zip_code']));

            $user->country_id = stripslashes(trim($_POST['country_id']));
            $user->city_id = stripslashes(trim($_POST['city_id']));

            $user->address = stripslashes(trim($_POST['address']));

            $user->phone = stripslashes(trim($_POST['phone']));

            $user->linkedin_url = stripslashes(trim($_POST['linkedin_url']));

            $user->photo = $photo;


            $user_exist = User::where('user_email', $email)->where('status', '!=', 'Delete');
            if ($user_exist->count() != 0) {
                Session::flash('flash_message', 'Email id exist allready.');
                return Redirect::back();
            } else {
                if ($user->save()) {


                    /*

                      $to = $user->user_email;

                      $mail_temp=file_get_contents('public/mail_template/templ.php');

                      $subject = "Registration Sucessfull With IIMCIP.";
                      $link=HTML::linkAction('UserController@activate', 'click this link', array("val"=>base64_encode($user->id)));




                      $message="
                      <table border='0'>
                      <tr>
                      <td>Hello User ,</td>
                      </tr>

                      <tr>
                      <td>Your default password is ".$default_password.". Please change it after login. </td>
                      </tr>

                      <tr>
                      <td> Please ".$link." to activate your account.</td>
                      </tr>
                      </table>";


                      $mail_temp=str_replace("{{message}}",$message,$mail_temp);

                      //echo $message; exit;
                      // Always set content-type when sending HTML email
                      $headers = "MIME-Version: 1.0" . "\r\n";
                      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                      // More headers
                      $headers .= 'From: <'.Config::get('app.from_email').'>' . "\r\n";
                      mail($to,$subject,$mail_temp,$headers);

                      // add entreprenaur
                     */



                    $cv = "";
                    if (Input::hasFile('cv')) {
                        $file = Input::file('cv');
                        $name = time() . '-' . $file->getClientOriginalName();
                        $name = str_replace(" ", "-", $name);
                        $file = $file->move('public/cv/', $name);
                        $cv = 'cv/' . $name;
                    }






                    $investor = new Investor;
                    $investor->id = $user->id;


                    $investor->type = stripslashes(trim($_POST['type']));


                    if ($investor->investor_type != '4') {
                        $investor->other_company = "";
                    } else {
                        $investor->other_company = stripslashes(trim($_POST['other_company'])); // if investor type is other
                    }


                    $investor->investor_type = stripslashes(trim($_POST['investor_type']));
                    $investor->brief_profile = stripslashes(trim($_POST['brief_profile']));


                    $investor->cv = $cv;

                    $investor->highest_qualification = stripslashes(trim($_POST['highest_qualification']));

                    $investor->past_details = stripslashes(trim($_POST['past_details']));

                    $investor->investment_objective = stripslashes(trim($_POST['investment_objective']));
                    $investor->from_investment = stripslashes(trim($_POST['from_investment']));
                    $investor->to_investment = stripslashes(trim($_POST['to_investment']));
                    $investor->funding_type = stripslashes(trim($_POST['funding_type']));

                    if ($investor->save()) {
                        if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
                            foreach ($_POST['cat_id'] as $cat_id) {
                                $category_user = new CategoryUser;
                                $category_user->category_id = $cat_id;
                                $category_user->user_id = $user->id;
                                $category_user->user_type = 'i';
                                $category_user->save();
                            }
                        }
                        Session::flash('flash_message', 'Investor has Been added Sucessfully.');
                        return Redirect::route('admin.list_investor');
                    } else {

                        Session::flash('flash_message', 'Sorry!! Investor cannot save.');
                        return Redirect::back();
                    }
                } else {
                    Session::flash('flash_message', 'Sorry!! Investor cannot save.');
                    return Redirect::back();
                }
            }
        }
    }

    public function admin_delete_investor($id) {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }

        $user = User::find($id);

        $user->status = 'delete';

        $user->save();

        Session::flash('flash_message', 'Invester has been deleted successfully.');
        return Redirect::back();
    }

    public function edit($id) {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }



        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', 1)->orderBy('name')->get();

        $investortypeList = InvestorType::where('status', '1')->orderBy('ordering')->get();

        $fundingList = FundingType::where('status', '1')->orderBy('name')->get();

        // $investor_detail=Investor::find($id)->get();

        $investor_detail = Investor::where('id', $id);

        if ($investor_detail->count() == 0) {

            return Redirect::route('admin.list_investor');
        }


        $investor_detail = $investor_detail->get();

        $user_detail = User::where('id', $id);

        $user_detail = $user_detail->get();

        $category_user = CategoryUser::where('user_id', $id)->get();

        $category_array = array();

        foreach ($category_user as $v) {
            array_push($category_array, $v->category_id);
        }

        //echo $investor_detail[0]->name;

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $country_id = $user_detail[0]->country_id;

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {

            //-------- start for update of investor -------//

            $user = User::find($id);

            $user->name = stripslashes(trim($_POST['company_name']));
            $user->individual_name = stripslashes(trim($_POST['individual_name']));
            $user->zip_code = stripslashes(trim($_POST['zip_code']));
            $user->address = stripslashes(trim($_POST['address']));
            $user->country_id = stripslashes(trim($_POST['country_id']));
            $user->city_id = stripslashes(trim($_POST['city_id']));
            $user->phone = stripslashes(trim($_POST['phone']));

            $user->linkedin_url = stripslashes(trim($_POST['linkedin_url']));

            if (Input::hasFile('photo')) {
                $file = Input::file('photo');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/investor_profile/', $name);
                $photo = 'investor_profile/' . $name;

                $user->photo = $photo;

                if (isset($_POST['hid_photo']) && $_POST['hid_photo'] != "" && file_exists('public/' . $_POST['hid_photo'])) {
                    unlink('public/' . $_POST['hid_photo']);
                }
            }

            $user->save();

            $investor = Investor::find($id);

            /*
              $investor->type=stripslashes(trim($_POST['type']));
              //$investor->photo=$photo;
              $investor->phone=stripslashes(trim($_POST['phone']));
              $investor->name=stripslashes(trim($_POST['company_name']));
              $investor->address=stripslashes(trim($_POST['address']));
              $investor->highest_qualification=stripslashes(trim($_POST['highest_qualification']));
              $investor->professional_qualification=stripslashes(trim($_POST['professional_qualification']));
              $investor->past_details=stripslashes(trim($_POST['past_details']));
             */

            $investor->type = stripslashes(trim($_POST['type']));



            $investor->investor_type = stripslashes(trim($_POST['investor_type']));


            if ($investor->investor_type != '4') {
                $investor->other_company = "";
            } else {
                $investor->other_company = stripslashes(trim($_POST['other_company'])); // if investor type is other
            }

            $investor->brief_profile = stripslashes(trim($_POST['brief_profile']));


            //$investor->photo=$photo;
            $investor->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
            //$investor->professional_qualification=stripslashes(trim($_POST['professional_qualification']));
            $investor->past_details = stripslashes(trim($_POST['past_details']));

            $investor->investment_objective = stripslashes(trim($_POST['investment_objective']));
            $investor->from_investment = stripslashes(trim($_POST['from_investment']));
            $investor->to_investment = stripslashes(trim($_POST['to_investment']));
            $investor->funding_type = stripslashes(trim($_POST['funding_type']));

            $investor_ordering = stripslashes(trim($_POST['ordering']));

            $investor_ordering = (int) $investor_ordering;

            if ($investor_ordering == 0 || "") {
                $investor_ordering = NULL;
            }

            $investor->ordering = $investor_ordering;



            // cv upload


            if (Input::hasFile('cv')) {
                $file = Input::file('cv');
                $name = time() . '-' . $file->getClientOriginalName();
                $name = str_replace(" ", "-", $name);
                $file = $file->move('public/cv/', $name);
                $cv = 'cv/' . $name;

                $investor->cv = $cv;

                if (isset($_POST['hid_cv']) && $_POST['hid_cv'] != "" && file_exists('public/' . $_POST['hid_cv'])) {
                    unlink('public/' . $_POST['hid_cv']);
                }
            }





            $investor->save();

            //-------- end for update of investor -------//
            //-------- start for update of category user -------//

            if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {

                //$category_user = CategoryUser::where('user_id', '=', $id)->findOrFail()->delete();

                $category_user = CategoryUser::where('user_id', $id);

                $category_user->delete();



                foreach ($_POST['cat_id'] as $cat_id) {

                    $category_user = new CategoryUser;
                    $category_user->category_id = $cat_id;
                    $category_user->user_id = $id;
                    $category_user->user_type = 'i';
                    $category_user->save();
                }
            }




            //-------- end for update of category user -------//

            Session::flash('flash_message', 'Investor has been updated successfully.');

            return Redirect::back();

            // return Redirect::route('admin.list_investor');
        }


        return View::make('admin.investor.edit', compact('categoryList', 'investor_detail', 'user_detail', 'category_array', 'investortypeList', 'fundingList', 'countryList', 'cityList'));
    }

    public function get_startup_data() {
        $columns = array(
            0 => 'id',
            1 => 'photo',
            2 => 'individual_name',
            3 => 'name',
            4 => 'user_email',
            5 => 'phone',
        );

        // $totalData = Post::count();
        $porfolio_start_rel = new StartUpInvestorRel;
        $start_port_rel = StartUpInvestorRel::select('start_up_id')->get()->toArray();
        $statup_ids = array_column($start_port_rel, 'start_up_id');

        $totalData = Entrepreneur::join('users', 'users.id', '=', 'entrepreneurs.id')
                        ->select('users.user_email', 'users.name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id')
                        ->where('users.status', '=', 'Active')->whereNotIn('entrepreneurs.id', $statup_ids)->count();
        $totalFiltered = $totalData;
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order_0_column = isset($_POST['order.0.column']) ? $_POST['order.0.column'] : '';
        $order_0_dir = isset($_POST['order.0.dir']) ? $_POST['order.0.dir'] : '';
        $order = !empty($columns[$order_0_column]) ? $columns[$order_0_column] : '';
        $dir = !empty($_POST[$order_0_dir]) ? $_POST[$order_0_dir] : '';

        if (empty($_POST['search']['value'])) {
            $posts = Entrepreneur::join('users', 'users.id', '=', 'entrepreneurs.id')
                    ->select('users.user_email', 'users.name', 'users.individual_name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id')
                    ->where('users.status', '=', 'Active')->whereNotIn('entrepreneurs.id', $statup_ids)->offset($start)
                    ->limit($limit)
                    // ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $_POST['search']['value'];

            $posts = Entrepreneur::join('users', 'users.id', '=', 'entrepreneurs.id')
                            ->select('users.user_email', 'users.name', 'users.individual_name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id')
                            ->where('users.status', '=', 'Active')->whereNotIn('entrepreneurs.id', $statup_ids);
//                       ->Where('name', 'LIKE',"%{$search}%")
//                       ->orWhere('user_email', 'LIKE',"%{$search}%")
//                       ->orWhere('individual_name', 'LIKE',"%{$search}%")
//                       ->orWhere('phone', 'LIKE',"%{$search}%")
            $posts = $posts->where(function($qr)use($search) {
                $qr->orWhere('users.name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.user_email', 'LIKE', "%{$search}%");
                $qr->orWhere('users.individual_name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.phone', 'LIKE', "%{$search}%");
            });
            $posts = $posts->offset($start)
                    ->limit($limit)
                    // ->orderBy($order,$dir)
                    ->get();

            $totalFiltered = Entrepreneur::join('users', 'users.id', '=', 'entrepreneurs.id')
                            ->select('users.user_email', 'users.name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id')
                            ->where('users.status', '=', 'Active')->whereNotIn('entrepreneurs.id', $statup_ids);
            $totalFiltered = $totalFiltered->where(function($qr)use($search) {
                $qr->orWhere('users.name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.user_email', 'LIKE', "%{$search}%");
                $qr->orWhere('users.individual_name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.phone', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $totalFiltered->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);

                $nestedData['id'] = "<input  type='checkbox' name='markAssigned[]' class='icheck check'  data-checkbox='icheckbox_square-grey' value='{$post->id}'>";
                if (isset($post->photo) && $post->photo != "") {
                    $photo_path = asset('public/' . $post->photo);
                    $nestedData['photo'] = "<a href=" . URL::route('entrepreneur.details', array('det' => base64_encode($post->id))) . " target='_blank'><img  src='{$photo_path}'   alt='' class='dp_img'/></a>";
                } else {
                    $photo_path = asset('public/images/noimage.png');
                    $nestedData['photo'] = "<a href=" . URL::route('entrepreneur.details', array('det' => base64_encode($post->id))) . " target='_blank'><img   src='{$photo_path}'  alt='' class='dp_img'/></a>";
                }

                //$nestedData['photo'] = $post->photo;
                $nestedData['individual_name'] = $post->individual_name;
                $nestedData['name'] = "<a href=" . URL::route('entrepreneur.details', array('det' => base64_encode($post->id))) . " target='_blank'>" . $post->name . "</a>";

                $nestedData['user_email'] = $post->user_email;
                $nestedData['phone'] = $post->phone;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function get_investor_data() {
        $columns = array(
            0 => 'id',
            1 => 'photo',
            3 => 'name',
            4 => 'user_email',
            5 => 'phone',
        );

        // $totalData = Post::count();
        $porfolio_start_rel = new StartUpInvestorRel;
        $start_port_rel = StartUpInvestorRel::select('start_up_id')->get()->toArray();
        $statup_ids = array_column($start_port_rel, 'start_up_id');

        $totalData = Investor::join('users', 'users.id', '=', 'investors.id')
                        ->select('users.user_email', 'users.name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id')
                        ->where('users.status', '=', 'Active')->count();
        $totalFiltered = $totalData;
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order_0_column = isset($_POST['order.0.column']) ? $_POST['order.0.column'] : '';
        $order_0_dir = isset($_POST['order.0.dir']) ? $_POST['order.0.dir'] : '';
        $order = !empty($columns[$order_0_column]) ? $columns[$order_0_column] : '';
        $dir = !empty($_POST[$order_0_dir]) ? $_POST[$order_0_dir] : '';

        if (empty($_POST['search']['value'])) {
            $posts = Investor::join('users', 'users.id', '=', 'investors.id')
                    ->select('users.user_email', 'users.individual_name', 'users.name', 'users.photo', 'users.phone', 'investors.id')
                    ->where('users.status', '=', 'Active')->offset($start)
                    ->limit($limit)
                    // ->orderBy($order,$dir)
                    ->get();
        } else {
            $search = $_POST['search']['value'];

            $posts = Investor::join('users', 'users.id', '=', 'investors.id')
                    ->select('users.user_email', 'users.individual_name', 'users.name', 'users.photo', 'users.phone', 'investors.id')
                    ->where('users.status', '=', 'Active');

//                       ->orWhere('user_email', 'LIKE',"%{$search}%")
//                       ->orWhere('individual_name', 'LIKE',"%{$search}%")
//                       ->orWhere('phone', 'LIKE',"%{$search}%")
            $posts = $posts->where(function($qr)use($search) {
                $qr->orWhere('users.name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.user_email', 'LIKE', "%{$search}%");
                $qr->orWhere('users.individual_name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.phone', 'LIKE', "%{$search}%");
            });
            $posts = $posts->offset($start)
                    ->limit($limit)
                    ->get();
            // dd(DB::getQueryLog()); 


            $totalFiltered = Investor::join('users', 'users.id', '=', 'investors.id')
                    ->select('users.user_email', 'users.name', 'users.photo', 'users.phone', 'investors.id')
                    ->where('users.status', '=', 'Active');
            $totalFiltered = $totalFiltered->where(function($qr)use($search) {
                $qr->orWhere('users.name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.user_email', 'LIKE', "%{$search}%");
                $qr->orWhere('users.individual_name', 'LIKE', "%{$search}%");
                $qr->orWhere('users.phone', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $totalFiltered->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);

                $nestedData['id'] = "<input  type='button'  class='btn btn-small btn-info' onclick='assignInv({$post->id})' value='Assign'>";
                if (isset($post->photo) && $post->photo != "") {
                    $photo_path = asset('public/' . $post->photo);
                    $nestedData['photo'] = "<a href=" . URL::route('investor.profile', array('det' => base64_encode($post->id))) . " target='_blank'><img  src='{$photo_path}'   alt='' class='dp_img'/></a>";
                } else {
                    $photo_path = asset('public/images/noimage.png');
                    $nestedData['photo'] = "<a href=" . URL::route('investor.profile', array('det' => base64_encode($post->id))) . " target='_blank'><img   src='{$photo_path}'  alt='' class='dp_img'/></a>";
                }

                //$nestedData['photo'] = $post->photo;
                $nestedData['individual_name'] = "<a href=" . URL::route('investor.profile', array('det' => base64_encode($post->id))) . " target='_blank'>" . $post->individual_name . "</a>";
                $nestedData['name'] = $post->name;
                $nestedData['user_email'] = $post->user_email;
                $nestedData['phone'] = $post->phone;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function assign_startups() {

        $start_up_id = $_POST['markAssigned'];
        foreach ($start_up_id as $sid) {
            $porfolio_start_rel = new StartUpInvestorRel;
            $porfolio_start_rel->start_up_id = $sid;
            $porfolio_start_rel->investor_id = $_POST['pm_id'];
            $porfolio_start_rel->save();
        }


        //  print_r(StartUpPmRel::get());
    }

    public function assign_investor_startup() {

        $inv_id = $_POST['markAssigned'];

        foreach ($inv_id as $in_id) {
            $porfolio_start_rel = new StartUpInvestorRel;
            $porfolio_start_rel->investor_id = $in_id;
            $porfolio_start_rel->start_up_id = $_POST['s_id'];
            $porfolio_start_rel->save();
        }
    }

    public function mystartup() {
        require_once Config::get('app.base_url')."common/helpers.php";
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $investor_id = Auth::id();
        $user_type = Auth::user()->user_type;
        if ($user_type != 'i') {
            return Redirect::route('home');
        }
        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', '1')->orderBy('name')->get();
        $startupstageList = StartupStage::where('status', '1')->orderBy('name')->get();
        $country_id = "";

        if (isset($_GET['country_id']) && $_GET['country_id'] != "") {
            $country_id = $_GET['country_id'];
        }

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        $category = "";
        $start_up_stage = "";
        $country_id = "";
        $city_id = "";

        // $searchbox="";
        //$sort="";

        if (isset($_GET['category']) && $_GET['category'] != "") {
            $category = stripslashes(trim($_GET['category']));
        }

        if (isset($_GET['start_up_stage']) && $_GET['start_up_stage'] != "") {
            $start_up_stage = stripslashes(trim($_GET['start_up_stage']));
        }

        if (isset($_GET['country_id']) && $_GET['country_id'] != "") {
            $country_id = stripslashes(trim($_GET['country_id']));
        }

        if (isset($_GET['city_id']) && $_GET['city_id'] != "") {
            $city_id = stripslashes(trim($_GET['city_id']));
        }



        $start_port_rel = StartUpInvestorRel::select('start_up_id')->where('investor_id', $investor_id)->get()->toArray();
        $statup_ids = array_column($start_port_rel, 'start_up_id');
        //echo '<pre>'; dd($statup_ids);die();

        $all_entrepreneurs = Entrepreneur::leftjoin('category_users', 'category_users.user_id', '=', 'entrepreneurs.id')
                ->leftjoin('categories', 'categories.id', '=', 'category_users.category_id')
                ->join('users', 'users.id', '=', 'entrepreneurs.id')
                ->leftjoin('start_up_stages', 'entrepreneurs.start_up_stage', '=', 'start_up_stages.id')
                ->select('category_users.category_id', 'entrepreneurs.id', 'users.name', 'users.photo', 'entrepreneurs.founding_year', 'entrepreneurs.website', 'entrepreneurs.fund_require', 'entrepreneurs.summary_start_up', 'start_up_stages.name')
                ->where('users.status', 'Active');
        if ($category != "") {
            $all_entrepreneurs = $all_entrepreneurs->where('category_users.category_id', $category);
        }

        if ($start_up_stage != "") {
            $all_entrepreneurs = $all_entrepreneurs->where('entrepreneurs.start_up_stage', $start_up_stage);
        }

        if ($country_id != "") {
            $all_entrepreneurs = $all_entrepreneurs->where('users.country_id', $country_id);
        }

        if ($city_id != "") {
            $all_entrepreneurs = $all_entrepreneurs->where('users.city_id', $city_id);
        }
        $all_entrepreneurs = $all_entrepreneurs->whereIn('users.id', $statup_ids);
        $all_entrepreneurs = $all_entrepreneurs->orderBy('id', 'desc')->groupBy('entrepreneurs.id');

        $all_entrepreneurs = $all_entrepreneurs->paginate(20);
        //dd(DB::getQueryLog()); 
        // return View::make('entrepreneurs.list',compact('all_entrepreneurs', 'categoryList','startupstageList','countryList','cityList'));
        return $returnHTML = View::make('investors.startup_list', compact('all_entrepreneurs', 'categoryList', 'startupstageList', 'countryList', 'cityList'));
    }

    public function add_task() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }

        $user_type = Auth::user()->user_type;
        if ($user_type != 'i') {
            return Redirect::route('home');
        }
        $start_up_id = Input::get('start_up_id');
        $userId = Auth::id();
        $start_port_rel = StartUpPmRel::where('start_up_id', '=', $start_up_id)
                        ->select('pm_id')->get()->toArray();
        $pm_ids = array_column($start_port_rel, 'pm_id');
        $investor_ids = StartUpInvestorRel::where('start_up_id', '=', $start_up_id)->select('investor_id')->get()->toArray();
        $investor_ids = array_column($investor_ids, 'investor_id');


        $investor_details = User::whereIn('id', $investor_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
        $startup_details = User::where('id', '=', $start_up_id)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
        $pm_details = User::whereIn('id', $pm_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
        $start_up = User::find($start_up_id);
        $taskHistory = TaskList::leftJoin('users', 'users.id', '=', 'task_list.start_up_id')
                ->leftJoin('users as pm', 'pm.id', '=', 'task_list.assignd_by')
                ->leftJoin('users as assigned', 'assigned.id', '=', 'task_list.assigned_to')
                ->select('task_list.id', 'task_list.title', 'task_list.answer', 'task_list.description', 'pm.user_type', 'task_list.created_at', 'task_list.dead_line', 'task_list.assignd_by', 'task_list.is_complete', 'users.id as startup_id', 'users.name as startup_name', 'pm.individual_name as assigned_by', 'assigned.individual_name as assigned_to', 'assigned.user_type as assigned_user_type', 'task_list.mentor_view')
                ->where('start_up_id', '=', $start_up_id)->orderBy('task_list.is_complete', 'ASC')
                ->orderBy('task_list.created_at', 'DESC')
                ->paginate(15);

        if (!empty($start_up_id)) {
            $assigned_to['Startup'] = User::select('users.id', 'users.name', 'users.user_type')->find($start_up_id);
        }
        if (!empty($pm_ids)) {
            $assigned_to['PM'] = User::select('users.id', 'users.individual_name as name', 'users.user_type')->find($pm_ids[0]);
        }
        if (!empty($userId)) {
            $assigned_to['Mentor'] = User::select('users.id', 'users.individual_name as name', 'users.user_type')->find($userId);
        }
        $task_category = TaskCategory::where('is_active', '=', 1)->get();
        $data = [
            'start_up_id' => $start_up_id,
            'task_category' => $task_category,
            'investor_details' => $investor_details,
            'startup_details' => $startup_details,
            'taskHistory' => $taskHistory,
            'pm_details' => $pm_details,
            'assigned_to' => $assigned_to,
            'start_up' => $start_up
        ];

        return $returnHTML = View::make('investors.add_task')->with($data);
    }

    public function get_pm($startup_id) {
        $start_pm_rel = StartUpPmRel::where('start_up_id', $startup_id)->get();
        return $start_pm_rel;
    }

    public function create_task() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userId = Auth::id();

        $user_type = Auth::user()->user_type;
        $user_name = Auth::user()->individual_name;
        $user_email = Auth::user()->user_email;
        $startup_id = $_POST['start_up_id'];
        $taskDetails = array();
        if ($user_type != 'i') {
            return Redirect::route('home');
        }
        if (isset($_POST['submit'])) {
            $pm = $this->get_pm($startup_id);
            $pm_id = isset($pm[0]->pm_id) ? $pm[0]->pm_id : '';
            if (!empty($pm_id)) {
                $pm_email = User::find($pm_id)->user_email;
            } else {
                $pm_email = '';
            }
            $titleArr = $_POST['title'];
            $descArr = $_POST['description'];
            $deadLineArr = $_POST['dead_line'];
            //$taskCatArr = $_POST['task_cat_id'];
            $assignTo = $_POST['assigned_to'];


            if (!empty($titleArr)) {
                for ($i = 0; $i < count($titleArr); $i++) {
                    if (!empty($titleArr[$i])) {
                        $taskList = new TaskList;
                        $taskList->title = $titleArr[$i];
                        $taskList->description = $descArr[$i];
                        $taskList->dead_line = $deadLineArr[$i];
                        $taskList->start_up_id = $_POST['start_up_id'];
                        $taskList->assignd_by = $userId;
                        $taskList->assigned_to = $assignTo[$i];
                        //$taskList->task_cat_id = $taskCatArr[$i];
                        $taskDetails[$i]['title'] = $titleArr[$i];
                        $taskDetails[$i]['description'] = $descArr[$i];
                        $taskDetails[$i]['dead_line'] = $deadLineArr[$i];
                        $assigned_by = $user_name;
                        $start_up_id = $_POST['start_up_id'];
                        $taskList->save();
                    }
                }
                $this->send_task_email($taskDetails, $start_up_id, $assigned_by, $pm_email);
            }
        }
        Session::flash('flash_message', 'Task Added Successfully');
        return Redirect::back();
    }

    public function send_task_email($taskDetails, $start_up_id, $assigned_by, $pm_email) {
        require_once Config::get('app.base_url') . "common/helpers.php";

        $startUp = User::find($start_up_id);
        $startup_to = $startUp->user_email;
        $startup_name = $startUp->name;
        $subject = 'New task for ' . $startup_name . ' Added by ' . $assigned_by . '(Mentor)';
        $message = "<h3>Task Details</h3>";
        $message .= "<b>Startup Name: " . $startup_name;
        $message .= "<table width='100%' border='1'><thead><tr><th></th><th>Title</th><th>Description</th><th>Deadline</th><th></th></tr></thead><tbody>";
        $i = 0;
        foreach ($taskDetails as $task) {
            $i++;
            $link = "<a href='" . Config::get('app.url') . "/entrepreneur/task_list'>Details</a>";
            $message .= "<tr><td>" . $i . "</td><td>" . $task['title'] . "</td><td>" . $task['description'] . "</td><td>" . date('jS M Y', strtotime($task['dead_line'])) . "</td><td>" . $link . "</td></tr>";
        }

        $message .= "</tbody></table>";


        $send = sendEmailCommon($startup_to, $subject, $message);
        if (!empty($pm_email)) {
            //uncomment below line to send email to PM
            //  $send=  sendEmailCommon($pm_email,$subject,$message);
        }

        return true;
    }

    public function review_task() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $user_type = Auth::user()->user_type;
        if ($user_type != 'i') {
            return Redirect::route('home');
        }
        $userId = Auth::id();
        $task_id = Input::get('task_id');
        $url = Input::get('relation') ? Input::get('relation') : '';
        $taskList = TaskList::find($task_id);
        $start_up_id = $taskList->start_up_id;
        $taskList->mentor_view = 1;
        $taskList->save();
        $taskReview = TaskReview::where('task_id', $task_id)->get();
        $taskReview = TaskReview::leftJoin('users', 'users.id', '=', 'task_review.reviewed_by')
                        ->select('task_review.id', 'task_review.remarks', 'users.user_type', 'task_review.created_at', 'users.id', 'users.individual_name', 'users.name')
                        ->where('task_review.task_id', '=', $task_id)->orderBy('task_review.created_at', 'DESC')->get();
        $data = [
            'task_id' => $task_id,
            'taskReview' => $taskReview,
            'investor_id' => $userId,
            'taskList' => $taskList,
            'start_up_id' => $start_up_id,
            'url' => $url
        ];

        return $returnHTML = View::make('investors.task_review')->with($data);
    }

    public function create_review() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userId = Auth::id();

        if (isset($_POST['submit'])) {
            $remark = $_POST['remark'];
            $investor_id = $_POST['investor_id'];
            $task_id = $_POST['task_id'];
            $taskList = TaskList::find($task_id);
            $taskList->startup_view = 0;
            $taskList->mentor_view = 0;
            $taskList->pm_view = 0;
            $taskList->save();
            $taskReview = new TaskReview;
            $taskReview->remarks = $remark;
            $taskReview->reviewed_by = $investor_id;
            $taskReview->task_id = $task_id;
            $taskReview->save();

            //database insert query goes here



            Session::flash('flash_message', 'Task Remark Added Successfully');
            //return Redirect::back();
            return Redirect::route('investor.task_list', 'start_up_id=' . $taskList->start_up_id);
        }
    }

    public function task_list() {
       echo $user_type = Auth::user()->user_type; die;
        if ($user_type != 'i') {
            return Redirect::route('home');
        }
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $start_up_id = Input::get('start_up_id');
            $userid = Auth::user()->id;
            $start_port_rel = StartUpPmRel::where('start_up_id', '=', $start_up_id)
                            ->select('pm_id')->get()->toArray();
            $pm_ids = array_column($start_port_rel, 'pm_id');
            $investor_ids = StartUpInvestorRel::where('start_up_id', '=', $start_up_id)->select('investor_id')->get()->toArray();
            $investor_ids = array_column($pm_ids, 'investor_id');


            $investor_details = User::whereIn('id', $investor_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $startup_details = User::where('id', '=', $start_up_id)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $pm_details = User::whereIn('id', $pm_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $start_up = User::find($start_up_id);
            $user = User::find($userid);
            $taskHistory = TaskList::leftJoin('users', 'users.id', '=', 'task_list.start_up_id')
                    ->leftJoin('users as pm', 'pm.id', '=', 'task_list.assignd_by')
                    ->leftJoin('users as assigned', 'assigned.id', '=', 'task_list.assigned_to')
                    ->select('task_list.id', 'task_list.title', 'task_list.answer', 'task_list.description', 'pm.user_type', 'task_list.created_at', 'task_list.dead_line', 'task_list.assignd_by', 'task_list.is_complete', 'users.id as startup_id', 'users.name as startup_name', 'pm.individual_name as assigned_by', 'assigned.individual_name as assigned_to', 'assigned.user_type as assigned_user_type', 'task_list.mentor_view')
                    ->where('start_up_id', '=', $start_up_id)->orderBy('task_list.is_complete', 'ASC')
                    ->orderBy('task_list.created_at', 'DESC')
                    ->paginate(15);

            $task_category = TaskCategory::where('is_active', '=', 1)->get();
            $data = [
                'taskHistory' => $taskHistory,
                'investor_details' => $investor_details,
                'pm_details' => $pm_details,
                'startup_details' => $startup_details,
                'start_up' => $start_up
            ];
        }

        return View::make('investors.task_list')->with($data);
    }

}
