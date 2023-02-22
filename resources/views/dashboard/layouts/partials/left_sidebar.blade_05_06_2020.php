<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel" style="background-color: #fff; text-align: center;">
      <a href="{{ route('dashboard') }}" id="brandBox">
        <img src="{{ asset('public/images/logo.png') }}" class="brandBox_logo1">
      </a>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
      <li class="">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'industryCategory') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Startup Business Verticals</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">          
          <li class="@if(isset($childMenu) && $childMenu == 'allIndustryCategory') active @endif">
            <a href="{{ route('allIndustryCats') }}"><i class="fa fa-circle-o"></i> All Verticals</a>
          </li>          
          
          <li class="@if(isset($childMenu) && $childMenu == 'addIndustryCategory') active @endif">
            <a href="{{ route('addIndustryCats') }}"><i class="fa fa-circle-o"></i> Add Vertical</a>
          </li>          
        </ul>
      </li>
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'management') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Startup</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
              
              <li class="@if(isset($childMenu) && $childMenu == 'usersList') active @endif">
                <a href="{{ route('users_list') }}"><i class="fa fa-circle-o"></i> All Startups</a>
              </li>
             
              <li class="@if(isset($childMenu) && $childMenu == 'createUser') active @endif">
                <a href="{{ route('crte_user') }}"><i class="fa fa-circle-o"></i> Add Startup</a>
              </li>
              
            </ul>
      </li>   

      

    <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'mainPost') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Topics/Q&A</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">          
          <li class="@if(isset($childMenu) && $childMenu == 'allmainPost') active @endif">
            <a href="{{ route('main_post.all') }}"><i class="fa fa-circle-o"></i> All Topics/Q&A</a>
          </li>          
          
          <li class="@if(isset($childMenu) && $childMenu == 'addmainPost') active @endif">
            <a href="{{ route('main_post.add') }}"><i class="fa fa-circle-o"></i> Add Topics/Q&A</a>
          </li>          
        </ul>
      </li>














     

      {{--@if(auth()->user()->can('banner-view') || auth()->user()->can('cms-page-view') || 
      auth()->user()->can('cms-page-create') || auth()->user()->can('faq-view') || auth()->user()->can('faq-create') || 
      auth()->user()->can('faq-edit') || auth()->user()->can('faq-delete') || auth()->user()->can('popup-view'))
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'contentManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Content Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if(auth()->user()->can('banner-view'))
          <li class="@if(isset($childMenu) && $childMenu == 'banner') active @endif">
            <a href="{{ route('bannList') }}"><i class="fa fa-circle-o"></i> Banner Management</a>
          </li>
          @endif
          @if(auth()->user()->can('cms-page-view') || auth()->user()->can('cms-page-create'))
          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'cmsManagement') active @endif">
            <a href="#">
              <i class="fa fa-file-o" aria-hidden="true"></i> <span>CMS Pages</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if(auth()->user()->can('cms-page-view'))
              <li class="@if(isset($childMenu) && $childMenu == 'allCMS') active @endif">
                <a href="{{ route('allCMS') }}"><i class="fa fa-circle-o"></i> All Pages</a>
              </li>
              @endif
              @if(auth()->user()->can('cms-page-create'))
              <li class="@if(isset($childMenu) && $childMenu == 'addCMS') active @endif">
                <a href="{{ route('addCMS') }}"><i class="fa fa-circle-o"></i> Add New Page</a>
              </li>
              @endif
            </ul>
          </li>
          @endif
          @if(auth()->user()->can('faq-view') || auth()->user()->can('faq-create') || auth()->user()->can('faq-edit') || auth()->user()->can('faq-delete'))
          <li class="treeview @if(isset($subMenu) && $subMenu == 'faq') active @endif">
            <a href="#"><i class="fa fa-question-circle"></i> FAQ
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if(auth()->user()->can('faq-view'))
              <li class="@if(isset($childMenu) && $childMenu == 'allFaqs') active @endif">
                <a href="{{ route('allFaqsFile') }}"><i class="fa fa-circle-o"></i> All FAQs</a>
              </li>
              @endif
              @if(auth()->user()->can('faq-create'))
              <li class="@if(isset($childMenu) && $childMenu == 'addFaq') active @endif">
                <a href="{{ route('addFaqFile') }}"><i class="fa fa-circle-o"></i> Add New FAQ</a>
              </li>
              @endif
              @if(auth()->user()->can('faq-category'))
              <li class="@if(isset($childMenu) && $childMenu == 'faqCats') active @endif">
                <a href="{{ route('faqCatsFile') }}"><i class="fa fa-circle-o"></i> FAQ Category</a>
              </li>
              @endif
            </ul>
          </li>
          @endif
          @if(auth()->user()->can('popup-view'))
          <li class="@if(isset($childMenu) && $childMenu == 'allPopups') active @endif">
            <a href="{{ route('popup.all') }}"><i class="fa fa-circle-o"></i> All Popups</a>
          </li>
          @endif
        </ul>
      </li>
      @endif


      @if(auth()->user()->can('menu-create') || auth()->user()->can('menu-view') || auth()->user()->can('menu-edit') || auth()->user()->can('menu-delete'))
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'menuManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Menu Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'menus') active @endif">
            <a href="{{ route('menu.all') }}"><i class="fa fa-circle-o"></i> Menus</a>
          </li>
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('event-create') || auth()->user()->can('event-view') || auth()->user()->can('event-edit') || auth()->user()->can('event-delete'))
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'eventManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Event Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        @if(auth()->user()->can('event-view'))
          <li class="@if(isset($childMenu) && $childMenu == 'allEvents') active @endif">
            <a href="{{ route('event.all') }}"><i class="fa fa-circle-o"></i> All Events</a>
          </li>
        @endif
        @if(auth()->user()->can('event-create'))
          <li class="@if(isset($childMenu) && $childMenu == 'addEvent') active @endif">
            <a href="{{ route('event.add') }}"><i class="fa fa-circle-o"></i> Add Event</a>
          </li>
        @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('project-create') || auth()->user()->can('project-view') || auth()->user()->can('project-edit') || auth()->user()->can('project-delete'))
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'projectManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Project Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if(auth()->user()->can('project-view'))
          <li class="@if(isset($childMenu) && $childMenu == 'allProjects') active @endif">
            <a href="{{ route('project.all') }}"><i class="fa fa-circle-o"></i> All Projects</a>
          </li>
          @endif
          @if(auth()->user()->can('project-create'))
          <li class="@if(isset($childMenu) && $childMenu == 'addProject') active @endif">
            <a href="{{ route('project.add') }}"><i class="fa fa-circle-o"></i> Add Project</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('job-create') || auth()->user()->can('job-view') || auth()->user()->can('job-edit') || auth()->user()->can('job-delete'))
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'careerManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Career Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if(auth()->user()->can('job-view'))
          <li class="@if(isset($childMenu) && $childMenu == 'allCareers') active @endif">
            <a href="{{ route('career.all') }}"><i class="fa fa-circle-o"></i> All Jobs</a>
          </li>
          @endif
          @if(auth()->user()->can('job-create'))
          <li class="@if(isset($childMenu) && $childMenu == 'addCareer') active @endif">
            <a href="{{ route('career.add') }}"><i class="fa fa-circle-o"></i> Add Job</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      
      

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'officeContact') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Office Contact</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">          
          <li class="@if(isset($childMenu) && $childMenu == 'allOfficeContact') active @endif">
            <a href="{{ route('office_contact.all') }}"><i class="fa fa-circle-o"></i> All Contact</a>
          </li>          
       
        </ul>
      </li>


      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'media') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Resources</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="treeview @if(isset($subMenu) && $subMenu == 'image') active @endif">
            <a href="#"><i class="fa fa-picture-o"></i> Images
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allImgs') active @endif">
                <a href="{{ route('media_all_imgs') }}"><i class="fa fa-circle-o"></i> All Images</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addImg') active @endif">
                <a href="{{ route('media_img_add') }}"><i class="fa fa-circle-o"></i> Add New Image</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'mngImgCats') active @endif">
                <a href="{{ route('media_all_img_cats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'video') active @endif">
            <a href="#"><i class="fa fa-video-camera"></i> Video
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allVids') active @endif">
                <a href="{{ route('allVideos') }}"><i class="fa fa-circle-o"></i> All Videos</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addVid') active @endif">
                <a href="{{ route('addVideo') }}"><i class="fa fa-circle-o"></i> Add New Video</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'vidCats') active @endif">
                <a href="{{ route('videoCats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'file') active @endif">
            <a href="#"><i class="fa fa-file-text"></i> Files
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allFls') active @endif">
                <a href="{{ route('allFiles') }}"><i class="fa fa-circle-o"></i> All Files</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addFl') active @endif">
                <a href="{{ route('addFile') }}"><i class="fa fa-circle-o"></i> Add New File</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'flCats') active @endif">
                <a href="{{ route('allFlCats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'link') active @endif">
            <a href="#"><i class="fa fa-link"></i> Links
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allLks') active @endif">
                <a href="{{ route('allLinks') }}"><i class="fa fa-circle-o"></i> All Links</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addLk') active @endif">
                <a href="{{ route('addLink') }}"><i class="fa fa-circle-o"></i> Add New Link</a>
              </li>  
            </ul>
          </li>

        </ul>
      </li>

      @if(auth()->user()->can('image-gallery-create') || auth()->user()->can('image-gallery-view') || 
      auth()->user()->can('video-gallery-create') || auth()->user()->can('video-gallery-view'))
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'file') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Gallery</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        @if(auth()->user()->can('image-gallery-create') || auth()->user()->can('image-gallery-view'))
          <li class="treeview @if(isset($subMenu) && $subMenu == 'image') active @endif">
            <a href="#"><i class="fa fa-picture-o"></i> Images Gallery
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if(auth()->user()->can('image-gallery-view'))
              <li class="@if(isset($childMenu) && $childMenu == 'allImgs') active @endif">
                <a href="{{ route('file_all_imgs') }}"><i class="fa fa-circle-o"></i> All Images</a>
              </li>
              @endif
              @if(auth()->user()->can('image-gallery-create'))
              <li class="@if(isset($childMenu) && $childMenu == 'addImg') active @endif">
                <a href="{{ route('file_img_add') }}"><i class="fa fa-circle-o"></i> Add New Image</a>
              </li>
              @endif
              @if(auth()->user()->can('image-gallery-create') || auth()->user()->can('image-gallery-edit'))
              <li class="@if(isset($childMenu) && $childMenu == 'mngImgCats') active @endif">
                <a href="{{ route('file_all_img_cats') }}"><i class="fa fa-circle-o"></i> Manage Image Gallery</a>
              </li>
              @endif
            </ul>
          </li>
        @endif
        @if(auth()->user()->can('video-gallery-create') || auth()->user()->can('video-gallery-view'))
          <li class="treeview @if(isset($subMenu) && $subMenu == 'video') active @endif">
            <a href="#"><i class="fa fa-video-camera"></i> Video Gallery
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if(auth()->user()->can('video-gallery-view'))
              <li class="@if(isset($childMenu) && $childMenu == 'allVids') active @endif">
                <a href="{{ route('allVideosFile') }}"><i class="fa fa-circle-o"></i> All Videos</a>
              </li>
              @endif
              @if(auth()->user()->can('image-gallery-create'))
              <li class="@if(isset($childMenu) && $childMenu == 'addVid') active @endif">
                <a href="{{ route('addVideoFile') }}"><i class="fa fa-circle-o"></i> Add New Video</a>
              </li>
              @endif
              @if(auth()->user()->can('image-gallery-create') || auth()->user()->can('image-gallery-edit'))
              <li class="@if(isset($childMenu) && $childMenu == 'vidCats') active @endif">
                <a href="{{ route('videoCatsFile') }}"><i class="fa fa-circle-o"></i> Manage Video Gallery</a>
              </li>
              @endif
            </ul>
          </li>
        @endif
        </ul>
      </li>
      @endif

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'enquiryManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Enquiry Forms</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">         
          <li class="@if(isset($childMenu) && $childMenu == 'enquiryData') active @endif">
            <a href="{{ route('enquiry.data') }}"><i class="fa fa-circle-o"></i> All Records</a>
          </li>      
        </ul>
      </li>--}}
      
      <!--li class="header">SETTINGS</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'settings') active @endif">
        <a href="#">
          <i class="fa fa-cogs" aria-hidden="true"></i> <span>Settings</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        @if(auth()->user()->can('general-settings-access'))
          <li class="@if(isset($childMenu) && $childMenu == 'genSett') active @endif">
            <a href="{{ route('gen_sett') }}"><i class="fa fa-circle-o"></i> General Settings</a>
          </li>
        @endif
        
        
          <li class="@if(isset($childMenu) && $childMenu == 'profile') active @endif">
            <a href="{{ route('usr_profile') }}"><i class="fa fa-circle-o"></i> My Profile</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'cngPwd') active @endif">
            <a href="{{ route('cng_pwd') }}"><i class="fa fa-circle-o"></i> Change Password</a>
          </li>
        </ul>
      </li>
      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<!-- =============================================== -->