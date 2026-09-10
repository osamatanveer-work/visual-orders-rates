<!-- ========== Left Sidebar Start ========== -->
<div class="app-menu">

    <div class="logo-box">
        <a href="#" class="logo-light">
            {{-- <img src="{{ asset('assets/logo4.png') }}" alt="logo" class="w-100"> --}}
            <small  alt="logo" class="w-100 fw-bold">Rate Shopper</small>
           
        </a>
        <a href="#" class="logo-dark">
            {{-- <img src="{{ asset('assets/logo4.png') }}" alt="dark logo" class="w-50 m-auto d-block"> --}}
            <small  alt="logo" class="w-100 fw-bold">Rate Shopper</small>
        </a>
    </div>

    <div class="scrollbar h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{ Vite::asset('resources/images/users/user-1.jpg') }}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">Geneva
                    Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="{{ route('user.profile') }}" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>

        <!--- Sidemenu -->

        <ul id="side-menu" class="menu">

            {{-- <li class="menu-title">Navigation</li> --}}

            {{-- <li class="menu-item ">
                <a href="{{ route('dashboard') }}"  class="menu-link">
                    <span class="menu-icon">
                        <i data-feather="airplay"></i>
                    </span>
                    <span class="menu-text"> Dashboards </span>
                    <span class="badge bg-success rounded-pill ms-auto">4</span>
                </a>
                
            </li> --}}

           

            {{-- <li class="menu-item">
                <a href="{{ route('second', ['apps', 'calendar']) }}" class="menu-link">
                    <span class="menu-icon"><i data-feather="calendar"></i></span>
                    <span class="menu-text"> Calendar </span>
                </a>
            </li> --}}

            {{-- <li class="menu-item">
                <a href="{{ route('second', ['apps', 'chat']) }}" class="menu-link">
                    <span class="menu-icon"><i data-feather="message-square"></i></span>
                    <span class="menu-text"> Chat </span>
                </a>
            </li> --}}

            {{-- <li class="menu-item">
                <a href="#sidebarEcommerce" data-bs-toggle="collapse" class="menu-link">
                    <span class="menu-icon"><i data-feather="shopping-cart"></i></span>
                    <span class="menu-text"> Ecommerce </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'dashboard']) }}"><span class="menu-text">Dashboard</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'products']) }}"><span class="menu-text">Products</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'product-detail']) }}"><span class="menu-text">Product Detail</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'product-edit']) }}"><span class="menu-text">Add Product</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'customers']) }}"><span class="menu-text">Customers</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'orders']) }}"><span class="menu-text">Orders</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'order-detail']) }}"><span class="menu-text">Order Detail</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'sellers']) }}"><span class="menu-text">Sellers</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'cart']) }}"><span class="menu-text">Shopping Cart</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ecommerce', 'checkout']) }}"><span class="menu-text">Checkout</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarCrm" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="users"></i></span>
                    <span class="menu-text"> CRM </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCrm">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['crm', 'dashboard']) }}"><span class="menu-text">Dashboard</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['crm', 'contacts']) }}"><span class="menu-text">Contacts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['crm', 'opportunities']) }}"><span class="menu-text">Opportunities</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['crm', 'leads']) }}"><span class="menu-text">Leads</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['crm', 'customers']) }}"><span class="menu-text">Customers</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarEmail" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="mail"></i></span>
                    <span class="menu-text"> Email </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEmail">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['email', 'inbox']) }}"><span class="menu-text">Inbox</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['email', 'read']) }}"><span class="menu-text">Read Email</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['email', 'compose']) }}"><span class="menu-text">Compose Email</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['email', 'templates']) }}"><span class="menu-text">Email Templates</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="{{ route('second', ['apps', 'social-feed']) }}">
                    <span class="menu-icon"><i data-feather="rss"></i></span>
                    <span class="menu-text"> Social Feed </span>
                    <span class="badge bg-pink ms-auto">Hot</span>
                </a>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="{{ route('company.all') }}">
                    <span class="menu-icon"><i data-feather="activity"></i></span>
                    <span class="menu-text"> Companies </span>
                </a>
            </li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('company.all') }}" >
                    <span class="menu-icon"><i data-feather="activity"></i></span>
                    <span class="menu-text"> Companies </span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
                {{-- <div class="collapse" id="companies">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" ><span class="menu-text">All Companies</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('company.create') }}"><span class="menu-text">Create New</span></a>
                        </li>
                    </ul>
                </div> --}}
            </li> 

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarProjects" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="briefcase"></i></span>
                    <span class="menu-text"> Projects </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProjects">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['project', 'list']) }}"><span class="menu-text">List</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['project', 'detail']) }}"><span class="menu-text">Detail</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['project', 'create']) }}"><span class="menu-text">Create Project</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarTasks" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="clipboard"></i></span>
                    <span class="menu-text"> Tasks </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTasks">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['task', 'list']) }}"><span class="menu-text">List</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['task', 'details']) }}"><span class="menu-text">Details</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['task', 'kanban-board']) }}"><span class="menu-text">Kanban Board</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('carrier.all') }}" >
                    <span class="menu-icon"> <i data-feather="truck" class="icon-dual"></i></span>
                    <span class="menu-text"> Carriers </span>
                
                </a>
                {{-- <div class="collapse" id="sidebarContacts">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="#"><span class="menu-text">Members List</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="#"><span class="menu-text">Profile</span></a>
                        </li>
                    </ul>
                </div> --}}
            </li>

            <li class="menu-item">
                <a class="menu-link" href="{{ route('markup.all') }}" >
                    <span class="menu-icon"><i data-feather="aperture"></i></span>
                    <span class="menu-text"> Markup Templates </span>
                   
                </a>
               
            </li> 

            <li class="menu-item">
                <a class="menu-link" href="{{ route('users.all') }}" >
                    <span class="menu-icon"><i data-feather="user"></i></span>
                    <span class="menu-text"> Users </span>
                   
                </a>
               
            </li> 

            <li class="menu-item">
                <a class="menu-link" href="{{ route('box.all') }}" >
                    <span class="menu-icon"><i data-feather="box"></i></span>
                    <span class="menu-text"> Boxes </span>
                   
                </a>
               
            </li> 
            <li class="menu-item">
                <a class="menu-link" href="{{ route('rateqoute.all') }}" >
                    <span class="menu-icon"><i data-feather="dollar-sign"></i></span>
                    <span class="menu-text"> Rate Quote </span>
                   
                </a>
               
            </li>

            <li class="menu-item">
                <a class="menu-link" href="/telescope/requests" target="_blank" >
                    <span class="menu-icon">
                        <i data-feather="cpu"></i>
                    </span>
                    <span class="menu-text"> Telescope </span>

                </a>
            </li>
            
            

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarTickets" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="aperture"></i></span>
                    <span class="menu-text"> Tickets </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTickets">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tickets', 'list']) }}"><span class="menu-text">List</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tickets', 'detail']) }}"><span class="menu-text">Detail</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="{{ route('second', ['apps', 'file-manager']) }}">
                    <span class="menu-icon"><i data-feather="folder-plus"></i></span>
                    <span class="menu-text"> File Manager </span>
                </a>
            </li> --}}

            {{-- <li class="menu-title">Custom</li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarAuth" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="file-text"></i></span>
                    <span class="menu-text"> Auth Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAuth">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'login']) }}"><span class="menu-text">Log In</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'login-2']) }}"><span class="menu-text">Log In 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'register']) }}"><span class="menu-text">Register</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'register-2']) }}"><span class="menu-text">Register 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'signin-signup']) }}"><span class="menu-text">Signin - Signup</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'signin-signup-2']) }}"><span class="menu-text">Signin - Signup 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'recoverpw']) }}"><span class="menu-text">Recover Password</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'recoverpw-2']) }}"><span class="menu-text">Recover Password 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'lock-screen']) }}"><span class="menu-text">Lock Screen</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'lock-screen-2']) }}"><span class="menu-text">Lock Screen 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'logout']) }}"><span class="menu-text">Logout</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'logout-2']) }}"><span class="menu-text">Logout 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'confirm-mail']) }}"><span class="menu-text">Confirm Mail</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['auth', 'confirm-mail-2']) }}"><span class="menu-text">Confirm Mail 2</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}
{{-- 
            <li class="menu-item">
                <a class="menu-link" href="#sidebarExpages" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="package"></i></span>
                    <span class="menu-text"> Extra Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarExpages">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'starter']) }}"><span class="menu-text">Starter</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'timeline']) }}"><span class="menu-text">Timeline</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'sitemap']) }}"><span class="menu-text">Sitemap</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'invoice']) }}"><span class="menu-text">Invoice</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'faqs']) }}"><span class="menu-text">FAQs</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'search-results']) }}"><span class="menu-text">Search Results</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'pricing']) }}"><span class="menu-text">Pricing</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'maintenance']) }}"><span class="menu-text">Maintenance</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'coming-soon']) }}"><span class="menu-text">Coming Soon</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', 'gallery']) }}"><span class="menu-text">Gallery</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', '404']) }}"><span class="menu-text">Error 404</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', '404-two']) }}"><span class="menu-text">Error 404 Two</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', '404-alt']) }}"><span class="menu-text">Error 404-alt</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', '500']) }}"><span class="menu-text">Error 500</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['pages', '500-two']) }}"><span class="menu-text">Error 500 Two</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarLayouts" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="layout"></i></span>
                    <span class="menu-text"> Layouts </span>
                    <span class="badge bg-blue ms-auto">New</span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="#"><span class="menu-text">Vertical</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['layouts-eg', 'horizontal']) }}"><span class="menu-text">Horizontal</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['layouts-eg', 'detached']) }}"><span class="menu-text">Detached</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['layouts-eg', 'two-column']) }}"><span class="menu-text">Two Column Menu</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['layouts-eg', 'two-tone-icons']) }}"><span class="menu-text">Two Tones Icons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['layouts-eg', 'preloader']) }}"><span class="menu-text">Preloader</span></a>
                        </li>
                    </ul>
                </div>
            </li> --}}
{{-- 
            <li class="menu-title">Components</li> --}}

            {{-- <li class="menu-item">
                <a class="menu-link" href="#sidebarBaseui" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="pocket"></i></span>
                    <span class="menu-text"> Base UI </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBaseui">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'buttons']) }}"><span class="menu-text">Buttons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'cards']) }}"><span class="menu-text">Cards</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'avatars']) }}"><span class="menu-text">Avatars</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'portlets']) }}"><span class="menu-text">Portlets</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'tabs-accordions']) }}"><span class="menu-text">Tabs & Accordions</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'modals']) }}"><span class="menu-text">Modals</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'progress']) }}"><span class="menu-text">Progress</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'notifications']) }}"><span class="menu-text">Notifications</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'offcanvas']) }}"><span class="menu-text">Offcanvas</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'placeholders']) }}"><span class="menu-text">Placeholders</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'spinners']) }}"><span class="menu-text">Spinners</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'images']) }}"><span class="menu-text">Images</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'carousel']) }}"><span class="menu-text">Carousel</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'list-group']) }}"><span class="menu-text">List Group</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'video']) }}"><span class="menu-text">Embed Video</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'dropdowns']) }}"><span class="menu-text">Dropdowns</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'ribbons']) }}"><span class="menu-text">Ribbons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'tooltips-popovers']) }}"><span class="menu-text">Tooltips & Popovers</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'general']) }}"><span class="menu-text">General UI</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'typography']) }}"><span class="menu-text">Typography</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['ui', 'grid']) }}"><span class="menu-text">Grid</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarExtendedui" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="layers"></i></span>
                    <span class="menu-text"> Extended UI </span>
                    <span class="badge bg-info ms-auto">Hot</span>
                </a>
                <div class="collapse" id="sidebarExtendedui">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'nestable']) }}"><span class="menu-text">Nestable List</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'range-slider']) }}"><span class="menu-text">Range Slider</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'dragula']) }}"><span class="menu-text">Dragula</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'animation']) }}"><span class="menu-text">Animation</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'sweet-alert']) }}"><span class="menu-text">Sweet Alert</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'tour']) }}"><span class="menu-text">Tour Page</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'scrollspy']) }}"><span class="menu-text">Scrollspy</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['extended', 'loading-buttons']) }}"><span class="menu-text">Loading Buttons</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="{{ route('any', 'widgets') }}">
                    <span class="menu-icon"><i data-feather="gift"></i></span>
                    <span class="menu-text"> Widgets </span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarIcons" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="cpu"></i></span>
                    <span class="menu-text"> Icons </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarIcons">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'material-symbols']) }}"><span class="menu-text">Material Symbols</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'two-tone']) }}"><span class="menu-text">Two Tone Icons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'feather']) }}"><span class="menu-text">Feather Icons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'mdi']) }}"><span class="menu-text">Material Design Icons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'dripicons']) }}"><span class="menu-text">Dripicons</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'font-awesome']) }}"><span class="menu-text">Font Awesome 5</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'themify']) }}"><span class="menu-text">Themify</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'simple-line']) }}"><span class="menu-text">Simple Line</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['icons', 'weather']) }}"><span class="menu-text">Weather</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarForms" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="bookmark"></i></span>
                    <span class="menu-text"> Forms </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarForms">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'elements']) }}"><span class="menu-text">General Elements</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'advanced']) }}"><span class="menu-text">Advanced</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'validation']) }}"><span class="menu-text">Validation</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'pickers']) }}"><span class="menu-text">Pickers</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'wizard']) }}"><span class="menu-text">Wizard</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'masks']) }}"><span class="menu-text">Masks</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'quilljs']) }}"><span class="menu-text">Quilljs Editor</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'file-uploads']) }}"><span class="menu-text">File Uploads</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'x-editable']) }}"><span class="menu-text">X Editable</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['forms', 'image-crop']) }}"><span class="menu-text">Image Crop</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarTables" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="grid"></i></span>
                    <span class="menu-text"> Tables </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'basic']) }}"><span class="menu-text">Basic Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'datatables']) }}"><span class="menu-text">Data Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'editable']) }}"><span class="menu-text">Editable Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'responsive']) }}"><span class="menu-text">Responsive Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'footables']) }}"><span class="menu-text">FooTable</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'bootstrap']) }}"><span class="menu-text">Bootstrap Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'tablesaw']) }}"><span class="menu-text">Tablesaw Tables</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['tables', 'jsgrid']) }}"><span class="menu-text">JsGrid Tables</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarCharts" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="bar-chart-2"></i></span>
                    <span class="menu-text"> Charts </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCharts">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'apex']) }}"><span class="menu-text">Apex Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'flot']) }}"><span class="menu-text">Flot Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'morris']) }}"><span class="menu-text">Morris Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'chartjs']) }}"><span class="menu-text">Chartjs Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'peity']) }}"><span class="menu-text">Peity Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'chartist']) }}"><span class="menu-text">Chartist Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'c3']) }}"><span class="menu-text">C3 Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'sparklines']) }}"><span class="menu-text">Sparklines Charts</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['charts', 'knob']) }}"><span class="menu-text">Jquery Knob Charts</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarMaps" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="map"></i></span>
                    <span class="menu-text"> Maps </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMaps">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['maps', 'google']) }}"><span class="menu-text">Google Maps</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['maps', 'vector']) }}"><span class="menu-text">Vector Maps</span></a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('second', ['maps', 'mapael']) }}"><span class="menu-text">Mapael Maps</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="share-2"></i></span>
                    <span class="menu-text"> Multi Level </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMultilevel">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="#sidebarMultilevel2" data-bs-toggle="collapse">
                                <span class="menu-text"> Second Level</span> <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultilevel2">
                                <ul class="sub-menu">
                                    <li class="menu-item">
                                        <a class="menu-link" href="javascript: void(0);"><span class="menu-text">Item 1</span></a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="javascript: void(0);"><span class="menu-text">Item 2</span></a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#sidebarMultilevel3" data-bs-toggle="collapse">
                                <span class="menu-text">Third Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultilevel3">
                                <ul class="sub-menu">
                                    <li class="menu-item">
                                        <a class="menu-link" href="javascript: void(0);"><span class="menu-text">Item 1</span></a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="#sidebarMultilevel4" data-bs-toggle="collapse">
                                            <span class="menu-text">Item 2</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarMultilevel4">
                                            <ul class="sub-menu">
                                                <li class="menu-item">
                                                    <a class="menu-link" href="javascript: void(0);">
                                                        <span class="menu-text">Item 1</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a class="menu-link" href="javascript: void(0);">
                                                        <span class="menu-text">Item 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li> --}}
        </ul>

        {{-- </div> --}}
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
