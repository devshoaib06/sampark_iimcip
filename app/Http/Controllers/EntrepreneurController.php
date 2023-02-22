<?php
use Illuminate\Support\Facades\Response;

class EntrepreneurController extends \BaseController {

    /**
     * Display a listing of the resource.
     * uses from admin
     * @return Response
     */
    public function admin_index() {

        $sessionid = Session::get('userid');
        require_once Config::get('app.base_url')."common/helpers.php";
        $status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : 'Active';
        $search_name = !empty($_REQUEST['search_name']) ? $_REQUEST['search_name'] : '';
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }
        $all_entrepreneurs = Entrepreneur::join('users', 'users.id', '=', 'entrepreneurs.id')->select('users.user_email', 'users.name', 'users.photo', 'users.phone', 'startup_template', 'entrepreneurs.id');
        $all_entrepreneurs->where(function($qr)use($search_name) {
            $qr->where('users.name', 'LIKE', "%{$search_name}%");
            $qr->orWhere('users.user_email', 'LIKE', "%{$search_name}%");
            $qr->orWhere('users.individual_name', 'LIKE', "%{$search_name}%");
        });
        //$all_entrepreneurs = $all_entrepreneurs->where('users.status', '=', $status)->paginate(20);
		//$all_entrepreneurs = $all_entrepreneurs->orderByRaw('IF (ordering IS NOT NULL, ordering, 500000)')->where('users.status', '=', $status)->paginate(20);
		$all_entrepreneurs = $all_entrepreneurs->orderBy('users.id','DESC')->where('users.status', '=', $status)->paginate(20);
        //echo $status;die();
        $data = [
            'status' => $status,
            'search_name' => $search_name,
            'all_entrepreneurs' => $all_entrepreneurs
        ];

        return View::make('admin.entrepreneurs.index')->with($data);
    }

    public function admin_promoters($id = null) {
        $uid = base64_decode($id);
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }

        $user_promoter = User::where('id', $uid);

        if ($user_promoter->count() == 0) {

            return Redirect::route('admin.list_entreprenaurs');
        }



        $all_promoters = Promoter::where('user_id', $uid);

        $all_promoters = $all_promoters->get();

        return View::make('admin.entrepreneurs.promoters', compact('all_promoters', 'id'));
    }

    public function admin_add_promoters() {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }
        $uid = $_GET['uid'];
        $userid = base64_decode($uid);
        $user = User::find($userid);
        if (isset($user) && $userid != '') {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {
                // add promoter 

                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $name = time() . '-' . $file->getClientOriginalName();
                    $file = $file->move('public/promoters_images/', $name);
                    $image = 'promoters_images/' . $name;
                } else {
                    $image = "";
                }

                $promoter = new Promoter;
                $promoter->user_id = $userid;
                $promoter->name = stripslashes(trim($_POST['name']));
                $promoter->email_id = stripslashes(trim($_POST['email_id']));
                $promoter->address = stripslashes(trim($_POST['address']));
                $promoter->phone = stripslashes(trim($_POST['phone']));
                $promoter->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
                $promoter->professional_qualification = stripslashes(trim($_POST['professional_qualification']));
                $promoter->past_details = stripslashes(trim($_POST['past_details']));
                $promoter->image = $image;

                if ($promoter->save()) {
                    Session::flash('flash_message', 'Promoter has Been added Sucessfully.');
                    return Redirect::route('admin.promoters', $uid);
                } else {

                    Session::flash('flash_message', 'Sorry!! Registation cannot done.');
                    return Redirect::back();
                }
            }




            return View::make('admin.entrepreneurs.add_promoters', compact('uid'));
        } else {

            return Redirect::back();
        }
    }

    public function admin_edit_promoters($id) {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }
        $pid = $id;
        $promoter_id = base64_decode($pid);
        $promoter = Promoter::find($promoter_id);
        if (isset($promoter) && $promoter_id != '') {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {
                // add promoter 

                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $name = time() . '-' . $file->getClientOriginalName();
                    $file = $file->move('public/promoters_images/', $name);
                    $image = 'promoters_images/' . $name;

                    $promoter->image = $image;

                    if (isset($_POST['hid_photo']) && $_POST['hid_photo'] != "" && file_exists('public/' . $_POST['hid_photo'])) {
                        unlink('public/' . $_POST['hid_photo']);
                    }
                }


                //$promoter = new Promoter;
                //$promoter->user_id=$userid;
                $promoter->name = stripslashes(trim($_POST['name']));
                $promoter->email_id = stripslashes(trim($_POST['email_id']));
                $promoter->address = stripslashes(trim($_POST['address']));
                $promoter->phone = stripslashes(trim($_POST['phone']));
                $promoter->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
                $promoter->professional_qualification = stripslashes(trim($_POST['professional_qualification']));
                $promoter->past_details = stripslashes(trim($_POST['past_details']));
                //$promoter->image=$image;

                if ($promoter->save()) {
                    Session::flash('flash_message', 'Promoter has Been updated Sucessfully.');
                    return Redirect::route('admin.promoters', base64_encode($promoter->user_id));
                } else {

                    Session::flash('flash_message', 'Sorry!! Registation cannot done.');
                    return Redirect::back();
                }
            }




            return View::make('admin.entrepreneurs.edit_promoters', compact('pid', 'promoter'));
        } else {

            return Redirect::back();
        }
    }

    public function admin_delete_promoter($id) {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }




        $promoter = Promoter::where('id', $id);

        if ($promoter->count() > 0) {
            $promoter_detail = $promoter->get();

            $image_name = $promoter_detail[0]->image;

            if ($image_name != "" && file_exists('public/' . $image_name)) {
                unlink('public/' . $image_name);
            }
        }

        $promoter->delete();

        Session::flash('flash_message', 'Promoter has been deleted successfully.');
        return Redirect::back();
    }

    public function admin_add_products() {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }
        $uid = $_GET['uid'];
        $userid = base64_decode($uid);
        $user = User::find($userid);
        if (isset($user) && $userid != '') {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {
                // add product 
                $product = new Product;
                $product->user_id = $userid;
                $product->name = stripslashes(trim($_POST['name']));
                $product->description = stripslashes(trim($_POST['description']));
                if ($product->save()) {
                    $files = array();
                    $files = Input::file("images");
                    if (isset($files) && isset($files[0])) {
                        foreach (Input::file("images") as $file) {
                            $name = time() . '-' . $file->getClientOriginalName();
                            $file = $file->move('public/products_images/', $name);
                            $image = 'products_images/' . $name;
                            $product_image = new ProductImage;
                            $product_image->user_id = $userid;
                            $product_image->product_id = $product->id;
                            $product_image->image = $image;
                            $product_image->save();
                        }
                    }

                    Session::flash('flash_message', 'Project has Been added Sucessfully.');
                    return Redirect::route('admin.products', $uid);
                } else {

                    Session::flash('flash_message', 'Sorry!! project cannot added.');
                    return Redirect::back();
                }
            }

            return View::make('admin.entrepreneurs.add_products', compact('uid'));
        } else {

            return Redirect::back();
        }
    }

    public function admin_products($id = null) {
        $uid = base64_decode($id);
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }

        $user_product = User::where('id', $uid);

        if ($user_product->count() == 0) {

            return Redirect::route('admin.list_entreprenaurs');
        }

        $all_products = Product::where('user_id', $uid);

        $all_products = $all_products->get();

        return View::make('admin.entrepreneurs.products', compact('all_products', 'id'));
    }

    public function admin_delete_product($id) {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }


        $product = Product::find($id);

        $product->delete();

        $product_image = ProductImage::where('product_id', $id);

        if ($product_image->count() > 0) {
            $product_image_detail = $product_image->get();

            foreach ($product_image_detail as $v) {
                $image_name = $v->image;

                if ($image_name != "" && file_exists('public/' . $image_name)) {
                    unlink('public/' . $image_name);
                }
            }
        }

        $product_image->delete();

        Session::flash('flash_message', 'Project has been deleted successfully.');
        return Redirect::back();
    }

    /**
     * Show the form for creating a new resource.
     * Uses from admin
     * @return Response
     */
    public function create() {
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }

        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', 1)->orderBy('name')->get();

        $startupstageList = StartupStage::where('status', 1)->orderBy('name')->get();

        $supportrequiredList = SupportRequired::where('status', 1)->orderBy('name')->get();

        $structureofcompanyList = StructureofCompany::where('status', 1)->orderBy('name')->get();

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        return View::make('admin.entrepreneurs.create', array('categoryList' => $categoryList, 'startupstageList' => $startupstageList, 'supportrequiredList' => $supportrequiredList, 'structureofcompanyList' => $structureofcompanyList, 'countryList' => $countryList));
    }

    /**
     * Store a newly created resource in storage.
     * Uses from admin
     * @return Response
     */
    public function store() {
        if (isset($_POST['_token']) && !empty($_POST['_token'])) {

            $email = stripslashes(trim($_POST['user_email']));
            $default_password = 'etp@' . rand(100, 999);

            $user_exist = User::where('user_email', $email)->first();
            // checking whteher email exist
            if (isset($user_exist->user_email) && !empty($user_exist->user_email)) {
                Session::flash('flash_message', 'Email id exist allready.');
                return Redirect::back();
            } else {

                $user = new User;
                $user->user_email = $email;
                // $user->password = Hash::make($default_password);
                $user->user_type = 'e';
                $user->filled_ent = '1';
                $user->status = 'Inactive';

                $user->name = stripslashes(trim($_POST['company_name']));
                $user->individual_name = stripslashes(trim($_POST['individual_name']));

                $user->address = stripslashes(trim($_POST['address1']));
                //$entrepreneur->address2=stripslashes(trim($_POST['address2']));

                $user->country_id = stripslashes(trim($_POST['country_id']));
                $user->city_id = stripslashes(trim($_POST['city_id']));

                $user->zip_code = stripslashes(trim($_POST['zip_code']));
                $user->phone = stripslashes(trim($_POST['phone_no']));



                if (Input::hasFile('photo')) {
                    $file = Input::file('photo');
                    $name = time() . '-' . $file->getClientOriginalName();
                    $file = $file->move('public/entreprenaur_photo/', $name);
                    $photo = 'entreprenaur_photo/' . $name;

                    $user->photo = $photo;
                }


                $user->country_id2 = stripslashes(trim($_POST['country_id2']));
                $user->city_id2 = stripslashes(trim($_POST['city_id2']));

                $user->zip_code2 = stripslashes(trim($_POST['zip_code2']));

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

                     */

                    // add entreprenaur 
                    //  file upload 
                    $video = $photo = '';
                    if (Input::hasFile('video')) {

                        $file = Input::file('video');
                        $name = time() . '-' . $file->getClientOriginalName();
                        $name = str_replace(" ", "-", $name);
                        $file = $file->move('public/entreprenaur_video/', $name);
                        $video = 'entreprenaur_video/' . $name;
                    }


                    $incorporation_date = trim($_POST['incorporation_date']);
                    $incubation_start_date = trim($_POST['incubation_start_date']);
                    $dob = trim($_POST['dob']);
                    $qualification = trim($_POST['qualification']);
                    $operation_address = stripslashes(trim($_POST['operation_address']));
                    $legal_status = stripslashes(trim($_POST['legal_status']));
                    $pan = stripslashes(trim($_POST['pan']));
                    $cin = stripslashes(trim($_POST['cin']));
                    $gstin = stripslashes(trim($_POST['gstin']));
                    $share_holding_percentage = stripslashes(trim($_POST['share_holding_percentage']));

                    $entrepreneur = new Entrepreneur;
                    $entrepreneur->id = $user->id;
                    //$entrepreneur->company_name=stripslashes(trim($_POST['company_name']));
                    //$entrepreneur->type=stripslashes(trim($_POST['type']));

                    $entrepreneur->founding_year = stripslashes(trim($_POST['founding_year']));
                    $entrepreneur->start_up_stage = stripslashes(trim($_POST['start_up_stage']));

                    //$entrepreneur->address1=stripslashes(trim($_POST['address1']));
                    //$entrepreneur->country_id=stripslashes(trim($_POST['country_id']));
                    //$entrepreneur->city_id=stripslashes(trim($_POST['city_id']));
                    //$entrepreneur->zip_code=stripslashes(trim($_POST['zip_code']));
                    //$entrepreneur->phone_no=stripslashes(trim($_POST['phone_no']));
                    $entrepreneur->website = stripslashes(trim($_POST['website']));

                    //$entrepreneur->mobile_no=stripslashes(trim($_POST['mobile_no']));
                    $entrepreneur->video = $video;
                    //$entrepreneur->photo=$photo;
                    //new add for report 
                    $entrepreneur->incorporation_date = $incorporation_date;
                    $entrepreneur->incubation_start_date = $incubation_start_date;
                    $entrepreneur->operation_address = $operation_address;
                    $entrepreneur->dob = $dob;
                    $entrepreneur->qualification = $qualification;
                    $entrepreneur->legal_status = $legal_status;
                    $entrepreneur->pan = $pan;
                    $entrepreneur->cin = $cin;
                    $entrepreneur->gstin = $gstin;
                    $entrepreneur->share_holding_percentage = $share_holding_percentage;
                    //report end 
                    $full_time_employee = $_POST['full_time_employee'];
                    $part_time_employee = $_POST['part_time_employee'];
                    $total_employee = (int)$full_time_employee + (int)$part_time_employee;
                    $entrepreneur->full_time_employee = $full_time_employee;
                    $entrepreneur->part_time_employee = $part_time_employee;
                    $entrepreneur->total_employee = $total_employee;

                    $entrepreneur->summary_start_up = stripslashes(trim($_POST['summary_start_up']));
                    $entrepreneur->market_need = stripslashes(trim($_POST['market_need']));
                    $entrepreneur->product_service_desc = stripslashes(trim($_POST['product_service_desc']));
                    $entrepreneur->customer_user_desc = stripslashes(trim($_POST['customer_user_desc']));
                    $entrepreneur->revenue_model = stripslashes(trim($_POST['revenue_model']));
                    $entrepreneur->current_traction = stripslashes(trim($_POST['current_traction']));

                    /*
                      $entrepreneur->qualification_certificate1=stripslashes(trim($_POST['qualification_certificate1']));
                      $entrepreneur->qualification_certificate2=stripslashes(trim($_POST['qualification_certificate2']));
                      $entrepreneur->expected_revenue_oneyear=stripslashes(trim($_POST['expected_revenue_oneyear']));
                      $entrepreneur->expected_revenue_threeyears=stripslashes(trim($_POST['expected_revenue_threeyears']));
                      $entrepreneur->expected_revenue_fiveyears=stripslashes(trim($_POST['expected_revenue_fiveyears']));
                     */

                    $support_required = "";
                    if (isset($_POST['support_required']) && !empty($_POST['support_required'])) {
                        $support_required_array = $_POST['support_required'];

                        $support_required = implode('~', $support_required_array);
                    }

                    $entrepreneur->support_required = $support_required;

                    $entrepreneur->fund_require = stripslashes(trim($_POST['fund_require']));
                    //$entrepreneur->select_need=stripslashes(trim($_POST['select_need']));

                    $entrepreneur->structure_of_company = stripslashes(trim($_POST['structure_of_company']));

                    if ($entrepreneur->save()) {
                        if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
                            foreach ($_POST['cat_id'] as $cat_id) {
                                $category_user = new CategoryUser;
                                $category_user->category_id = $cat_id;
                                $category_user->user_id = $user->id;
                                $category_user->user_type = 'e';
                                $category_user->save();
                            }
                        }
                        Session::flash('flash_message', 'Entreprenaur has Been added Sucessfully.');
                        return Redirect::route('admin.list_entreprenaurs');
                    } else {

                        Session::flash('flash_message', 'Sorry!! Entreprenaur cannot save.');
                        return Redirect::back();
                    }
                } else {
                    Session::flash('flash_message', 'Sorry!! Entreprenaur cannot save.');
                    return Redirect::back();
                }
            }
        }
    }

    public function edit($id) { 

// print_r($_POST); die('soumya');
        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }


        $entrepreneur_detail = Entrepreneur::where('id', $id);

        if ($entrepreneur_detail->count() == 0) {

            return Redirect::route('admin.list_entreprenaurs');
        }

        $entrepreneur_detail = $entrepreneur_detail->get();

        $user_detail = User::where('id', $id);
        $user_detail = $user_detail->get();

        $categoryuser = array();
        //$categoryuser=CategoryUser::where('user_id',$id)->lists('category_id'); 

        $categoryList = Category::with('children')->where('parent_id', 0)->orderBy('name')->get();

        $category_user = CategoryUser::where('user_id', $id)->get();

        $startupstageList = StartupStage::where('status', 1)->orderBy('name')->get();

        $supportrequiredList = SupportRequired::where('status', 1)->orderBy('name')->get();

        $structureofcompanyList = StructureofCompany::where('status', 1)->orderBy('name')->get();

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $country_id = $user_detail[0]->country_id;

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        $category_array = array();

        foreach ($category_user as $v) {
            array_push($category_array, $v->category_id);
        }

//        echo '<pre>'; print_r($_POST); die('soumya');

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {


            //--------- start for enterpreneur ----------//

            $user = User::find($id);

            $user->name = stripslashes(trim($_POST['company_name']));
            $user->individual_name = stripslashes(trim($_POST['individual_name']));
            $user->address = stripslashes(trim($_POST['address1']));
            //$entrepreneur->address2=stripslashes(trim($_POST['address2']));

            $user->country_id = stripslashes(trim($_POST['country_id']));
            $user->city_id = stripslashes(trim($_POST['city_id']));

            $user->zip_code = stripslashes(trim($_POST['zip_code']));
            $user->phone = stripslashes(trim($_POST['phone_no']));



            if (Input::hasFile('photo')) {
                $file = Input::file('photo');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/entreprenaur_photo/', $name);
                $photo = 'entreprenaur_photo/' . $name;

                $user->photo = $photo;

                if (isset($_POST['hid_photo']) && $_POST['hid_photo'] != "" && file_exists('public/' . $_POST['hid_photo'])) {
                    unlink('public/' . $_POST['hid_photo']);
                }
            }
            $user->country_id2 = stripslashes(trim($_POST['country_id2']));
            $user->city_id2 = stripslashes(trim($_POST['city_id2']));
            $user->zip_code2 = stripslashes(trim($_POST['zip_code2']));

            $user->save();

            $entrepreneur = Entrepreneur::find($id);
            
            $incorporation_date = trim($_POST['incorporation_date']);
            $incubation_start_date = trim($_POST['incubation_start_date']);            
            $dob = trim($_POST['dob']);
            $qualification = trim($_POST['qualification']);
            $operation_address = stripslashes(trim($_POST['operation_address']));
            $legal_status = stripslashes(trim($_POST['legal_status']));
            $pan = stripslashes(trim($_POST['pan']));
            $cin = stripslashes(trim($_POST['cin']));
            $gstin = stripslashes(trim($_POST['gstin']));
            $share_holding_percentage = stripslashes(trim($_POST['share_holding_percentage']));

            //$entrepreneur->type=stripslashes(trim($_POST['type']));

            $entrepreneur->founding_year = stripslashes(trim($_POST['founding_year']));
            $entrepreneur->start_up_stage = stripslashes(trim($_POST['start_up_stage']));



            $entrepreneur->website = stripslashes(trim($_POST['website']));

            //$entrepreneur->video=$video;
            //$entrepreneur->photo=$photo;
            $full_time_employee = $_POST['full_time_employee'];
            $part_time_employee = $_POST['part_time_employee'];
            $total_employee = (int)$full_time_employee + (int)$part_time_employee;
            $entrepreneur->full_time_employee = $full_time_employee;
            $entrepreneur->part_time_employee = $part_time_employee;
            $entrepreneur->total_employee = $total_employee;

            $entrepreneur->summary_start_up = stripslashes(trim($_POST['summary_start_up']));
            $entrepreneur->market_need = stripslashes(trim($_POST['market_need']));
            $entrepreneur->product_service_desc = stripslashes(trim($_POST['product_service_desc']));
            $entrepreneur->customer_user_desc = stripslashes(trim($_POST['customer_user_desc']));
            $entrepreneur->revenue_model = stripslashes(trim($_POST['revenue_model']));
            $entrepreneur->current_traction = stripslashes(trim($_POST['current_traction']));
            //report 
            
            $entrepreneur->incorporation_date = $incorporation_date;
            $entrepreneur->incubation_start_date = $incubation_start_date;
            $entrepreneur->dob = $dob;
            $entrepreneur->qualification = $qualification;
            $entrepreneur->operation_address = $operation_address;
            $entrepreneur->legal_status = $legal_status;
            $entrepreneur->pan = $pan;
            $entrepreneur->cin = $cin;
            $entrepreneur->gstin = $gstin;
            $entrepreneur->share_holding_percentage = $share_holding_percentage;
            //report end
            

            $support_required = "";
            if (isset($_POST['support_required']) && !empty($_POST['support_required'])) {
                $support_required_array = $_POST['support_required'];

                $support_required = implode('~', $support_required_array);
            }

            $entrepreneur->support_required = $support_required;

            $entrepreneur->fund_require = stripslashes(trim($_POST['fund_require']));
            //$entrepreneur->select_need=stripslashes(trim($_POST['select_need']));

            $entrepreneur->structure_of_company = stripslashes(trim($_POST['structure_of_company']));

			$entrepreneur_ordering = stripslashes(trim($_POST['ordering']));

            $entrepreneur_ordering = (int) $entrepreneur_ordering;

            if ($entrepreneur_ordering == 0 || "") {
                $entrepreneur_ordering = NULL;
            }

            $entrepreneur->ordering = $entrepreneur_ordering;




            if (Input::hasFile('video')) {

                $file = Input::file('video');
                $name = time() . '-' . $file->getClientOriginalName();
                $name = str_replace(" ", "-", $name);
                $file = $file->move('public/entreprenaur_video/', $name);
                $video = 'entreprenaur_video/' . $name;

                if (isset($_POST['hid_video']) && $_POST['hid_video'] && file_exists('public/' . $_POST['hid_video'])) {
                    unlink('public/' . $_POST['hid_video']);
                }

                $entrepreneur->video = $video;
            }


            $entrepreneur->save(); 

            //--------- end for enterpreneur ----------//
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

            Session::flash('flash_message', 'Startup has been updated successfully.');

            return Redirect::back();
        }



        return View::make('admin.entrepreneurs.edit', compact('categoryList', 'entrepreneur_detail', 'user_detail', 'user_email', 'category_array', 'startupstageList', 'supportrequiredList', 'structureofcompanyList', 'countryList', 'cityList'));
    }

    public function admin_delete_entreprenaur($id) {

        $sessionid = Session::get('userid');
        if (!isset($sessionid) && $sessionid == '') {
            return Redirect::route('admin')->with('message', 'Please Login');
        }

        $user = User::find($id);

        $user->status = 'delete';

        $user->save();

        Session::flash('flash_message', 'Entreprenaur has been deleted successfully.');
        return Redirect::back();
    }

// front end section 
    public function edit_profile() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $user_email = Auth::user()->user_email;

        $user = User::find($userid);

        $entrepreneur_exist = Entrepreneur::find($userid);
        if (isset($entrepreneur_exist) && !empty($entrepreneur_exist)) {
            $entrepreneur = $entrepreneur_exist;

            $country_id = $user->country_id;
        } else {
            $entrepreneur = new Entrepreneur;

            $country_id = "";
        }
        $categoryuser = array();
        $categoryuser = CategoryUser::where('user_id', $userid)->lists('category_id');
        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', '1')->orderBy('name')->get();

        $startupstageList = StartupStage::where('status', 1)->orderBy('name')->get();

        $supportrequiredList = SupportRequired::where('status', 1)->orderBy('name')->get();

        $structureofcompanyList = StructureofCompany::where('status', 1)->orderBy('name')->get();

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        return View::make('entrepreneurs.edit_profile', compact('categoryList', 'user', 'entrepreneur', 'user_email', 'categoryuser', 'startupstageList', 'supportrequiredList', 'structureofcompanyList', 'countryList', 'cityList'));
    }

    public function store_step1() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {

            $userid = Auth::user()->id;
            $entrepreneur_exist = Entrepreneur::find($userid);
            $user = User::find($userid);
            if (isset($entrepreneur_exist) && !empty($entrepreneur_exist)) {
                $entrepreneur = $entrepreneur_exist;
            } else {
                $entrepreneur = new Entrepreneur;
            }
        }

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {

            // add entreprenaur 

            $user->name = stripslashes(trim($_POST['company_name']));

            $user->individual_name = stripslashes(trim($_POST['individual_name']));

            $user->address = stripslashes(trim($_POST['address1']));

            $user->country_id = stripslashes(trim($_POST['country_id']));
            $user->city_id = stripslashes(trim($_POST['city_id']));

            //$entrepreneur->address2=stripslashes(trim($_POST['address2']));
            $user->zip_code = stripslashes(trim($_POST['zip_code']));
            $user->phone = stripslashes(trim($_POST['phone_no']));

            //  file upload 
            $photo = '';
            $user->country_id2 = stripslashes(trim($_POST['country_id2']));
            $user->city_id2 = stripslashes(trim($_POST['city_id2']));

            //$entrepreneur->address2=stripslashes(trim($_POST['address2']));
            $user->zip_code2 = stripslashes(trim($_POST['zip_code2']));

            if (Input::hasFile('photo')) {

                $file = Input::file('photo');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/entreprenaur_photo/', $name);
                $photo = 'entreprenaur_photo/' . $name;

                $user->photo = $photo;

                if (isset($_POST['hid_photo']) && $_POST['hid_photo'] != "" && file_exists('public/' . $_POST['hid_photo'])) {
                    unlink('public/' . $_POST['hid_photo']);
                }
            }

            $user->save();

            //$entrepreneur->id=$userid;
            //$entrepreneur->type=stripslashes(trim($_POST['type']));

            $entrepreneur->founding_year = stripslashes(trim($_POST['founding_year']));
            $entrepreneur->start_up_stage = stripslashes(trim($_POST['start_up_stage']));

            $entrepreneur->website = stripslashes(trim($_POST['website']));

            $incorporation_date = trim($_POST['incorporation_date']);
            $incubation_start_date = trim($_POST['incubation_start_date']);
            $dob = trim($_POST['dob']);
            $full_time_employee = $_POST['full_time_employee'];
            $part_time_employee = $_POST['part_time_employee'];
            $total_employee = (int)$full_time_employee + (int)$part_time_employee;
            $qualification = trim($_POST['qualification']);
            $operation_address = stripslashes(trim($_POST['operation_address']));
            $legal_status = stripslashes(trim($_POST['legal_status']));
            $pan = stripslashes(trim($_POST['pan']));
            $cin = stripslashes(trim($_POST['cin']));
            $gstin = stripslashes(trim($_POST['gstin']));
            //$share_holding_percentage = stripslashes(trim($_POST['share_holding_percentage']));


            $support_required = "";
            if (isset($_POST['support_required']) && !empty($_POST['support_required'])) {
                $support_required_array = $_POST['support_required'];

                $support_required = implode('~', $support_required_array);
            }

            $entrepreneur->support_required = $support_required;
            //report add
            $entrepreneur->incorporation_date = $incorporation_date;
            $entrepreneur->incubation_start_date = $incubation_start_date;
            $entrepreneur->dob = $dob;
            $entrepreneur->qualification = $qualification;
            $entrepreneur->operation_address = $operation_address;
            $entrepreneur->legal_status = $legal_status;
            $entrepreneur->pan = $pan;
            $entrepreneur->cin = $cin;
            $entrepreneur->gstin = $gstin;
            $entrepreneur->full_time_employee = $full_time_employee;
            $entrepreneur->part_time_employee = $part_time_employee;
            $entrepreneur->total_employee = $total_employee;
            //$entrepreneur->share_holding_percentage = $share_holding_percentage;
            //report end
            $entrepreneur->fund_require = stripslashes(trim($_POST['fund_require']));

            $entrepreneur->structure_of_company = stripslashes(trim($_POST['structure_of_company']));

            if ($entrepreneur->save()) {

                if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
                    // saving invester indicator	
                    //$user->filled_ent='1';
                    //$user->save();
                    // for edit section only

                    $categoryuser = CategoryUser::where('user_id', $userid)->lists('id', 'category_id');
                    //echo '<pre>'; dd($categoryuser);
                    if (isset($categoryuser) && !empty($categoryuser)) {
                        foreach ($categoryuser as $cat_id) {
                            $categoryuser = CategoryUser::find($cat_id);
                            $categoryuser->delete();
                        }
                    }

                    if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {

                        foreach ($_POST['cat_id'] as $cat_id) {
                            $category_user = new CategoryUser;
                            $category_user->category_id = $cat_id;
                            $category_user->user_id = $user->id;
                            $category_user->user_type = 'e';
                            $category_user->save();
                        }
                    }
                }

                if (isset($_POST['other_category']) && !empty($_POST['other_category'])) {
                    $other_category_name = trim(strip_tags($_POST['other_category']));

                    $othercategory = new Othercategory;

                    $othercategory->user_id = $userid;
                    $othercategory->name = $other_category_name;

                    $othercategory->save();
                }

                Session::flash('flash_message', 'Startups has Been saved Sucessfully.');
                return Redirect::route('entrepreneur.step2');
            } else {

                Session::flash('flash_message', 'Sorry!! Startups cannot save.');
                return Redirect::back();
            }
        }
    }

    public function step2() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $entrepreneur = Entrepreneur::find($userid);
            $user = User::find($userid);
        }

        if (isset($entrepreneur) && !empty($entrepreneur)) {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {






                if (Input::hasFile('video')) {

                    $file = Input::file('video');
                    $name = time() . '-' . $file->getClientOriginalName();
                    $name = str_replace(" ", "-", $name);
                    $file = $file->move('public/entreprenaur_video/', $name);
                    $video = 'entreprenaur_video/' . $name;

                    if (isset($_POST['hid_video']) && $_POST['hid_video'] != "" && file_exists('public/' . $_POST['hid_video'])) {
                        unlink('public/' . $_POST['hid_video']);
                    }

                    $entrepreneur->video = $video;
                }



                $entrepreneur->summary_start_up = stripslashes(trim($_POST['summary_start_up']));
                $entrepreneur->market_need = stripslashes(trim($_POST['market_need']));
                $entrepreneur->product_service_desc = stripslashes(trim($_POST['product_service_desc']));
                $entrepreneur->customer_user_desc = stripslashes(trim($_POST['customer_user_desc']));
                $entrepreneur->revenue_model = stripslashes(trim($_POST['revenue_model']));
                $entrepreneur->current_traction = stripslashes(trim($_POST['current_traction']));

                if ($entrepreneur->save()) {
                    Session::flash('flash_message', 'Entreprenaur has Been saved Sucessfully.');
                    return Redirect::route('entrepreneur.step3');
                } else {
                    Session::flash('flash_message', 'Sorry details cannot save.');
                }
            }
        } else {
            return Redirect::back();
        }

        return View::make('entrepreneurs.step2', compact('entrepreneur', 'user'));
    }

    public function step3() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $user = User::find($userid);
            $all_promoters = Promoter::where('user_id', $userid)->get();
        }

        return View::make('entrepreneurs.step3', compact('all_promoters', 'user'));
    }

    public function add_promoters() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {
            // add promoter 

            if (Input::hasFile('image')) {
                $file = Input::file('image');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/promoters_images/', $name);
                $image = 'promoters_images/' . $name;
            } else {
                $image = "";
            }

            $promoter = new Promoter;
            $promoter->user_id = $userid;
            $promoter->name = stripslashes(trim($_POST['name']));
            $promoter->dob = $_POST['dob'];
            $promoter->email_id = stripslashes(trim($_POST['email_id']));
            $promoter->address = stripslashes(trim($_POST['address']));
            $promoter->phone = stripslashes(trim($_POST['phone']));
            $promoter->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
            $promoter->professional_qualification = stripslashes(trim($_POST['professional_qualification']));
            $promoter->past_details = stripslashes(trim($_POST['past_details']));
            $promoter->keys_rols = stripslashes(trim($_POST['keys_rols']));
            $promoter->image = $image;

            if ($promoter->save()) {
                Session::flash('flash_message', 'Promoter has Been added Sucessfully.');
                return Redirect::route('entrepreneur.step3');
            } else {

                Session::flash('flash_message', 'Sorry!! Registation cannot done.');
                return Redirect::back();
            }
        }
    }

    public function edit_promoters() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $id = base64_decode($_GET['id']);
        $promoter = Promoter::find($id);
        if (!isset($promoter) && empty($promoter)) {
            return Redirect::back();
        }

        if (isset($_POST['_token']) && !empty($_POST['_token'])) {
            // add promoter 
            if (Input::hasFile('image')) {
                $file = Input::file('image');
                $name = time() . '-' . $file->getClientOriginalName();
                $file = $file->move('public/promoters_images/', $name);
                $image = 'promoters_images/' . $name;
                if (isset($_POST['hid_image']) && $_POST['hid_image'] != "" && file_exists('public/' . $_POST['hid_image'])) {
                    unlink('public/' . $_POST['hid_image']);
                }
            } else {
                $image = $_POST['hid_image'];
            }


            $promoter->user_id = $userid;
            $promoter->name = stripslashes(trim($_POST['name']));
            $promoter->dob = $_POST['dob'];
            $promoter->email_id = stripslashes(trim($_POST['email_id']));
            $promoter->address = stripslashes(trim($_POST['address']));
            $promoter->phone = stripslashes(trim($_POST['phone']));
            $promoter->highest_qualification = stripslashes(trim($_POST['highest_qualification']));
            $promoter->professional_qualification = stripslashes(trim($_POST['professional_qualification']));
            $promoter->past_details = stripslashes(trim($_POST['past_details']));
            $promoter->keys_rols = stripslashes(trim($_POST['keys_rols']));
            $promoter->image = $image;

            if ($promoter->save()) {
                Session::flash('flash_message', 'Promoter has Been added Sucessfully.');
                return Redirect::route('entrepreneur.step3');
            } else {

                Session::flash('flash_message', 'Sorry!! Registation cannot done.');
                return Redirect::back();
            }
        }
    }
	
	public function delete_promoters() {
		if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $id = base64_decode($_GET['id']);
        $promoter = Promoter::find($id);
        if (!isset($promoter) && empty($promoter)) {
            return Redirect::back();
        }
		
		DB::table('promoters')->where('id', '=', $id)->where('user_id', '=', $userid)->delete();
		//Session::flash('flash_message', 'Promoter has Been deleted Sucessfully.');
		return Redirect::route('entrepreneur.step3');
	}

    public function step4() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $user = User::find($userid);
            $all_products = Product::where('user_id', $userid)->get();
        }

        return View::make('entrepreneurs.step4', compact('all_products', 'user'));
    }

    public function checkFinancialStart($year){
        $next_year = $year + 1;
        $start = $year.'-04-01';
        $end = $next_year.'-03-31';
        return $start;
    }

    public function checkFinancialEnd($year){
        $next_year = $year + 1;
        $start = $year.'-04-01';
        $end = $next_year.'-03-31';
        return $end;
    }

    public function step5()
    {
		require_once Config::get('app.base_url')."common/helpers.php";
		
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $user = User::find($userid);
            $funding_status = Entrepreneur::where('id', $userid)->first();

           $where = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
            $current_target_achievement = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where)->first();

            $where1 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 1) . "' AND '" . checkFinancialEnd(date('Y') - 1) . "'  ";
            $current_target_achievement1 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where1)->first();

            $where2 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 2) . "' AND '" . checkFinancialEnd(date('Y') - 2) . "'  ";
            $current_target_achievement2 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where2)->first();

            $where3 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 3) . "' AND '" . checkFinancialEnd(date('Y') - 3) . "'  ";
            $current_target_achievement3 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where3)->first();

            $where4 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 4) . "' AND '" . checkFinancialEnd(date('Y') - 4) . "'  ";
            $current_target_achievement4 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where4)->first();


        //}

        return View::make('entrepreneurs.step5', compact('funding_status', 'user', 'current_target_achievement', 'current_target_achievement1', 'current_target_achievement2', 'current_target_achievement3', 'current_target_achievement4'));
        }
    }

    public function store_step5(){
		require_once Config::get('app.base_url')."common/helpers.php";
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $enterpreneur = Entrepreneur::find($userid);
            $remarks_fund_from_own_resources = stripslashes(trim($_POST['remarks_fund_from_own_resources']));
            $remarks_fund_form_external = stripslashes(trim($_POST['remarks_fund_form_external']));
            $remarks_iimcip_investment = stripslashes(trim($_POST['remarks_iimcip_investment']));

            $enterpreneur->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
            $enterpreneur->remarks_fund_form_external = $remarks_fund_form_external;
            $enterpreneur->remarks_iimcip_investment = $remarks_iimcip_investment;
            if($enterpreneur->save()) {
               $where = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
             
			   $current_target_achievement = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where)->first();

                $volume_annual_target = stripslashes(trim($_POST['volume_annual_target']));
                $sales_annual_target = stripslashes(trim($_POST['sales_annual_target']));
                $volume_achived_till_date = stripslashes(trim($_POST['volume_achived_till_date']));
                $sales_achived_till_date = stripslashes(trim($_POST['sales_achived_till_date']));
                //$volume_sales_revenue = stripslashes(trim($_POST['volume_sales_revenue']));
               // $sales_sales_revenue = stripslashes(trim($_POST['sales_sales_revenue']));
                $order_pipeline = stripslashes(trim($_POST['order_pipeline']));
				$prev_sales_sales_revenue=$_POST['prev_sales_sales_revenue'];
				$row_id=$_POST['row_id'];
				
				if (count($current_target_achievement) > 0) {
					$startup_target_achivement = StartupTargetAchievement::find($current_target_achievement->id);
				}
				else
				{
					$startup_target_achivement = new StartupTargetAchievement;
				}
                
                $startup_target_achivement->startup_id = $userid;
                $startup_target_achivement->volume_annual_target = $volume_annual_target;
                $startup_target_achivement->sales_annual_target = $sales_annual_target;
                $startup_target_achivement->volume_achived_till_date = $volume_achived_till_date;
                $startup_target_achivement->sales_achived_till_date = $sales_achived_till_date;
                //$startup_target_achivement->volume_sales_revenue = $volume_sales_revenue;
                //$startup_target_achivement->sales_sales_revenue = $sales_sales_revenue;
                $startup_target_achivement->order_pipeline = $order_pipeline;
				
				$startup_target_achivement->save();
				unset($startup_target_achivement);
				
				/*
                if (count($current_target_achievement) > 0) {
                    $exist_id = $current_target_achievement->id;
                    if($startup_target_achivement->save()){
                        StartupTargetAchievement::where('id', '=', $exist_id)->delete();
                    }
                }else{
                    $startup_target_achivement->save();
                }
				*/
				
				//--------- start for entry of previous of sales revinue -------------//
				
				foreach($prev_sales_sales_revenue as $k=>$v)
				{
					$new_row_id=$row_id[$k];
					$year_back=$k+1;
					$new_sales_revenue=$v;
					if($new_row_id!="")
					{
						$prev_sale_update=StartupTargetAchievement::find($new_row_id);
						$prev_sale_update->sales_sales_revenue=$new_sales_revenue;
						$prev_sale_update->save();
					}
					else
					{
						$date = strtotime("-$year_back years");
						$new_date= date('Y-m-d', $date);
						
						$startup_target_achivement = new StartupTargetAchievement;
						$startup_target_achivement->startup_id = $userid;
						$startup_target_achivement->sales_sales_revenue = $new_sales_revenue;
						$startup_target_achivement->created_at=$new_date;
						$startup_target_achivement->save();
						
					}
				}
				
				//--------- end for entry of previous of sales revinue -------------//



                Session::flash('flash_message', 'Saved Sucessfully.');
            }else{
                Session::flash('flash_message', 'Not Saved.');
            }

            return Redirect::route('entrepreneur.step5');
        }
    }
    public function add_product() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $user = User::find($userid);
        if (isset($user) && $userid != '') {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {
                // add product 
                $product = new Product;
                $product->user_id = $userid;
                $product->name = stripslashes(trim($_POST['name']));
                $product->description = stripslashes(trim($_POST['description']));
                if ($product->save()) {
                    $files = array();
                    $files = Input::file("images");
                    if (isset($files) && isset($files[0])) {
                        foreach (Input::file("images") as $file) {
                            $name = time() . '-' . $file->getClientOriginalName();
                            $file = $file->move('public/products_images/', $name);
                            $image = 'products_images/' . $name;
                            $product_image = new ProductImage;
                            $product_image->user_id = $userid;
                            $product_image->product_id = $product->id;
                            $product_image->image = $image;
                            $product_image->save();
                        }
                    }

                    Session::flash('flash_message', 'Product has Been added Sucessfully.');
                    return Redirect::route('entrepreneur.step4');
                } else {

                    Session::flash('flash_message', 'Sorry!! product cannot added.');
                    return Redirect::back();
                }
            }
        } else {

            return Redirect::back();
        }
    }

    public function edit_product() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $user = User::find($userid);
        $data = base64_decode($_GET['data']);
        $product = Product::find($data);
        if (!isset($product) && empty($product)) {
            return Redirect::back();
        }

        if (isset($user) && $userid != '') {
            if (isset($_POST['_token']) && !empty($_POST['_token'])) {
                // edit product 

                $product->user_id = $userid;
                $product->name = stripslashes(trim($_POST['name']));
                $product->description = stripslashes(trim($_POST['description']));
                if ($product->save()) {
                    $files = array();
                    $files = Input::file("images");
                    if (isset($files) && isset($files[0])) {
                        foreach (Input::file("images") as $file) {
                            $name = time() . '-' . $file->getClientOriginalName();
                            $file = $file->move('public/products_images/', $name);
                            $image = 'products_images/' . $name;
                            $product_image = new ProductImage;
                            $product_image->user_id = $userid;
                            $product_image->product_id = $product->id;
                            $product_image->image = $image;
                            $product_image->save();
                        }
                    }


                    Session::flash('flash_message', 'Product has Been added Sucessfully.');
                    return Redirect::route('entrepreneur.step4');
                } else {

                    Session::flash('flash_message', 'Sorry!! product cannot added.');
                    return Redirect::back();
                }
            }
        } else {

            return Redirect::back();
        }
    }
	
	public function delete_product() {
		if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userid = Auth::user()->id;
        $id = base64_decode($_GET['id']);
        $product = Product::find($id);
        if (!isset($product) && empty($product)) {
            return Redirect::back();
        }
		
		
		
		DB::table('products')->where('id', '=', $id)->where('user_id', '=', $userid)->delete();
		//Session::flash('flash_message', 'Promoter has Been deleted Sucessfully.');
		return Redirect::route('entrepreneur.step4');
	}

    public function delete_productimg() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $id = base64_decode($_GET['i']);
        $productImage = ProductImage::find($id);
        $productImage->delete();
        return Redirect::back();
    }

    public function listing() {
        if (!Auth::check()) {
            //Session::flash('flash_message', 'Please login.');
            //return Redirect::route('home');
        } else {
            $userid = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_inv = Auth::user()->filled_inv;
            if ($user_type == 'i' && $filled_inv = 0) {
                return Redirect::back();
            }
            if ($user_type == 'e') {
                return Redirect::back();
            }
        }

        $categoryList = Category::with('children')->where('parent_id', 0)->where('is_active', '1')->orderBy('name')->get();

        $startupstageList = StartupStage::where('status', '1')->orderBy('name')->get();

        $country_id = "";

        if (isset($_GET['country_id']) && $_GET['country_id'] != "") {
            $country_id = $_GET['country_id'];
        }

        $countryList = Country::where('status', '1')->orderBy('name')->get();

        $cityList = City::where('status', '1')->where('country_iso_code', $country_id)->orderBy('city_name')->get();

        //if (isset($_GET) && !empty($_GET))
        //{

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



        $all_entrepreneurs = Entrepreneur::leftjoin('category_users', 'category_users.user_id', '=', 'entrepreneurs.id')->leftjoin('categories', 'categories.id', '=', 'category_users.category_id')->join('users', 'users.id', '=', 'entrepreneurs.id')->leftjoin('start_up_stages', 'entrepreneurs.start_up_stage', '=', 'start_up_stages.id')->select('category_users.category_id', 'entrepreneurs.id', 'users.name', 'users.photo', 'entrepreneurs.founding_year', 'entrepreneurs.website', 'entrepreneurs.fund_require', 'entrepreneurs.summary_start_up', 'start_up_stages.name')->where('users.status', 'Active');
		

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

        /*
          if($searchbox!=="")
          {
          $all_entrepreneurs=$all_entrepreneurs->where('entrepreneurs.company_name','like','%'.$searchbox.'%');
          $all_entrepreneurs=$all_entrepreneurs->orWhere('entrepreneurs.company_objective','like','%'.$searchbox.'%');
          $all_entrepreneurs=$all_entrepreneurs->orWhere('entrepreneurs.business_model','like','%'.$searchbox.'%');
          //$all_entrepreneurs=$all_entrepreneurs->orWhere('past_details','like','%'.$searchbox.'%');
          }
         */

       //$all_entrepreneurs = $all_entrepreneurs->orderBy('id', 'desc')->groupBy('entrepreneurs.id');
	   
	   //$all_entrepreneurs = $all_entrepreneurs->orderByRaw('IF (entrepreneurs.id in(439,485) , entrepreneurs.id, -1)')->orderBy('entrepreneurs.id', 'desc')->groupBy('entrepreneurs.id');

       // $all_entrepreneurs = $all_entrepreneurs->paginate(20);
	   
	   $all_entrepreneurs = $all_entrepreneurs->orderByRaw('IF (entrepreneurs.ordering IS NOT NULL, ordering, 5000000)')->orderBy('entrepreneurs.id', 'desc')->groupBy('entrepreneurs.id')->paginate(20);



        //$all_entrepreneurs=Entrepreneur::orderBy('id', 'desc')->groupBy('entrepreneurs.id')->paginate(5);

        return View::make('entrepreneurs.list', compact('all_entrepreneurs', 'categoryList', 'startupstageList', 'countryList', 'cityList'));
    }


    public function listingApi() {

        if (!Auth::check()) {
            //Session::flash('flash_message', 'Please login.');
            //return Redirect::route('home');
        } else {
            $userid = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_inv = Auth::user()->filled_inv;
            if ($user_type == 'i' && $filled_inv = 0) {
                return Redirect::back();
            }
            if ($user_type == 'e') {
                return Redirect::back();
            }
        }


        $all_entrepreneurs = Entrepreneur::leftjoin('category_users', 'category_users.user_id', '=', 'entrepreneurs.id')->leftjoin('categories', 'categories.id', '=', 'category_users.category_id')->join('users', 'users.id', '=', 'entrepreneurs.id')->leftjoin('start_up_stages', 'entrepreneurs.start_up_stage', '=', 'start_up_stages.id')->select('category_users.category_id', 'entrepreneurs.id', 'users.name', 'users.photo', 'entrepreneurs.founding_year', 'entrepreneurs.website', 'entrepreneurs.fund_require', 'entrepreneurs.summary_start_up', 'start_up_stages.name')->where('users.status', 'Active');
           
       
       $all_entrepreneurs = $all_entrepreneurs->orderByRaw('IF (entrepreneurs.ordering IS NOT NULL, ordering, 5000000)')->orderBy('entrepreneurs.id', 'desc')->groupBy('entrepreneurs.id')->limit(25)->get();

      
       $retArray = array();
       if( count($all_entrepreneurs) > 0 ) {

            foreach ($all_entrepreneurs as $key => $entrepreneur) {

                // $website_link = '';

                // if($entrepreneur->website!="") {
                //     $website_link=$entrepreneur->website;
                //     if(!stristr($entrepreneur->website,'http://') && !stristr($entrepreneur->website,'https://'))
                //     {
                //         $website_link="http://".$website_link;
                //     }
                // } else {
                //    $website_link = '' ;
                // }

                $entrepreneuUserPhoto = '';
                if($entrepreneur->user->photo!='') {
                    $entrepreneuUserPhoto = asset('public/'.$entrepreneur->user->photo);
                } else {
                    $entrepreneuUserPhoto = asset('public/images/noimage.png');
                }
               
               $retArray[] = array(
                        'name' => $entrepreneur->user->name,
                        'entrepreneuUserPhoto' => $entrepreneuUserPhoto,
                        'entrepreneurDetailUrl' => URL::route('entrepreneur.details',array('det'=>base64_encode($entrepreneur->id)))
                        //'startupStage' =>$entrepreneur->name,
                        //'investmentRequired' => $entrepreneur->fund_require,
                        //'website' => $website_link,
                        //'executiveSummary' => substr($entrepreneur->summary_start_up,0,300)
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
    public function details() {


		require_once Config::get('app.base_url')."common/helpers.php";
		
        if (!Auth::check()) {
            //Session::flash('flash_message', 'Please login.');
            //return Redirect::route('home');

            $guset_user = 1;
            $user_type = 0;
            $filled_ent = 0;
        } else {
            //$userid='';

            $guset_user = 0;
            $user_type = Auth::user()->user_type;
            $filled_inv = Auth::user()->filled_inv;
        }

        if ($user_type == 'i' && $filled_inv = 0) {

            return Redirect::back();
        }
        if (isset($_GET['det']) && $_GET['det'] != '') {

            $userid = stripcslashes(base64_decode($_GET['det']));
        } else {
            if ($user_type == 'e') {
                $userid = Auth::id();
            }
        }

        $user = User::find($userid);
        $entrepreneur = Entrepreneur::with('promoters', 'products', 'startupstage', 'structureofcompnay')->where('id', $userid);

        if ($entrepreneur->count() == 0) {
            Session::flash('flash_message', 'Sorry!! some problem redirecting.');
            return Redirect::route('entrepreneur.list');
        }

        $entrepreneur = $entrepreneur->first();


        if (isset($user) && isset($entrepreneur)) {
            //$userid = Auth::id();
            $taskHistory = TaskList::leftJoin('users', 'users.id', '=', 'task_list.start_up_id')
                            ->leftJoin('users as pm', 'pm.id', '=', 'task_list.assignd_by')
                            ->select('task_list.id', 'task_list.title', 'task_list.description', 'task_list.created_at', 'pm.user_type', 'task_list.dead_line', 'task_list.answer', 'task_list.assignd_by', 'task_list.is_complete', 'users.id as startup_id', 'users.name as startup_name', 'pm.individual_name as assigned_by')
                            ->where('start_up_id', '=', $userid)->orderBy('task_list.is_complete', 'ASC')->get();


            $where = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
            $current_target_achievement = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where)->first();

            $where1 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 1) . "' AND '" . checkFinancialEnd(date('Y') - 1) . "'  ";
            $current_target_achievement1 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where1)->first();

            $where2 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 2) . "' AND '" . checkFinancialEnd(date('Y') - 2) . "'  ";
            $current_target_achievement2 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where2)->first();

            $where3 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 3) . "' AND '" . checkFinancialEnd(date('Y') - 3) . "'  ";
            $current_target_achievement3 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where3)->first();

            $where4 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 4) . "' AND '" . checkFinancialEnd(date('Y') - 4) . "'  ";
            $current_target_achievement4 = StartupTargetAchievement::where('startup_id', $userid)->whereRaw($where4)->first();


            return View::make('entrepreneurs.details', compact('user', 'entrepreneur', 'userid', 'guset_user', 'taskHistory','current_target_achievement','current_target_achievement1','current_target_achievement2','current_target_achievement3','current_target_achievement4'));
        } else {
            Session::flash('flash_message', 'Sorry!! some problem redirecting.');
            return Redirect::back();
        }
    }

    public function getProductImages($product_id = null) {
        $product_images = ProductImage::where('product_id', $product_id)->get();
        return $product_images;
    }

    public function productdetails() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::id();
            $user_type = Auth::user()->user_type;
            $filled_inv = Auth::user()->filled_inv;
            if ($user_type == 'i' && $filled_inv = 0) {
                return Redirect::back();
            }
        }

        $pid = base64_decode($_GET['product']);
        $product = Product::with('productimages')->where('id', $pid)->first();

        if (isset($product)) {
            return View::make('entrepreneurs.product_details', compact('product'));
        } else {
            Session::flash('flash_message', 'Sorry!! some problem redirecting.');
            return Redirect::back();
        }
    }

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
    public function destroy($id) {
        //
    }

    public function answer_form() {

        $task_id = Input::get('task_id');

        $taskDetails = TaskList::find($task_id);
        //echo $taskDetails->start_up_id;
        $data = [
            'taskDetails' => $taskDetails
        ];
        echo View::make('entrepreneurs.task_respond_form')->with($data);
    }

    public function answer_log() {

        $task_id = Input::get('task_id');

        $taskDetails = TaskList::find($task_id);
        $taskReview = TaskReview::where('task_id', $task_id)->get();
        $taskReview = TaskReview::leftJoin('users', 'users.id', '=', 'task_review.reviewed_by')
                        ->select('task_review.id', 'task_review.remarks', 'users.user_type', 'task_review.created_at', 'users.id', 'users.individual_name', 'users.name')
                        ->where('task_review.task_id', '=', $task_id)->orderBy('task_review.created_at', 'DESC')->get();
        $data = [
            'taskDetails' => $taskDetails,
            'task_id' => $task_id,
            'taskReview' => $taskReview
        ];
        echo View::make('entrepreneurs.task_respond_log')->with($data);
    }

    public function respond() {

        $task_id = $_POST['task_id'];
        $answer = $_POST['answer'];

        $taskDetails = TaskList::find($task_id);
        // echo '<pre>'; print_r($taskDetails); die();
        $taskDetails->answer = $answer;
        //$taskDetails-save();
        if ($taskDetails->save()) {
            Session::flash('flash_message', 'Task has Been updated Sucessfully.');
            return Redirect::route('entrepreneur.details');
        } else {

            Session::flash('flash_message', 'Sorry!! Something went wrong.');
            return Redirect::route('entrepreneur.details');
        }
    }

    public function task_list() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            $user = User::find($userid);
            $start_up_id = $userid;
            $start_port_rel = StartUpPmRel::where('start_up_id', '=', $start_up_id)
                            ->select('pm_id')->get()->toArray();
            $pm_ids = array_column($start_port_rel, 'pm_id');
            $investor_ids = StartUpInvestorRel::where('start_up_id', '=', $start_up_id)->select('investor_id')->get()->toArray();
            $investor_ids = array_column($investor_ids, 'investor_id');
            //print_r($investor_ids); die();

            $investor_details = User::whereIn('id', $investor_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $startup_details = User::where('id', '=', $start_up_id)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $pm_details = User::whereIn('id', $pm_ids)->select('user_email', 'individual_name', 'name', 'photo', 'phone', 'id')->get();
            $start_up = User::find($start_up_id);
            $discussionDetails = Discussion::where('created_by', '=', $userid)->orderBy('created_at', 'desc')->limit(3)->get();
            $entrepreneurDetails = Entrepreneur::where('id','=',$userid)->select('challenges','opportunity')->first();
            $taskHistory = TaskList::leftJoin('users', 'users.id', '=', 'task_list.start_up_id')
                    ->leftJoin('users as pm', 'pm.id', '=', 'task_list.assignd_by')
                    ->leftJoin('users as assigned', 'assigned.id', '=', 'task_list.assigned_to')
                    ->select('task_list.id', 'task_list.title', 'task_list.answer', 'task_list.description', 'pm.user_type', 'task_list.created_at', 'task_list.dead_line', 'task_list.assignd_by', 'task_list.is_complete', 'users.id as startup_id', 'users.name as startup_name', 'pm.individual_name as assigned_by', 'assigned.individual_name as assigned_to', 'assigned.user_type as assigned_user_type', 'task_list.startup_view')
                    ->where('start_up_id', '=', $start_up_id)->orderBy('task_list.is_complete', 'ASC')
                    ->orderBy('task_list.created_at', 'DESC')
                    ->paginate(15);
            $task_category = TaskCategory::where('is_active', '=', 1)->get();
            if (!empty($start_up_id)) {
                $assigned_to['Startup'] = User::select('users.id', 'users.name', 'users.user_type')->find($start_up_id);
            }
            if (!empty($pm_ids)) {
                $assigned_to['PM'] = User::select('users.id', 'users.individual_name as name', 'users.user_type')->find($pm_ids[0]);
            }
            if (!empty($investor_ids)) {
                $assigned_to['Mentor'] = User::select('users.id', 'users.individual_name as name', 'users.user_type')->find($investor_ids[0]);
            }

            $data = [
                'discussionDetails' => $discussionDetails,
                'entrepreneurDetails' =>$entrepreneurDetails,
                'taskHistory' => $taskHistory,
                'start_up_id' => $userid,
                'assigned_to' => $assigned_to,
                'task_category' => $task_category,
                'start_up' => $start_up,
                'pm_details' => $pm_details,
                'investor_details' => $investor_details
            ];
        }

        return View::make('entrepreneurs.task_list')->with($data);
    }

    public function get_pm($startup_id) {
        $start_pm_rel = StartUpPmRel::where('start_up_id', $startup_id)->get();
        return $start_pm_rel;
    }

    public function get_mentor($startup_id) {
        $start_inv_rel = StartUpInvestorRel::where('start_up_id', $startup_id)->get();
        return $start_inv_rel;
    }
    public function save_report_data(){
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $startup_id = $_POST['startUp_id'];
        $challenges = $_POST['challenge'];
        $opportunity = $_POST['opportunity'];

        $entrepreneur = Entrepreneur::find($startup_id);
        if($entrepreneur){
            $entrepreneur->challenges = $challenges;
            $entrepreneur->opportunity = $opportunity;
            $entrepreneur->save();
            Session::flash('flash_message', 'Information Updated Successfully');
        }else{
            Session::flash('flash_message', 'Information Not Updated');
        }
        return Redirect::back();
    }
    public function create_task() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userId = Auth::id();

        $user_type = Auth::user()->user_type;
        $user_name = Auth::user()->name;
        $user_email = Auth::user()->user_email;
        $startup_id = $_POST['start_up_id'];
        $taskDetails = array();
        if ($user_type != 'e') {
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
                        $taskDetails[$i]['assigned_to'] = User::find($assignTo[$i])->individual_name;
                        $assigned_by = $user_name;
                        $start_up_id = $_POST['start_up_id'];
                        $taskList->save();
                    }
                }
                $this->send_task_email($taskDetails, $startup_id, $assigned_by, $pm_email);
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

        $mentor = $this->get_mentor($start_up_id);
        $mentor_id = isset($mentor[0]->investor_id) ? $mentor[0]->investor_id : '';
        if (!empty($mentor_id)) {
            $mentor_email = User::find($mentor_id)->user_email;
        } else {
            $mentor_email = '';
        }

        $subject = 'New task for ' . $startup_name . ' Added by ' . $assigned_by . '(Startup)';
        $message = "<h3>Task Details</h3>";
        $message .= "<b>Startup Name: " . $startup_name;
        $message .= "<table width='100%' border='1'>"
                . "<thead><tr>"
                . "<th></th>"
                . "<th>Title</th>"
                . "<th>Description</th>"
                . "<th>Deadline</th>"
                . "<th>Assigned To</th>"
//               . "<th></th>"
                . "</tr></thead><tbody>";
        $i = 0;
        foreach ($taskDetails as $task) {
            $i++;
            $link = "<a href='" . Config::get('app.url') . "/entrepreneur/task_list'>Details</a>";
            $message .= "<tr><td>" . $i . "</td>"
                    . "<td>" . $task['title'] . "</td>"
                    . "<td>" . $task['description'] . "</td>"
                    . "<td>" . date('jS M Y', strtotime($task['dead_line'])) . "</td>"
                    . "<td>" . $task['assigned_to'] . "</td></tr>"
//                . "<td>".$link."</td>"
                    . "</tr>";
        }

        $message .= "</tbody></table>";

//echo $subject.$message; die();
        return true;
        $send = sendEmailCommon($mentor_email, $subject, $message);
        if (!empty($pm_email)) {

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
        if ($user_type != 'e') {
            return Redirect::route('home');
        }



        $userId = Auth::id();
        $task_id = Input::get('task_id');
        $taskList = TaskList::find($task_id);
        $taskList->startup_view = 1;
        $taskList->save();
        $taskReview = TaskReview::where('task_id', $task_id)->get();
        $taskReview = TaskReview::leftJoin('users', 'users.id', '=', 'task_review.reviewed_by')
                        ->select('task_review.id', 'task_review.remarks', 'users.user_type', 'task_review.created_at', 'users.id', 'users.individual_name', 'users.name')
                        ->where('task_review.task_id', '=', $task_id)->orderBy('task_review.created_at', 'DESC')->get();
        $data = [
            'task_id' => $task_id,
            'taskReview' => $taskReview,
            'start_up_id' => $userId,
            'taskList' => $taskList
        ];

        return $returnHTML = View::make('entrepreneurs.task_review')->with($data);
    }

    public function create_review() {
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        }
        $userId = Auth::id();

        if (isset($_POST['submit'])) {


            $remark = $_POST['remark'];
            $start_up_id = $_POST['start_up_id'];
            $task_id = $_POST['task_id'];

            $taskList = TaskList::find($task_id);
            $taskList->startup_view = 0;
            $taskList->mentor_view = 0;
            $taskList->pm_view = 0;
            $taskList->save();

            $taskReview = new TaskReview;
            $taskReview->remarks = $remark;
            $taskReview->reviewed_by = $start_up_id;
            $taskReview->task_id = $task_id;
            $taskReview->save();

            //database insert query goes here



            Session::flash('flash_message', 'Task Remark Added Successfully');
            return Redirect::route('entrepreneur.task_list');
        }
    }

}
