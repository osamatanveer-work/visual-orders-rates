<div class="app-menu menuitem-active">
    <div class="logo-box">
        <a href="javascript:void(0);" class="logo-light">
            <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="logo" class="logo-lg" />
            <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="small logo" class="logo-sm" />
        </a>
        <a href="javascript:void(0);" class="logo-dark">
            <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="dark logo" class="logo-lg" />
            <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="small logo" class="logo-sm" />
        </a>
    </div>

    <div class="scrollbar">
        <ul class="menu">

            <li class="menu-item">
                <a class="menu-link" href="#menuDashboards" id="topnav-dashboard" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="airplay"></i></span>
                    <span class="menu-text"> Dashboards </span>
                    <span class="badge bg-success rounded-pill ms-auto">4</span>
                </a>
                <div class="collapse" id="menuDashboards">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="{{ route('any', 'index') }}" class="menu-link"><span class="menu-text">Dashboard 1</span></a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('any', 'dashboard-2') }}" class="menu-link"><span class="menu-text">Dashboard 2</span></a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('any', 'dashboard-3') }}" class="menu-link"><span class="menu-text">Dashboard 3</span></a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('any', 'dashboard-4') }}" class="menu-link"><span class="menu-text">Dashboard 4</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#menuApps" id="topnav-apps" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="aperture"></i></span>
                    <span class="menu-text"> Apps </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="menuApps">
                    <ul class="sub-menu">

                        <li class="menu-item">
                            <a href="{{ route('second', ['apps', 'calendar']) }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="calendar"></i></span>
                                <span class="menu-text"> Calendar </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('second', ['apps', 'chat']) }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="message-square"></i></span>
                                <span class="menu-text"> Chat </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#" id="topnav-ecommerce" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="shopping-cart"></i></span>
                                <span class="menu-text"> Ecommerce </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuEcommerce">
                                <ul class="sub-menu">
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'dashboard']) }}" class="menu-link"><span class="menu-text">Dashboard</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'products']) }}" class="menu-link"><span class="menu-text">Products</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'product-detail']) }}" class="menu-link"><span class="menu-text">Product Detail</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'product-edit']) }}" class="menu-link"><span class="menu-text">Add Product</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'customers']) }}" class="menu-link"><span class="menu-text">Customers</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'orders']) }}" class="menu-link"><span class="menu-text">Orders</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'order-detail']) }}" class="menu-link"><span class="menu-text">Order Detail</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'sellers']) }}" class="menu-link"><span class="menu-text">Sellers</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'cart']) }}" class="menu-link"><span class="menu-text">Shopping Cart</span></a></li>
                                    <li class="menu-item"><a href="{{ route('second', ['ecommerce', 'checkout']) }}" class="menu-link"><span class="menu-text">Checkout</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuCrm" id="topnav-crm" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="users"></i></span>
                                <span class="menu-text"> CRM </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuCrm">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['crm', 'dashboard']) }}" class="menu-link"><span class="menu-text">Dashboard</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['crm', 'contacts']) }}" class="menu-link"><span class="menu-text">Contacts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['crm', 'opportunities']) }}" class="menu-link"><span class="menu-text">Opportunities</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['crm', 'leads']) }}" class="menu-link"><span class="menu-text">Leads</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['crm', 'customers']) }}" class="menu-link"><span class="menu-text">Customers</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuEmail" id="topnav-email" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="mail"></i></span>
                                <span class="menu-text"> Email </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuEmail">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['email', 'inbox']) }}" class="menu-link"><span class="menu-text">Inbox</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['email', 'read']) }}" class="menu-link"><span class="menu-text">Read Email</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['email', 'compose']) }}" class="menu-link"><span class="menu-text">Compose Email</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['email', 'templates']) }}" class="menu-link"><span class="menu-text">Email Templates</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('second', ['apps', 'social-feed']) }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="rss"></i></span>
                                <span class="menu-text"> Social Feed </span>
                                <span class="badge bg-pink ms-auto">Hot</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('second', ['apps', 'companies']) }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="activity"></i></span>
                                <span class="menu-text"> Companies </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuProjects" id="topnav-project" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="briefcase"></i></span>
                                <span class="menu-text"> Projects </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuProjects">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['project', 'list']) }}" class="menu-link"><span class="menu-text">List</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['project', 'detail']) }}" class="menu-link"><span class="menu-text">Detail</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['project', 'create']) }}" class="menu-link"><span class="menu-text">Create Project</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuTasks" id="topnav-task" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="clipboard"></i></span>
                                <span class="menu-text"> Tasks </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuTasks">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['task', 'list']) }}" class="menu-link"><span class="menu-text">List</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['task', 'details']) }}" class="menu-link"><span class="menu-text">Details</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['task', 'kanban-board']) }}" class="menu-link"><span class="menu-text">Kanban Board</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuContacts" id="topnav-contact" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="book"></i></span>
                                <span class="menu-text"> Contacts </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuContacts">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['contacts', 'list']) }}" class="menu-link"><span class="menu-text">Members List</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['contacts', 'profile']) }}" class="menu-link"><span class="menu-text">Profile</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuTickets" id="topnav-tickets" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="aperture"></i></span>
                                <span class="menu-text"> Tickets </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuTickets">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['tickets', 'list']) }}" class="menu-link"><span class="menu-text">List</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tickets', 'detail']) }}" class="menu-link"><span class="menu-text">Detail</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('second', ['apps', 'file-manager']) }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="folder-plus"></i></span>
                                <span class="menu-text"> File Manager </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#menuBaseui" id="topnav-ui" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="pocket"></i></span>
                    <span class="menu-text"> Base UI </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse collapse-lg" id="menuBaseui">
                    <ul class="sub-menu">
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'buttons']) }}" class="menu-link"><span class="menu-text">Buttons</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'cards']) }}" class="menu-link"><span class="menu-text">Cards</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'avatars']) }}" class="menu-link"><span class="menu-text">Avatars</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'portlets']) }}" class="menu-link"><span class="menu-text">Portlets</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'tabs-accordions']) }}" class="menu-link"><span class="menu-text">Tabs & Accordions</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'modals']) }}" class="menu-link"><span class="menu-text">Modals</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'progress']) }}" class="menu-link"><span class="menu-text">Progress</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'notifications']) }}" class="menu-link"><span class="menu-text">Notifications</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'offcanvas']) }}" class="menu-link"><span class="menu-text">Offcanvas</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'placeholders']) }}" class="menu-link"><span class="menu-text">Placeholders</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'spinners']) }}" class="menu-link"><span class="menu-text">Spinners</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'images']) }}" class="menu-link"><span class="menu-text">Images</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'carousel']) }}" class="menu-link"><span class="menu-text">Carousel</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'list-group']) }}" class="menu-link"><span class="menu-text">List Group</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'video']) }}" class="menu-link"><span class="menu-text">Embed Video</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'dropdowns']) }}" class="menu-link"><span class="menu-text">Dropdowns</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'ribbons']) }}" class="menu-link"><span class="menu-text">Ribbons</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'tooltips-popovers']) }}" class="menu-link"><span class="menu-text">Tooltips & Popovers</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'general']) }}" class="menu-link"><span class="menu-text">General UI</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'typography']) }}" class="menu-link"><span class="menu-text">Typography</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['ui', 'grid']) }}" class="menu-link"><span class="menu-text">Grid</span></a></li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#menuComponents" id="topnav-components" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="layout"></i></span>
                    <span class="menu-text"> Components </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="menuComponents">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="#menuExtendedui" id="topnav-extendedui" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="layers"></i></span>
                                <span class="menu-text"> Extended UI </span>
                                <span class="badge bg-info ms-auto">Hot</span>
                            </a>
                            <div class="collapse" id="menuExtendedui">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'nestable']) }}" class="menu-link"><span class="menu-text">Nestable List</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'range-slider']) }}" class="menu-link"><span class="menu-text">Range Slider</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'dragula']) }}" class="menu-link"><span class="menu-text">Dragula</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'animation']) }}" class="menu-link"><span class="menu-text">Animation</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'sweet-alert']) }}" class="menu-link"><span class="menu-text">Sweet Alert</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'tour']) }}" class="menu-link"><span class="menu-text">Tour Page</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'scrollspy']) }}" class="menu-link"><span class="menu-text">Scrollspy</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['extended', 'loading-buttons']) }}" class="menu-link"><span class="menu-text">Loading Buttons</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('any', 'widgets') }}" class="menu-link">
                                <span class="menu-icon"><i data-feather="gift"></i></span>
                                <span class="menu-text"> Widgets </span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuIcons" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="cpu"></i></span>
                                <span class="menu-text"> Icons </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuIcons">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'material-symbols']) }}" class="menu-link"><span class="menu-text">Material Symbols</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'two-tone']) }}" class="menu-link"><span class="menu-text">Two Tone Icons</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'feather']) }}" class="menu-link"><span class="menu-text">Feather Icons</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'mdi']) }}" class="menu-link"><span class="menu-text">Material Design Icons</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'dripicons']) }}" class="menu-link"><span class="menu-text">Dripicons</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'font-awesome']) }}" class="menu-link"><span class="menu-text">Font Awesome 5</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'themify']) }}" class="menu-link"><span class="menu-text">Themify</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'simple-line']) }}" class="menu-link"><span class="menu-text">Simple Line</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['icons', 'weather']) }}" class="menu-link"><span class="menu-text">Weather</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuForms" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="bookmark"></i></span>
                                <span class="menu-text"> Forms </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuForms">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'elements']) }}" class="menu-link"><span class="menu-text">General Elements</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'advanced']) }}" class="menu-link"><span class="menu-text">Advanced</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'validation']) }}" class="menu-link"><span class="menu-text">Validation</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'pickers']) }}" class="menu-link"><span class="menu-text">Pickers</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'wizard']) }}" class="menu-link"><span class="menu-text">Wizard</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'masks']) }}" class="menu-link"><span class="menu-text">Masks</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'quilljs']) }}" class="menu-link"><span class="menu-text">Quilljs Editor</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'file-uploads']) }}" class="menu-link"><span class="menu-text">File Uploads</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'x-editable']) }}" class="menu-link"><span class="menu-text">X Editable</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['forms', 'image-crop']) }}" class="menu-link"><span class="menu-text">Image Crop</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuTables" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="grid"></i></span>
                                <span class="menu-text"> Tables </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuTables">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'basic']) }}" class="menu-link"><span class="menu-text">Basic Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'datatables']) }}" class="menu-link"><span class="menu-text">Data Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'editable']) }}" class="menu-link"><span class="menu-text">Editable Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'responsive']) }}" class="menu-link"><span class="menu-text">Responsive Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'footables']) }}" class="menu-link"><span class="menu-text">FooTable</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'bootstrap']) }}" class="menu-link"><span class="menu-text">Bootstrap Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'tablesaw']) }}" class="menu-link"><span class="menu-text">Tablesaw Tables</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['tables', 'jsgrid']) }}" class="menu-link"><span class="menu-text">JsGrid Tables</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuCharts" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="bar-chart-2"></i></span>
                                <span class="menu-text"> Charts </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuCharts">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'apex']) }}" class="menu-link"><span class="menu-text">Apex Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'flot']) }}" class="menu-link"><span class="menu-text">Flot Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'morris']) }}" class="menu-link"><span class="menu-text">Morris Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'chartjs']) }}" class="menu-link"><span class="menu-text">Chartjs Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'peity']) }}" class="menu-link"><span class="menu-text">Peity Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'chartist']) }}" class="menu-link"><span class="menu-text">Chartist Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'c3']) }}" class="menu-link"><span class="menu-text">C3 Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'sparklines']) }}" class="menu-link"><span class="menu-text">Sparklines Charts</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['charts', 'knob']) }}" class="menu-link"><span class="menu-text">Jquery Knob Charts</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuMaps" id="topnav-map" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="map"></i></span>
                                <span class="menu-text"> Maps </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuMaps">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['maps', 'google']) }}" class="menu-link"><span class="menu-text">Google Maps</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['maps', 'vector']) }}" class="menu-link"><span class="menu-text">Vector Maps</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['maps', 'mapael']) }}" class="menu-link"><span class="menu-text">Mapael Maps</span></a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#menuExpages" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="package"></i></span>
                    <span class="menu-text"> Extra Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="menuExpages">
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a class="menu-link" href="#menuAuth" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="file-text"></i></span>
                                <span class="menu-text"> Auth Pages </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuAuth">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'login']) }}" class="menu-link"><span class="menu-text">Log In</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'login-2']) }}" class="menu-link"><span class="menu-text">Log In 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'register']) }}" class="menu-link"><span class="menu-text">Register</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'register-2']) }}" class="menu-link"><span class="menu-text">Register 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'signin-signup']) }}" class="menu-link"><span class="menu-text">Signin - Signup</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'signin-signup-2']) }}" class="menu-link"><span class="menu-text">Signin - Signup 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'recoverpw']) }}" class="menu-link"><span class="menu-text">Recover Password</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'recoverpw-2']) }}" class="menu-link"><span class="menu-text">Recover Password 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'lock-screen']) }}" class="menu-link"><span class="menu-text">Lock Screen</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'lock-screen-2']) }}" class="menu-link"><span class="menu-text">Lock Screen 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'logout']) }}" class="menu-link"><span class="menu-text">Logout</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'logout-2']) }}" class="menu-link"><span class="menu-text">Logout 2</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'confirm-mail']) }}" class="menu-link"><span class="menu-text">Confirm Mail</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['auth', 'confirm-mail-2']) }}" class="menu-link"><span class="menu-text">Confirm Mail 2</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuUtility" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="file-text"></i></span>
                                <span class="menu-text"> Utility </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuUtility">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'starter']) }}" class="menu-link"><span class="menu-text">Starter</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'timeline']) }}" class="menu-link"><span class="menu-text">Timeline</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'sitemap']) }}" class="menu-link"><span class="menu-text">Sitemap</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'invoice']) }}" class="menu-link"><span class="menu-text">Invoice</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'faqs']) }}" class="menu-link"><span class="menu-text">FAQs</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'search-results']) }}" class="menu-link"><span class="menu-text">Search Results</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'pricing']) }}" class="menu-link"><span class="menu-text">Pricing</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'maintenance']) }}" class="menu-link"><span class="menu-text">Maintenance</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'coming-soon']) }}" class="menu-link"><span class="menu-text">Coming Soon</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', 'gallery']) }}" class="menu-link"><span class="menu-text">Gallery</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" href="#menuErrorPage" data-bs-toggle="collapse">
                                <span class="menu-icon"><i data-feather="file-text"></i></span>
                                <span class="menu-text"> Error Page </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuErrorPage">
                                <ul class="sub-menu">
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', '404']) }}" class="menu-link"><span class="menu-text">Error 404</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', '404-two']) }}" class="menu-link"><span class="menu-text">Error 404 Two</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', '404-alt']) }}" class="menu-link"><span class="menu-text">Error 404-alt</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', '500']) }}" class="menu-link"><span class="menu-text">Error 500</span></a></li>
                                    <li class="menu-item"> <a href="{{ route('second', ['pages', '500-two']) }}" class="menu-link"><span class="menu-text">Error 500 Two</span></a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-item">
                            <a href="#menuMultilevel" data-bs-toggle="collapse" class="menu-link">
                                <span class="menu-icon"><i data-feather="share-2"></i></span>
                                <span class="menu-text"> Multi Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="menuMultilevel">
                                <ul class="sub-menu">
                                    <li class="menu-item">
                                        <a href="#menuMultilevel2" data-bs-toggle="collapse" class="menu-link">
                                            <span class="menu-text"> Second Level </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="menuMultilevel2">
                                            <ul class="sub-menu">
                                                <li class="menu-item">
                                                    <a href="javascript: void(0);" class="menu-link">
                                                        <span class="menu-text">Item 1</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="javascript: void(0);" class="menu-link">
                                                        <span class="menu-text">Item 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="menu-item">
                                        <a href="#menuMultilevel3" data-bs-toggle="collapse" class="menu-link">
                                            <span class="menu-text">Third Level</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="menuMultilevel3">
                                            <ul class="sub-menu">
                                                <li class="menu-item">
                                                    <a href="javascript: void(0);" class="menu-link">
                                                        <span class="menu-text">Item 1</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="#menuMultilevel4" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-text">Item 2</span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="menuMultilevel4">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="javascript: void(0);" class="menu-link">
                                                                    <span class="menu-text">Item 1</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="javascript: void(0);" class="menu-link">
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
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="#menuLayouts" data-bs-toggle="collapse">
                    <span class="menu-icon"><i data-feather="layout"></i></span>
                    <span class="menu-text"> Layouts </span>
                    <span class="badge bg-blue ms-auto">New</span>
                </a>
                <div class="collapse" id="menuLayouts">
                    <ul class="sub-menu">
                        <li class="menu-item"> <a href="{{ route('any', 'index') }}" class="menu-link"><span class="menu-text">Vertical</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['layouts-eg', 'horizontal']) }}" class="menu-link"><span class="menu-text">Horizontal</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['layouts-eg', 'detached']) }}" class="menu-link"><span class="menu-text">Detached</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['layouts-eg', 'two-column']) }}" class="menu-link"><span class="menu-text">Two Column Menu</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['layouts-eg', 'two-tone-icons']) }}" class="menu-link"><span class="menu-text">Two Tones Icons</span></a></li>
                        <li class="menu-item"> <a href="{{ route('second', ['layouts-eg', 'preloader']) }}" class="menu-link"><span class="menu-text">Preloader</span></a></li>
                    </ul>
                </div>
            </li>

        </ul> <!-- end navbar-->
    </div>
</div> <!-- end topnav-->
