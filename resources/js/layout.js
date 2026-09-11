/**
* Theme: Ubold - Responsive Bootstrap 5 Admin Dashboard
* Author: Coderthemes
* Module/App: Layout Js
*/

class ThemeCustomizer {

    constructor() {
        this.html = document.getElementsByTagName('html')[0]
        this.config = {};
        this.defaultConfig = window.config;
        this.mainNavScrollbar = document.getElementsByClassName('scrollbar')[0];
    }

    initConfig() {
        this.defaultConfig = JSON.parse(JSON.stringify(window.defaultConfig));
        this.config = JSON.parse(JSON.stringify(window.config));
        this.setSwitchFromConfig();
    }

    // Add Scrollbar
    initScrollBars() {
        this.initLeftSidebar();
        document.querySelector('.app-menu .scrollbar')?.setAttribute("data-simplebar", "");
        document.querySelector('.app-menu .scrollbar')?.classList.add('h-100');
    }

    // Left Sidebar Menu (Vertical Menu)
    initLeftSidebar() {
        var self = this;

        if ($(".menu").length) {
            var navCollapse = $('.menu li .collapse');
            var navToggle = $(".menu li [data-bs-toggle='collapse']");
            navToggle.on('click', function (e) {
                return false;
            });

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function (event) {
                    var parent = $(event.target).parents('.collapse.show');
                    $('.menu .collapse.show').not(event.target).not(parent).collapse('hide');
                }
            });

            // activate the menu in left side bar (Vertical Menu) based on url
            $(".menu a").each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");
                }
            });
        }

        // handling two columns menu if present
        var twoColSideNav = $("#two-col-sidenav-main");
        if (twoColSideNav.length) {

            var twoColSideNavItems = $("#two-col-sidenav-main .menu-link");
            var sideSubMenus = $(".twocolumn-menu-item");

            var nav = $('.twocolumn-menu-item .sub-menu');
            var navCollapse = $('#two-col-menu menu-item .collapse');

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function () {
                    var nearestNav = $(this).closest(nav).closest(nav).find(navCollapse);
                    if (nearestNav.length) nearestNav.not($(this)).collapse('hide'); else navCollapse.not($(this)).collapse('hide');
                }
            });

            twoColSideNavItems.on('click', function (e) {
                var target = $($(this).attr('href'));

                if (target.length) {
                    e.preventDefault();
                    twoColSideNavItems.removeClass('active');
                    $(this).addClass('active');
                    sideSubMenus.removeClass("d-block");
                    target.addClass("d-block");
                    if (window.innerWidth >= 1040) {
                        self.changeLeftbarSize("default");
                    }
                }
                return true;
            });

            // activate menu with no child
            var pageUrl = window.location.href; //.split(/[?#]/)[0];
            twoColSideNavItems.each(function () {
                if (this.href === pageUrl) {
                    $(this).addClass('active');
                }
            });


            // activate the menu in left side bar (Two column) based on url
            $("#two-col-menu a").each(function () {
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active"); // add active to li of the current link

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");

                    // opening menu
                    var matchingItem = null;
                    var targetEl = '#' + $(this).parents('.twocolumn-menu-item').attr("id");
                    $("#two-col-sidenav-main .menu-link").each(function () {
                        if ($(this).attr('href') === targetEl) {
                            matchingItem = $(this);
                        }
                    });
                    if (matchingItem) matchingItem.trigger('click');
                }
            });
        }


        setTimeout(function () {
            var activatedItem = document.querySelector('li.menuitem-active .active');
            if (activatedItem != null) {
                var simplebarContent = document.querySelector('.app-menu .simplebar-content-wrapper');
                var offset = activatedItem.offsetTop - 300;
                if (simplebarContent && offset > 100) {
                    scrollTo(simplebarContent, offset, 600);
                }
            }
        }, 200);

        // scrollTo (Left Side Bar Active Menu)
        function easeInOutQuad(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }
        function scrollTo(element, to, duration) {
            var start = element.scrollTop, change = to - start, currentTime = 0, increment = 20;
            var animateScroll = function () {
                currentTime += increment;
                var val = easeInOutQuad(currentTime, start, change, duration);
                element.scrollTop = val;
                if (currentTime < duration) {
                    setTimeout(animateScroll, increment);
                }
            };
            animateScroll();
        }
    }

    // Horizontal Layout Menu Setup
    initHorizontalLayout() {
        if (html.getAttribute("data-layout") === "horizontal" && window.innerWidth > 1040) {
            this.initLeftSidebar(true);
        }
    }

    reverseQuery(element, query) {
        while (element) {
            if (element.parentElement) {
                if (element.parentElement.querySelector(query) === element) return element
            }
            element = element.parentElement;
        }
        return null;
    }

    changeThemeMode(color) {
        this.config.theme = color;
        this.html.setAttribute('data-bs-theme', color);
        this.setSwitchFromConfig();
    }

    changeLayoutMode(mode) {
        this.html.setAttribute('data-layout-mode', mode);
        this.config.layout.mode = mode;
        this.setSwitchFromConfig();
    }

    changeLayoutWidth(width, save = true) {
        this.html.setAttribute('data-layout-width', width);
        if (save) {
            this.config.layout.width = width;
            this.setSwitchFromConfig();
        }
    }

    changeMenuIcon(icon, save = true) {
        this.html.setAttribute('data-menu-icon', icon);
        if (save) {
            this.config.menu.icon = icon;
            this.setSwitchFromConfig();
        }
    }

    changeMenuColor(color) {
        this.config.menu.color = color;
        this.html.setAttribute('data-menu-color', color);
        this.setSwitchFromConfig();
    }

    changeLeftbarSize(size, save = true) {
        this.html.setAttribute('data-sidenav-size', size);
        if (save) {
            this.config.sidenav.size = size;
            this.setSwitchFromConfig();
        }
    }

    changeTwocolumnColor(twocolumn) {
        this.html.setAttribute('data-two-column-color', twocolumn);
        this.config.sidenav.twocolumn = twocolumn;
        this.setSwitchFromConfig();
    }

    changeTopbarColor(color) {
        this.config.topbar.color = color;
        this.html.setAttribute('data-topbar-color', color);
        this.setSwitchFromConfig();
    }

    changeSidebarUser(showUser) {

        this.config.sidenav.user = showUser;
        if (showUser) {
            this.html.setAttribute('data-sidenav-user', showUser);
        } else {
            this.html.removeAttribute('data-sidenav-user');
        }
        this.setSwitchFromConfig();
    }

    resetTheme() {
        this.config = JSON.parse(JSON.stringify(window.defaultConfig));
        this.changeThemeMode(this.config.theme);
        this.changeLayoutMode(this.config.layout.mode);
        this.changeLayoutWidth(this.config.layout.width);
        this.changeMenuColor(this.config.menu.color);
        this.changeMenuIcon(this.config.menu.icon);
        this.changeTopbarColor(this.config.topbar.color);
        this.changeLeftbarSize(this.config.sidenav.size);
        this.changeTwocolumnColor(this.config.sidenav.twocolumn)
        this.adjustLayout();
    }

    initSwitchListener() {
        var self = this;

        document.querySelectorAll('input[name=data-layout]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeLayout(element.value);
            })
        });


        document.querySelectorAll('input[name=data-menu-color]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeMenuColor(element.value);
            })
        });

        document.querySelectorAll('input[name=data-sidenav-size]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeLeftbarSize(element.value);
            })
        });

        document.querySelectorAll('input[name=data-menu-icon]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeMenuIcon(element.value);
            })
        });

        document.querySelectorAll('input[name=data-bs-theme]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeThemeMode(element.value);
            })
        });

        document.querySelectorAll('input[name=data-layout-mode]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeLayoutMode(element.value);
            })
        });

        document.querySelectorAll('input[name=data-layout-width]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeLayoutWidth(element.value);
            })
        });

        document.querySelectorAll('input[name=data-topbar-color]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeTopbarColor(element.value);
            })
        });

        document.querySelectorAll('input[name=data-two-column-color]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeTwocolumnColor(element.value);
            })
        });

        document.querySelectorAll('input[name=data-sidebar-user]').forEach(function (element) {
            element.addEventListener('change', function (e) {
                self.changeSidebarUser(element.checked);
            })
        });


        //TopBar Light Dark
        var themeColorToggle = document.getElementById('light-dark-mode');
        if (themeColorToggle) {
            themeColorToggle.addEventListener('click', function (e) {

                if (self.config.theme === 'light') {
                    self.changeThemeMode('dark');
                } else {
                    self.changeThemeMode('light');
                }
            });
        }

        var resetBtn = document.querySelector('#reset-layout')
        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                self.resetTheme();
            });
        }

        var menuToggleBtn = document.querySelector('.button-toggle-menu');
        if (menuToggleBtn) {
            menuToggleBtn.addEventListener('click', function () {
                var configSize = self.config.sidenav.size;
                var size = self.html.getAttribute('data-sidenav-size', configSize);

                if (size === 'full') {
                    self.showBackdrop();
                } else {
                    if (configSize == 'fullscreen') {
                        if (size === 'fullscreen') {
                            self.changeLeftbarSize(configSize == 'fullscreen' ? 'default' : configSize, false);
                        } else {
                            self.changeLeftbarSize('fullscreen', false);
                        }
                    } else {
                        if (size === 'condensed') {
                            self.changeLeftbarSize(configSize == 'condensed' ? 'default' : configSize, false);
                        } else {
                            self.changeLeftbarSize('condensed', false);
                        }
                    }
                }

                // Todo: old implementation
                self.html.classList.toggle('sidebar-enable');

            });
        }

        var hoverBtn = document.querySelectorAll('.button-sm-hover');
        hoverBtn.forEach(function (element) {
            element.addEventListener('click', function () {
                var configSize = self.config.sidenav.size;
                var size = self.html.getAttribute('data-sidenav-size', configSize);

                if (size === 'sm-hover-active') {
                    self.changeLeftbarSize('sm-hover', false);
                } else {
                    self.changeLeftbarSize('sm-hover-active', false);
                }
            });
        })
    }

    showBackdrop() {
        const backdrop = document.createElement('div');
        backdrop.id = 'custom-backdrop';
        backdrop.classList = 'offcanvas-backdrop fade show';
        document.body.appendChild(backdrop);

        if (document.getElementsByTagName('html')[0]?.getAttribute('dir') != 'rtl') {
            document.body.style.overflow = "hidden";
            if (window.innerWidth > 1140) {
                document.body.style.paddingRight = "15px";
            }
        }
        const self = this
        backdrop.addEventListener('click', function (e) {
            self.html.classList.remove('sidebar-enable');
            self.hideBackdrop();
        })
    }

    hideBackdrop() {
        var backdrop = document.getElementById('custom-backdrop');
        if (backdrop) {
            document.body.removeChild(backdrop);
            document.body.style.overflow = null;
            document.body.style.paddingRight = null;
        }
    }

    initWindowSize() {
        var self = this;
        window.addEventListener('resize', function (e) {
            self.adjustLayout();
        })
    }

    adjustLayout() {
        var self = this;

        if (window.innerWidth <= 1140) {
            self.changeLeftbarSize('full', false);
        } else {
            self.changeLeftbarSize(self.config.sidenav.size);
        }
    }

    setSwitchFromConfig() {

        sessionStorage.setItem('__UBOLD_CONFIG__', JSON.stringify(this.config));
        // localStorage.setItem('__UBOLD_CONFIG__', JSON.stringify(this.config));

        document.querySelectorAll('.right-bar input[type=checkbox]').forEach(function (checkbox) {
            checkbox.checked = false;
        })
        var config = this.config;
        if (config) {
            var layoutColorSwitch = document.querySelector('input[type=checkbox][name=data-bs-theme][value=' + config.theme + ']');
            var layoutModeSwitch = document.querySelector('input[type=checkbox][name=data-layout-mode][value=' + config.layout.mode + ']');
            var layoutWidthSwitch = document.querySelector('input[type=checkbox][name=data-layout-width][value=' + config.layout.width + ']');
            var topbarColorSwitch = document.querySelector('input[type=checkbox][name=data-topbar-color][value=' + config.topbar.color + ']');
            var menuColorSwitch = document.querySelector('input[type=checkbox][name=data-menu-color][value=' + config.menu.color + ']');
            var menuIconSwitch = document.querySelector('input[type=checkbox][name=data-menu-icon][value=' + config.menu.icon + ']');
            var leftbarSizeSwitch = document.querySelector('input[type=checkbox][name=data-sidenav-size][value=' + config.sidenav.size + ']');
            var leftbarTwocolumnColorSwitch = document.querySelector('input[type=checkbox][name=data-two-column-color][value=' + config.sidenav.twocolumn + ']');
            var leftbarUserSwitch = document.querySelector('input[type=checkbox][name=data-sidebar-user]');

            if (layoutColorSwitch) layoutColorSwitch.checked = true;
            if (layoutModeSwitch) layoutModeSwitch.checked = true;
            if (layoutWidthSwitch) layoutWidthSwitch.checked = true;
            if (topbarColorSwitch) topbarColorSwitch.checked = true;
            if (menuColorSwitch) menuColorSwitch.checked = true;
            if (menuIconSwitch) menuIconSwitch.checked = true;
            if (leftbarSizeSwitch) leftbarSizeSwitch.checked = true;
            if (leftbarTwocolumnColorSwitch) leftbarTwocolumnColorSwitch.checked = true;
            if (leftbarUserSwitch && config.sidenav.user.toString() === "true") leftbarUserSwitch.checked = true;
        }
    }


    init() {
        this.initConfig();
        this.initScrollBars();
        this.initLeftSidebar();
        this.initSwitchListener();
        this.initWindowSize();
        this.adjustLayout();
        this.setSwitchFromConfig();
    }
}

new ThemeCustomizer().init();