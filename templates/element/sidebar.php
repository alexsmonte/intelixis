<aside class="left-sidebar with-horizontal">
    <!-- Sidebar scroll-->
    <div>
        <!-- Sidebar navigation-->
        <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
            <ul id="sidebarnav">
                <!-- ============================= -->
                <!-- Home -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <!-- =================== -->
                <!-- Dashboard -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <?= $this->Html->link('<iconify-icon icon="solar:home-linear" class="fs-6 aside-icon"></iconify-icon><span class="hide-menu ps-1">Dashboard</span>', ['controller'=>'Pages','action' => 'index'],['class'=>'sidebar-link primary-hover-bg','aria-expanded'=>'false', 'escape'=>false]) ?>
                </li>

                <!-- ============================= -->
                <!-- Apps -->
                <!-- ============================= -->
                <li class="sidebar-item">
                    <a class="sidebar-link two-column has-arrow indigo-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:archive-broken" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Apps</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="../horizontal/app-calendar.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Calendar</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-kanban.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Kanban</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-chat.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Chat</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../horizontal/app-email.html" aria-expanded="false">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Email</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-contact.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Contact Table</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-contact2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Contact List</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-notes.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Notes</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/app-invoice.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Invoice</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-user-profile.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">User Profile</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/blog-posts.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Posts</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/blog-detail.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Detail</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-shop.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Shop</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-shop-detail.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Shop Detail</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-product-list.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">List</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-checkout.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Checkout</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-add-product-list.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Add Product</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/eco-edit-product.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Edit Product</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Front Pages -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Front Pages</span>
                </li>
                <!-- =================== -->
                <!-- Front Pages -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:document-text-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Front Pages</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-landingpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Homepage</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-aboutpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">About Us</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-blogpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Blog</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-blogdetailpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Blog Details</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-contactpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Contact Us</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-portfoliopage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Portfolio</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/frontend-pricingpage.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Pricing</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- ============================= -->
                <!-- PAGES -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PAGES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link two-column has-arrow primary-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:file-text-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Pages</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <!-- Teachers -->
                        <li class="sidebar-item">
                            <a href="../horizontal/all-teacher.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">All Teachers</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/teacher-details.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Teachers Details</span>
                            </a>
                        </li>
                        <!-- Exams -->
                        <li class="sidebar-item">
                            <a href="../horizontal/exam-schedule.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Exam Schedule</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/exam-result.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Exam Result</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/exam-result-details.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Exam Result Details</span>
                            </a>
                        </li>
                        <!-- students -->
                        <li class="sidebar-item">
                            <a href="../horizontal/all-student.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">All Students</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/student-details.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Students Details</span>
                            </a>
                        </li>
                        <!-- classes -->
                        <li class="sidebar-item">
                            <a href="../horizontal/classes.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Classes</span>
                            </a>
                        </li>
                        <!-- attendance -->
                        <li class="sidebar-item">
                            <a href="../horizontal/attendance.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Attendance</span>
                            </a>
                        </li>
                        <!-- icons -->
                        <li class="sidebar-item">
                            <a href="../horizontal/icon-tabler.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Tabler Icon</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/icon-solar.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate"> Solar Icon</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-faq.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">FAQ</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-account-settings.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Account Setting</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-pricing.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Pricing</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-user-profile2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Profile One</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/page-user-profile.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Profile Two</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../landingpage/index.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Landing Page</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- ============================= -->
                <!-- UI -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">UI</span>
                </li>
                <!-- =================== -->
                <!-- UI Elements -->
                <!-- =================== -->
                <li class="sidebar-item mega-dropdown">
                    <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:cpu-bolt-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">UI</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="../horizontal/ui-accordian.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Accordian</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-badge.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Badge</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-buttons.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Buttons</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-dropdowns.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Dropdowns</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-modals.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Modals</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-tab.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Tab</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-tooltip-popover.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Tooltip & Popover</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-notification.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Alerts</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-progressbar.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Progressbar</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-pagination.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Pagination</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-typography.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Typography</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-bootstrap-ui.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Bootstrap UI</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-breadcrumb.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Breadcrumb</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-offcanvas.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Offcanvas</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-lists.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Lists</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-grid.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Grid</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-carousel.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Carousel</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-scrollspy.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Scrollspy</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/ui-spinner.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Spinner</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/ui-link.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Link</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- ============================= -->
                <!-- Forms -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Forms</span>
                </li>
                <!-- =================== -->
                <!-- Forms -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link two-column has-arrow success-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:book-2-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Forms</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <!-- form elements -->
                        <li class="sidebar-item">
                            <a href="../horizontal/form-inputs.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Forms Input</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-input-groups.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Input Groups</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-input-grid.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Input Grid</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-checkbox-radio.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Checkbox & Radios</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-bootstrap-switch.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Bootstrap Switch</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-select2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Select2</span>
                            </a>
                        </li>

                        <!-- form inputs -->
                        <li class="sidebar-item">
                            <a href="../horizontal/form-basic.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Basic Form</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-horizontal.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Form Horizontal</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-actions.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Form Actions</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-row-separator.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Row Separator</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-bordered.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Form Bordered</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/form-detail.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Form Detail</span>
                            </a>
                        </li>

                        <!-- form wizard -->
                        <li class="sidebar-item">
                            <a href="../horizontal/form-wizard.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Form Wizard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../horizontal/form-editor-quill.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Quill Editor</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- ============================= -->
                <!-- Tables -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Tables</span>
                </li>
                <!-- =================== -->
                <!-- Bootstrap Table -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:bedside-table-2-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Tables</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="../horizontal/table-basic.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Basic Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/table-dark-basic.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Dark Basic Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/table-sizing.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Sizing Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/table-layout-coloured.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Coloured Table Layout</span>
                            </a>
                        </li>
                        <!-- datatable -->
                        <li class="sidebar-item">
                            <a href="../horizontal/table-datatable-basic.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Basic Initialisation</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/table-datatable-api.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">API</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/table-datatable-advanced.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Advanced Initialisation</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Auth -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Auth</span>
                </li>
                <!-- =================== -->
                <!-- Auth -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow info-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:lock-keyhole-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Auth</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-error.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Error</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-login.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Side Login</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-login2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Boxed Login</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-register.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Side Register</span>
                            </a>
                        </li>
                        <!-- datatable -->
                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-register2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Boxed Register</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-forgot-password.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Side Forgot Password</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-forgot-password2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Boxed Forgot Password</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-two-steps.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Side Two Steps</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-two-steps2.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Boxed Two Steps</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../horizontal/authentication-maintenance.html" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu text-truncate">Maintenance</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Charts -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Charts</span>
                </li>

                <!-- multi level -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <iconify-icon icon="solar:layers-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                        <span class="hide-menu ps-1">Configurações</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="<?= $this->Url->build(['controller' => 'Assinaturas','action' => 'index'],['fullBase' => true]);?>" class="sidebar-link">
                                <i class="ti ti-circle"></i>
                                <span class="hide-menu">Assinatura</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow">
                                <i class="ti ti-circle"></i>
                                <span class="hide-menu">Extensões</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level">
                                <li class="sidebar-item">
                                    <a href="<?= $this->Url->build(['controller' => 'Extensoes','action' => 'index'],['fullBase' => true]);?>" class="sidebar-link">
                                        <i class="ti ti-circle"></i>
                                        <span class="hide-menu">Visualizar</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="<?= $this->Url->build(['controller' => 'Extensoes','action' => 'novo'],['fullBase' => true]);?>" class="sidebar-link">
                                        <i class="ti ti-circle"></i>
                                        <span class="hide-menu">Adicionar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow">
                                <i class="ti ti-circle"></i>
                                <span class="hide-menu">Planos</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level">
                                <li class="sidebar-item">
                                    <a href="<?= $this->Url->build(['controller' => 'Planos','action' => 'index'],['fullBase' => true]);?>" class="sidebar-link">
                                        <i class="ti ti-circle"></i>
                                        <span class="hide-menu">Visualizar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow">
                                <i class="ti ti-circle"></i>
                                <span class="hide-menu">Usuários</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level">
                                <li class="sidebar-item">
                                    <a href="<?= $this->Url->build(['controller' => 'Usuarios','action' => 'index'],['fullBase' => true]);?>" class="sidebar-link">
                                        <i class="ti ti-circle"></i>
                                        <span class="hide-menu">Visualizar</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="<?= $this->Url->build(['controller' => 'Usuarios','action' => 'novo'],['fullBase' => true]);?>" class="sidebar-link">
                                        <i class="ti ti-circle"></i>
                                        <span class="hide-menu">Adicionar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
    </div>
</aside>