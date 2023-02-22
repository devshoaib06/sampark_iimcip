<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel" style="background-color: #fff; text-align: center;">
      <a href="{{ route('dashboard') }}" id="brandBox">
        <img src="{{ asset('public/images/logo.png') }}" class="brandBox_logo">
      </a>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    @if(Auth::user()->user_type == 1)
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>

      <li class="">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>




      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'companyType') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Company Type</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allCompanyType') active @endif">
            <a href="{{ route('allcompanyType') }}"><i class="fa fa-circle-o"></i> All Company Type</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addCompanyType') active @endif">
            <a href="{{ route('addcompanyType') }}"><i class="fa fa-circle-o"></i> Add Company Type</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'sponsorType') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Sponsor</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allsponsorType') active @endif">
            <a href="{{ route('allsponsorType') }}"><i class="fa fa-circle-o"></i> All Sponsor</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addsponsorType') active @endif">
            <a href="{{ route('addsponsorType') }}"><i class="fa fa-circle-o"></i> Add Sponsor</a>
          </li>
        </ul>
      </li>
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'locationType') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Service Location</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'alllocationType') active @endif">
            <a href="{{ route('alllocationType') }}"><i class="fa fa-circle-o"></i> All Service Location</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addlocationType') active @endif">
            <a href="{{ route('addlocationType') }}"><i class="fa fa-circle-o"></i> Add Service Location</a>
          </li>
        </ul>
      </li>
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'programmeType') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Programme</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allprogrammeType') active @endif">
            <a href="{{ route('allprogrammeType') }}"><i class="fa fa-circle-o"></i> All Programme</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addprogrammeType') active @endif">
            <a href="{{ route('addprogrammeType') }}"><i class="fa fa-circle-o"></i> Add Program</a>
          </li>
        </ul>
      </li>
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'legalStatus') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Legal Status</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'alllegalStatus') active @endif">
            <a href="{{ route('alllegalStatus') }}"><i class="fa fa-circle-o"></i> All Legal Status</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addlegalStatus') active @endif">
            <a href="{{ route('addlegalStatus') }}"><i class="fa fa-circle-o"></i> Add Legal Status</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'industryExpertise') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Industry Expertise</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allIndustryExpertise') active @endif">
            <a href="{{ route('allindustryExpertise') }}"><i class="fa fa-circle-o"></i> All Industry Expertise</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addIndustryExpertise') active @endif">
            <a href="{{ route('addindustryExpertise') }}"><i class="fa fa-circle-o"></i> Add Industry Expertise</a>
          </li>
        </ul>
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
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'category') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span> Post Categories</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allCategory') active @endif">
            <a href="{{ route('allCats') }}"><i class="fa fa-circle-o"></i> All Post Categories</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addCategory') active @endif">
            <a href="{{ route('addCats') }}"><i class="fa fa-circle-o"></i> Add Post Category</a>
          </li>
        </ul>
      </li>


      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'management') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Startups</span>
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

          <li class="@if(isset($childMenu) && $childMenu == 'usersList') active @endif">
            <a href="{{ route('add_invitations') }}"><i class="fa fa-circle-o"></i> Invitations</a>
          </li>

        </ul>
      </li>


      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'pmmanagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Portfolio Manager</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">

          <li class="@if(isset($childMenu) && $childMenu == 'pmList') active @endif">
            <a href="{{ route('pm_list') }}"><i class="fa fa-circle-o"></i> All PM</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'pmCreate') active @endif">
            <a href="{{ route('crte_pm') }}"><i class="fa fa-circle-o"></i> Add PM</a>
          </li>


        </ul>
      </li>


      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'mentormanagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Mentors</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">

          <li class="@if(isset($childMenu) && $childMenu == 'mentorList') active @endif">
            <a href="{{ route('mentor_list') }}"><i class="fa fa-circle-o"></i> All Mentors</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'mentorAdd') active @endif">
            <a href="{{ route('crte_mentor') }}"><i class="fa fa-circle-o"></i> Add Mentors</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'parameters') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Parameters Master</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allparameters') active @endif">
            <a href="{{ route('allparameters') }}"><i class="fa fa-circle-o"></i> All Parameters</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addparameters') active @endif">
            <a href="{{ route('addparameters') }}"><i class="fa fa-circle-o"></i> Add Parameters</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'questions') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Questions Master</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allquestions') active @endif">
            <a href="{{ route('allquestions') }}"><i class="fa fa-circle-o"></i> All Questions</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addquestions') active @endif">
            <a href="{{ route('addquestions') }}"><i class="fa fa-circle-o"></i> Add Questions</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'incubatees') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Incubatee Master</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allincubatees') active @endif">
            <a href="{{ route('allincubatees') }}"><i class="fa fa-circle-o"></i> All Incubatees</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addincubatees') active @endif">
            <a href="{{ route('addincubatees') }}"><i class="fa fa-circle-o"></i> Add Incubatees</a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'mentordiagonostics') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Mentor Diagonostics</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'allmentordiagonostics') active @endif">
            <a href="{{ route('allmentordiagonostics') }}"><i class="fa fa-circle-o"></i> All Mentor Diagonostics</a>
          </li>

          {{-- <li class="@if(isset($childMenu) && $childMenu == 'addincubatees') active @endif">
            <a href="{{ route('addincubatees') }}"><i class="fa fa-circle-o"></i> Add Incubatees</a>
      </li> --}}
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

    <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'postGuide') active @endif">
      <a href="#">
        <i class="fa fa-bars" aria-hidden="true"></i> <span>Post Guidelines</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">

        <li class="@if(isset($childMenu) && $childMenu == 'addmainPostGuide') active @endif">
          <a href="{{ route('main_post.add_guide') }}"><i class="fa fa-circle-o"></i> Add/Edit Post Guidelines</a>
        </li>
      </ul>
    </li>


    <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'emailTemplate') active @endif">
      <a href="#">
        <i class="fa fa-bars" aria-hidden="true"></i> <span>Email Template</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="@if(isset($childMenu) && $childMenu == 'allCompanyType') active @endif">
          <a href="{{ route('allemailTemplate') }}"><i class="fa fa-circle-o"></i> All Email Template</a>
        </li>

        <li class="@if(isset($childMenu) && $childMenu == 'addCompanyType') active @endif">
          <a href="{{ route('addemailTemplate') }}"><i class="fa fa-circle-o"></i> Add Email Template</a>
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
    @if(auth()->user()->can('faq-view') || auth()->user()->can('faq-create') || auth()->user()->can('faq-edit') ||
    auth()->user()->can('faq-delete'))
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


    @if(auth()->user()->can('menu-create') || auth()->user()->can('menu-view') || auth()->user()->can('menu-edit') ||
    auth()->user()->can('menu-delete'))
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

    @if(auth()->user()->can('event-create') || auth()->user()->can('event-view') || auth()->user()->can('event-edit') ||
    auth()->user()->can('event-delete'))
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

    @if(auth()->user()->can('project-create') || auth()->user()->can('project-view') ||
    auth()->user()->can('project-edit') || auth()->user()->can('project-delete'))
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

    @if(auth()->user()->can('job-create') || auth()->user()->can('job-view') || auth()->user()->can('job-edit') ||
    auth()->user()->can('job-delete'))
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
    @endif

    @if(Auth::user()->user_type == 4)
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li class="">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'rpt_management') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Incubate Report </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'rpt_List') active @endif">
            <a href="{{ route('admin.incubate_report_list') }}"><i class="fa fa-circle-o"></i> List </a>
          </li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span> Appointment </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="">
            <a href="{{ route('admin.appointment_list') }}"><i class="fa fa-circle-o"></i> Appointment List </a>
          </li>
        </ul>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'management') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Startups</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'pmList') active @endif">
            <!--{{ route('startup_assign_list') }}-->
            <a href="{{ route('startup_assign') }}"><i class="fa fa-circle-o"></i>My Startups List</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'usersList') active @endif">
            <a href="{{ route('users_list') }}"><i class="fa fa-circle-o"></i>Startups List</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'createUser') active @endif">
            <a href="{{ route('crte_user') }}"><i class="fa fa-circle-o"></i> Add Startup</a>
          </li>

          <!--<li class="@if(isset($childMenu) && $childMenu == 'usersList') active @endif">
					<a href="{{ route('add_invitations') }}"><i class="fa fa-circle-o"></i> Invitations</a>
				  </li>-->

        </ul>
      </li>


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
    @endif




















  </section>
  <!-- /.sidebar -->
</aside>

<!-- =============================================== -->