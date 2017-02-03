/*
 Name: 			Theme Base
 Written by: 	Okler Themes - (http://www.okler.net)
 Theme Version: 	1.5.1
 */

window.theme = {};

// Navigation
(function ($) {

    'use strict';

    var $items = $('.nav-main li.nav-parent');

    function expand($li) {
        $li.children('ul.nav-children').slideDown('fast', function () {
            $li.addClass('nav-expanded');
            $(this).css('display', '');
            ensureVisible($li);
        });
    }

    function collapse($li) {
        $li.children('ul.nav-children').slideUp('fast', function () {
            $(this).css('display', '');
            $li.removeClass('nav-expanded');
        });
    }

    function ensureVisible($li) {
        var scroller = $li.offsetParent();
        if (!scroller.get(0)) {
            return false;
        }

        var top = $li.position().top;
        if (top < 0) {
            scroller.animate({
                scrollTop: scroller.scrollTop() + top
            }, 'fast');
        }
    }

    $items.find('> a').on('click', function (ev) {

        var $anchor = $(this),
                $prev = $anchor.closest('ul.nav').find('> li.nav-expanded'),
                $next = $anchor.closest('li');

        if ($anchor.prop('href')) {
            var arrowWidth = parseInt(window.getComputedStyle($anchor.get(0), ':after').width, 10) || 0;
            if (ev.offsetX > $anchor.get(0).offsetWidth - arrowWidth) {
                //ev.preventDefault();
            }
        }

        if ($prev.get(0) !== $next.get(0)) {
            collapse($prev);
            expand($next);
        } else {
            collapse($prev);
        }
    });


}).apply(this, [jQuery]);

// Skeleton
(function (theme, $) {

    'use strict';

    theme = theme || {};

    var $body = $('body'),
            $html = $('html'),
            $window = $(window),
            isAndroid = navigator.userAgent.toLowerCase().indexOf('android') > -1;

    // mobile devices with fixed has a lot of issues when focus inputs and others...
    if (typeof $.browser !== 'undefined' && $.browser.mobile && $html.hasClass('fixed')) {
        $html.removeClass('fixed').addClass('scroll');
    }

    var Skeleton = {
        options: {
            sidebars: {
                menu: '#content-menu',
                left: '#sidebar-left',
                right: '#sidebar-right'
            }
        },
        customScroll: (!Modernizr.overflowscrolling && !isAndroid && $.fn.nanoScroller !== 'undefined'),
        initialize: function () {
            this
                    .setVars()
                    .build()
                    .events();
        },
        setVars: function () {
            this.sidebars = {};

            this.sidebars.left = {
                $el: $(this.options.sidebars.left)
            };

            this.sidebars.right = {
                $el: $(this.options.sidebars.right),
                isOpened: $html.hasClass('sidebar-right-opened')
            };

            this.sidebars.menu = {
                $el: $(this.options.sidebars.menu),
                isOpened: $html.hasClass('inner-menu-opened')
            };

            return this;
        },
        build: function () {

            if (typeof $.browser !== 'undefined' && $.browser.mobile) {
                $html.addClass('mobile-device');
            } else {
                $html.addClass('no-mobile-device');
            }

            $html.addClass('custom-scroll');
            if (this.customScroll) {
                this.buildSidebarLeft();
                this.buildContentMenu();
            }

            this.buildSidebarRight();

            return this;
        },
        events: function () {
            if (this.customScroll) {
                this.eventsSidebarLeft();
            }

            this.eventsSidebarRight();
            this.eventsContentMenu();

            if (typeof $.browser !== 'undefined' && !this.customScroll && isAndroid) {
                this.fixScroll();
            }

            return this;
        },
        fixScroll: function () {
            var _self = this;

            $window
                    .on('sidebar-left-opened sidebar-right-toggle', function (e, data) {
                        _self.preventBodyScrollToggle(data.added);
                    });
        },
        buildSidebarLeft: function () {
            this.sidebars.left.isOpened = !$html.hasClass('sidebar-left-collapsed') || $html.hasClass('sidebar-left-opened');

            this.sidebars.left.$nano = this.sidebars.left.$el.find('.nano');

            this.sidebars.left.$nano.nanoScroller({
                alwaysVisible: true,
                preventPageScrolling: $html.hasClass('fixed')
            });

            return this;
        },
        eventsSidebarLeft: function () {

            var _self = this,
                    $nano = this.sidebars.left.$nano;

            var open = function () {
                if (_self.sidebars.left.isOpened) {
                    return close();
                }

                _self.sidebars.left.isOpened = true;

                $html.addClass('sidebar-left-opened');

                $window.trigger('sidebar-left-toggle', {
                    added: true,
                    removed: false
                });

                $html.on('click.close-left-sidebar', function (e) {
                    //console.log(e);
                    //e.stopPropagation();
                    //close(e);
                });
            };

            var close = function (e) {
                if (!!e && !!e.target && ($(e.target).closest('.sidebar-left').get(0) || !$(e.target).closest('html').get(0))) {
                    e.preventDefault();
                    return false;
                } else {
                    $html.removeClass('sidebar-left-opened');
                    $html.off('click.close-left-sidebar');

                    $window.trigger('sidebar-left-toggle', {
                        added: false,
                        removed: true
                    });

                    _self.sidebars.left.isOpened = !$html.hasClass('sidebar-left-collapsed');

                }
            };

            var updateNanoScroll = function () {
                if ($.support.transition) {
                    $nano.nanoScroller();
                    $nano
                            .one('bsTransitionEnd', updateNanoScroll)
                            .emulateTransitionEnd(150)
                } else {
                    updateNanoScroll();
                }
            };

            var isToggler = function (element) {
                return $(element).data('fire-event') === 'sidebar-left-toggle' || $(element).parents().data('fire-event') === 'sidebar-left-toggle';
            };

            this.sidebars.left.$el
                    .on('click', function () {
                        updateNanoScroll();
                    })
                    .on('touchend', function (e) {
                        _self.sidebars.left.isOpened = !$html.hasClass('sidebar-left-collapsed') || $html.hasClass('sidebar-left-opened');
                        if (!_self.sidebars.left.isOpened && !isToggler(e.target)) {
                            console.log("aqui 1");
                            e.stopPropagation();
                            e.preventDefault();
                            open();
                        }
                    });

            $nano
                    .on('mouseenter', function () {
                        if ($html.hasClass('sidebar-left-collapsed')) {
                            $nano.nanoScroller();
                        }
                    })
                    .on('mouseleave', function () {
                        if ($html.hasClass('sidebar-left-collapsed')) {
                            $nano.nanoScroller();
                        }
                    });

            $window.on('sidebar-left-toggle', function (e, toggle) {
                if (toggle.removed) {
                    $html.removeClass('sidebar-left-opened');
                    $html.off('click.close-left-sidebar');
                }
            });

            return this;
        },
        buildSidebarRight: function () {
            this.sidebars.right.isOpened = $html.hasClass('sidebar-right-opened');

            if (this.customScroll) {
                this.sidebars.right.$nano = this.sidebars.right.$el.find('.nano');

                this.sidebars.right.$nano.nanoScroller({
                    alwaysVisible: true,
                    preventPageScrolling: true
                });
            }

            return this;
        },
        eventsSidebarRight: function () {
            var _self = this;

            var open = function () {
                if (_self.sidebars.right.isOpened) {
                    return close();
                }

                _self.sidebars.right.isOpened = true;

                $html.addClass('sidebar-right-opened');

                $window.trigger('sidebar-right-toggle', {
                    added: true,
                    removed: false
                });

                $html.on('click.close-right-sidebar', function (e) {
                    e.stopPropagation();
                    close(e);
                });
            };

            var close = function (e) {
                if (!!e && !!e.target && ($(e.target).closest('.sidebar-right').get(0) || !$(e.target).closest('html').get(0))) {
                    return false;
                }

                $html.removeClass('sidebar-right-opened');
                $html.off('click.close-right-sidebar');

                $window.trigger('sidebar-right-toggle', {
                    added: false,
                    removed: true
                });

                _self.sidebars.right.isOpened = false;
            };

            var bind = function () {
                $('[data-open="sidebar-right"]').on('click', function (e) {
                    var $el = $(this);
                    e.stopPropagation();

                    if ($el.is('a')) {
                        e.preventDefault();
                    }

                    open();
                });
            };

            this.sidebars.right.$el.find('.mobile-close')
                    .on('click', function (e) {
                        e.preventDefault();
                        $html.trigger('click.close-right-sidebar');
                    });

            bind();

            return this;
        },
        buildContentMenu: function () {
            if (!$html.hasClass('fixed')) {
                return false;
            }

            this.sidebars.menu.$nano = this.sidebars.menu.$el.find('.nano');

            this.sidebars.menu.$nano.nanoScroller({
                alwaysVisible: true,
                preventPageScrolling: true
            });

            return this;
        },
        eventsContentMenu: function () {
            var _self = this;

            var open = function () {
                if (_self.sidebars.menu.isOpened) {
                    return close();
                }

                _self.sidebars.menu.isOpened = true;

                $html.addClass('inner-menu-opened');

                $window.trigger('inner-menu-toggle', {
                    added: true,
                    removed: false
                });

                $html.on('click.close-inner-menu', function (e) {

                    close(e);
                });

            };

            var close = function (e) {
                var hasEvent,
                        hasTarget,
                        isCollapseButton,
                        isInsideModal,
                        isInsideInnerMenu,
                        isInsideHTML,
                        $target;

                hasEvent = !!e;
                hasTarget = hasEvent && !!e.target;

                if (hasTarget) {
                    $target = $(e.target);
                }

                isCollapseButton = hasTarget && !!$target.closest('.inner-menu-collapse').get(0);
                isInsideModal = hasTarget && !!$target.closest('.mfp-wrap').get(0);
                isInsideInnerMenu = hasTarget && !!$target.closest('.inner-menu').get(0);
                isInsideHTML = hasTarget && !!$target.closest('html').get(0);

                if ((!isCollapseButton && (isInsideInnerMenu || !isInsideHTML)) || isInsideModal) {
                    return false;
                }

                e.stopPropagation();

                $html.removeClass('inner-menu-opened');
                $html.off('click.close-inner-menu');

                $window.trigger('inner-menu-toggle', {
                    added: false,
                    removed: true
                });

                _self.sidebars.menu.isOpened = false;
            };

            var bind = function () {
                $('[data-open="inner-menu"]').on('click', function (e) {
                    var $el = $(this);
                    e.stopPropagation();

                    if ($el.is('a'))
                        e.preventDefault();

                    open();
                });
            };

            bind();

            /* Nano Scroll */
            if ($html.hasClass('fixed')) {
                var $nano = this.sidebars.menu.$nano;

                var updateNanoScroll = function () {
                    if ($.support.transition) {
                        $nano.nanoScroller();
                        $nano
                                .one('bsTransitionEnd', updateNanoScroll)
                                .emulateTransitionEnd(150)
                    } else {
                        updateNanoScroll();
                    }
                };

                this.sidebars.menu.$el
                        .on('click', function () {
                            updateNanoScroll();
                        });
            }

            return this;
        },
        preventBodyScrollToggle: function (shouldPrevent, $el) {
            setTimeout(function () {
                if (shouldPrevent) {
                    $body
                            .data('scrollTop', $body.get(0).scrollTop)
                            .css({
                                position: 'fixed',
                                top: $body.get(0).scrollTop * -1
                            })
                } else {
                    $body
                            .css({
                                position: '',
                                top: ''
                            })
                            .scrollTop($body.data('scrollTop'));
                }
            }, 150);
        }

    };

    // expose to scope
    $.extend(theme, {
        Skeleton: Skeleton
    });

}).apply(this, [window.theme, jQuery]);

// Base
(function (theme, $) {

    'use strict';

    theme = theme || {};

    theme.Skeleton.initialize();

}).apply(this, [window.theme, jQuery]);

/*
 Name: 			Theme Admin Extension
 Written by: 	Okler Themes - (http://www.okler.net)
 Theme Version: 	1.5.1
 */

window.theme = {};

// Mailbox
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__mailbox';

    var capitalizeString = function (str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    var Mailbox = function ($wrapper) {
        return this.initialize($wrapper);
    };

    Mailbox.prototype = {
        initialize: function ($wrapper) {
            if ($wrapper.data(instanceName)) {
                return this;
            }

            this.$wrapper = $wrapper;

            this
                    .setVars()
                    .setData()
                    .build()
                    .events();

            return this;
        },
        setVars: function () {
            this.view = capitalizeString(this.$wrapper.data('mailbox-view') || "");

            return this;
        },
        setData: function () {
            this.$wrapper.data(instanceName, this);

            return this;
        },
        build: function () {

            if (typeof this[ 'build' + this.view ] === 'function') {
                this[ 'build' + this.view ].call(this);
            }


            return this;
        },
        events: function () {
            if (typeof this[ 'events' + this.view ] === 'function') {
                this[ 'events' + this.view ].call(this);
            }

            return this;
        },
        buildFolder: function () {
            this.$wrapper.find('.mailbox-email-list .nano').nanoScroller({
                alwaysVisible: true,
                preventPageScrolling: true
            });
        },
        buildEmail: function () {
            this.buildComposer();
        },
        buildCompose: function () {
            this.buildComposer();
        },
        buildComposer: function () {
            this.$wrapper.find('#compose-field').summernote({
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen']],
                    ['help', ['help']]
                ]
            });
        },
        eventsCompose: function () {
            var $composer,
                    $contentBody,
                    $html,
                    $innerBody;

            $composer = $('.note-editable');
            $contentBody = $('.content-body');
            $html = $('html');
            $innerBody = $('.inner-body');

            var adjustComposeSize = function () {
                var composerHeight,
                        composerTop,
                        contentBodyPaddingBottom,
                        innerBodyHeight,
                        viewportHeight,
                        viewportWidth;


                contentBodyPaddingBottom = parseInt($contentBody.css('paddingBottom'), 10) || 0;
                viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                viewportWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

                $composer.css('height', '');

                if (viewportWidth < 767 || $html.hasClass('mobile-device')) {
                    composerTop = $composer.offset().top;
                    composerHeight = viewportHeight - composerTop;
                } else {
                    if ($html.hasClass('fixed')) {
                        composerTop = $composer.offset().top;
                    } else {
                        composerTop = $composer.position().top;
                    }
                    composerHeight = $innerBody.outerHeight() - composerTop;
                }

                composerHeight -= contentBodyPaddingBottom;

                $composer.css({
                    height: composerHeight
                });
            };

            var timer;
            $(window)
                    .on('resize orientationchange sidebar-left-toggle mailbox-recalc', function () {
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            adjustComposeSize();
                        }, 100);
                    });

            adjustComposeSize();
        }
    };

    // expose to scope
    $.extend(theme, {
        Mailbox: Mailbox
    });

    // jquery plugin
    $.fn.themeMailbox = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new Mailbox($this);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Lock Screen
(function ($) {

    'use strict';

    var LockScreen = {
        initialize: function () {
            this.$body = $('body');

            this
                    .build()
                    .events();
        },
        build: function () {
            var lockHTML,
                    userinfo;

            userinfo = this.getUserInfo();
            this.lockHTML = this.buildTemplate(userinfo);

            this.$lock = this.$body.children('#LockScreenInline');
            this.$userPicture = this.$lock.find('#LockUserPicture');
            this.$userName = this.$lock.find('#LockUserName');
            this.$userEmail = this.$lock.find('#LockUserEmail');

            return this;
        },
        events: function () {
            var _self = this;

            this.$body.find('[data-lock-screen="true"]').on('click', function (e) {
                e.preventDefault();

                _self.show();
            });

            return this;
        },
        formEvents: function ($form) {
            var _self = this;

            $form.on('submit', function (e) {
                e.preventDefault();

                _self.hide();
            });
        },
        show: function () {
            var _self = this,
                    userinfo = this.getUserInfo();

            this.$userPicture.attr('src', userinfo.picture);
            this.$userName.text(userinfo.username);
            this.$userEmail.text(userinfo.email);

            this.$body.addClass('show-lock-screen');

            $.magnificPopup.open({
                items: {
                    src: this.lockHTML,
                    type: 'inline'
                },
                modal: true,
                mainClass: 'mfp-lock-screen',
                callbacks: {
                    change: function () {
                        _self.formEvents(this.content.find('form'));
                    }
                }
            });
        },
        hide: function () {
            $.magnificPopup.close();
        },
        getUserInfo: function () {
            var $info,
                    picture,
                    name,
                    email;

            // always search in case something is changed through ajax
            $info = $('#userbox');
            picture = $info.find('.profile-picture img').attr('data-lock-picture');
            name = $info.find('.profile-info').attr('data-lock-name');
            email = $info.find('.profile-info').attr('data-lock-email');

            return {
                picture: picture,
                username: name,
                email: email
            };
        },
        buildTemplate: function (userinfo) {
            return [
                '<section id="LockScreenInline" class="body-sign body-locked body-locked-inline">',
                '<div class="center-sign">',
                '<div class="panel panel-sign">',
                '<div class="panel-body">',
                '<form>',
                '<div class="current-user text-center">',
                '<img id="LockUserPicture" src="{{picture}}" alt="John Doe" class="img-circle user-image" />',
                '<h2 id="LockUserName" class="user-name text-dark m-none">{{username}}</h2>',
                '<p  id="LockUserEmail" class="user-email m-none">{{email}}</p>',
                '</div>',
                '<div class="form-group mb-lg">',
                '<div class="input-group input-group-icon">',
                '<input id="pwd" name="pwd" type="password" class="form-control input-lg" placeholder="Password" />',
                '<span class="input-group-addon">',
                '<span class="icon icon-lg">',
                '<i class="fa fa-lock"></i>',
                '</span>',
                '</span>',
                '</div>',
                '</div>',
                '<div class="row">',
                '<div class="col-xs-6">',
                '<p class="mt-xs mb-none">',
                '<a href="#">Not John Doe?</a>',
                '</p>',
                '</div>',
                '<div class="col-xs-6 text-right">',
                '<button type="submit" class="btn btn-primary">Unlock</button>',
                '</div>',
                '</div>',
                '</form>',
                '</div>',
                '</div>',
                '</div>',
                '</section>'
            ]
                    .join('')
                    .replace(/\{\{picture\}\}/, userinfo.picture)
                    .replace(/\{\{username\}\}/, userinfo.username)
                    .replace(/\{\{email\}\}/, userinfo.email);
        }

    };

    this.LockScreen = LockScreen;

    $(function () {
        LockScreen.initialize();
    });

}).apply(this, [jQuery]);

// Panels
(function ($) {

    $(function () {
        $('.panel')
                .on('panel:toggle', function () {
                    var $this,
                            direction;

                    $this = $(this);
                    direction = $this.hasClass('panel-collapsed') ? 'Down' : 'Up';

                    $this.find('.panel-body, .panel-footer')[ 'slide' + direction ](200, function () {
                        $this[ (direction === 'Up' ? 'add' : 'remove') + 'Class' ]('panel-collapsed')
                    });
                })
                .on('panel:dismiss', function () {
                    var $this = $(this);

                    if (!!($this.parent('div').attr('class') || '').match(/col-(xs|sm|md|lg)/g) && $this.siblings().length === 0) {
                        $row = $this.closest('.row');
                        $this.parent('div').remove();
                        if ($row.children().length === 0) {
                            $row.remove();
                        }
                    } else {
                        $this.remove();
                    }
                })
                .on('click', '[data-panel-toggle], .panel-title', function (e) {
                    e.preventDefault();
                    $(this).closest('.panel').trigger('panel:toggle');
                })
                .on('click', '[data-panel-dismiss]', function (e) {
                    e.preventDefault();
                    $(this).closest('.panel').trigger('panel:dismiss');
                })
                /* Deprecated */
                .on('click', '.panel-actions a.fa-caret-up', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this
                            .removeClass('fa-caret-up')
                            .addClass('fa-caret-down');

                    $this.closest('.panel').trigger('panel:toggle');
                })
                .on('click', '.panel-actions a.fa-caret-down', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this
                            .removeClass('fa-caret-down')
                            .addClass('fa-caret-up');

                    $this.closest('.panel').trigger('panel:toggle');
                })
                .on('click', '.panel-actions a.fa-times', function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    $this.closest('.panel').trigger('panel:dismiss');
                });
    });

})(jQuery);

// Bootstrap Toggle
(function ($) {

    'use strict';

    var $window = $(window);

    var toggleClass = function ($el) {
        if (!!$el.data('toggleClassBinded')) {
            return false;
        }

        var $target,
                className,
                eventName;

        $target = $($el.attr('data-target'));
        className = $el.attr('data-toggle-class');
        eventName = $el.attr('data-fire-event');


        $el.on('click.toggleClass', function (e) {
            e.preventDefault();
            $target.toggleClass(className);

            var hasClass = $target.hasClass(className);

            if (!!eventName) {
                $window.trigger(eventName, {
                    added: hasClass,
                    removed: !hasClass
                });
            }
        });

        $el.data('toggleClassBinded', true);

        return true;
    };

    $(function () {
        $('[data-toggle-class][data-target]').each(function () {
            toggleClass($(this));
        });
    });

}).apply(this, [jQuery]);

// Form to Object
(function ($) {

    'use strict';

    $.fn.formToObject = function () {
        var arrayData,
                objectData;

        arrayData = this.serializeArray();
        objectData = {};

        $.each(arrayData, function () {
            var value;

            if (this.value != null) {
                value = this.value;
            } else {
                value = '';
            }

            if (objectData[this.name] != null) {
                if (!objectData[this.name].push) {
                    objectData[this.name] = [objectData[this.name]];
                }

                objectData[this.name].push(value);
            } else {
                objectData[this.name] = value;
            }
        });

        return objectData;
    };

})(jQuery);

// Loading Overlay
(function (theme, $) {

    'use strict';

    theme = theme || {};

    var loadingOverlayTemplate = [
        '<div class="loading-overlay">',
        '<div class="loader"></div>',
        '</div>'
    ].join('');

    var LoadingOverlay = function ($wrapper, options) {
        return this.initialize($wrapper, options);
    };

    LoadingOverlay.prototype = {
        options: {
            css: {}
        },
        initialize: function ($wrapper, options) {
            this.$wrapper = $wrapper;

            this
                    .setVars()
                    .setOptions(options)
                    .build()
                    .events();

            this.$wrapper.data('loadingOverlay', this);
        },
        setVars: function () {
            this.$overlay = this.$wrapper.find('.loading-overlay');

            return this;
        },
        setOptions: function (options) {
            if (!this.$overlay.get(0)) {
                this.matchProperties();
            }
            this.options = $.extend(true, {}, this.options, options);
            this.loaderClass = this.getLoaderClass(this.options.css.backgroundColor);

            return this;
        },
        build: function () {
            if (!this.$overlay.closest(document.documentElement).get(0)) {
                if (!this.$cachedOverlay) {
                    this.$overlay = $(loadingOverlayTemplate).clone();

                    if (this.options.css) {
                        this.$overlay.css(this.options.css);
                        this.$overlay.find('.loader').addClass(this.loaderClass);
                    }
                } else {
                    this.$overlay = this.$cachedOverlay.clone();
                }

                this.$wrapper.append(this.$overlay);
            }

            if (!this.$cachedOverlay) {
                this.$cachedOverlay = this.$overlay.clone();
            }

            return this;
        },
        events: function () {
            var _self = this;

            if (this.options.startShowing) {
                _self.show();
            }

            if (this.$wrapper.is('body') || this.options.hideOnWindowLoad) {
                $(window).on('load error', function () {
                    _self.hide();
                });
            }

            if (this.options.listenOn) {
                $(this.options.listenOn)
                        .on('loading-overlay:show beforeSend.ic', function (e) {
                            e.stopPropagation();
                            _self.show();
                        })
                        .on('loading-overlay:hide complete.ic', function (e) {
                            e.stopPropagation();
                            _self.hide();
                        });
            }

            this.$wrapper
                    .on('loading-overlay:show beforeSend.ic', function (e) {
                        if (e.target === _self.$wrapper.get(0)) {
                            e.stopPropagation();
                            _self.show();
                            return true;
                        }
                        return false;
                    })
                    .on('loading-overlay:hide complete.ic', function (e) {
                        if (e.target === _self.$wrapper.get(0)) {
                            e.stopPropagation();
                            _self.hide();
                            return true;
                        }
                        return false;
                    });

            return this;
        },
        show: function () {
            this.build();

            this.position = this.$wrapper.css('position').toLowerCase();
            if (this.position != 'relative' || this.position != 'absolute' || this.position != 'fixed') {
                this.$wrapper.css({
                    position: 'relative'
                });
            }
            this.$wrapper.addClass('loading-overlay-showing');
        },
        hide: function () {
            var _self = this;

            this.$wrapper.removeClass('loading-overlay-showing');
            setTimeout(function () {
                if (this.position != 'relative' || this.position != 'absolute' || this.position != 'fixed') {
                    _self.$wrapper.css({position: ''});
                }
            }, 500);
        },
        matchProperties: function () {
            var i,
                    l,
                    properties;

            properties = [
                'backgroundColor',
                'borderRadius'
            ];

            l = properties.length;

            for (i = 0; i < l; i++) {
                var obj = {};
                obj[ properties[ i ] ] = this.$wrapper.css(properties[ i ]);

                $.extend(this.options.css, obj);
            }
        },
        getLoaderClass: function (backgroundColor) {
            if (!backgroundColor || backgroundColor === 'transparent' || backgroundColor === 'inherit') {
                return 'black';
            }

            var hexColor,
                    r,
                    g,
                    b,
                    yiq;

            var colorToHex = function (color) {
                var hex,
                        rgb;

                if (color.indexOf('#') > -1) {
                    hex = color.replace('#', '');
                } else {
                    rgb = color.match(/\d+/g);
                    hex = ('0' + parseInt(rgb[0], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2);
                }

                if (hex.length === 3) {
                    hex = hex + hex;
                }

                return hex;
            };

            hexColor = colorToHex(backgroundColor);

            r = parseInt(hexColor.substr(0, 2), 16);
            g = parseInt(hexColor.substr(2, 2), 16);
            b = parseInt(hexColor.substr(4, 2), 16);
            yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

            return (yiq >= 128) ? 'black' : 'white';
        }

    };

    // expose to scope
    $.extend(theme, {
        LoadingOverlay: LoadingOverlay
    });

    // expose as a jquery plugin
    $.fn.loadingOverlay = function (opts) {
        return this.each(function () {
            var $this = $(this);

            var loadingOverlay = $this.data('loadingOverlay');
            if (loadingOverlay) {
                return loadingOverlay;
            } else {
                var options = opts || $this.data('loading-overlay-options') || {};
                return new LoadingOverlay($this, options);
            }
        });
    }

    // auto init
    $(function () {
        $('[data-loading-overlay]').loadingOverlay();
    });

}).apply(this, [window.theme, jQuery]);

// Scroll to Top
(function (theme, $) {

    theme = theme || {};

    $.extend(theme, {
        PluginScrollToTop: {
            defaults: {
                wrapper: $('body'),
                offset: 150,
                buttonClass: 'scroll-to-top',
                iconClass: 'fa fa-chevron-up',
                delay: 500,
                visibleMobile: false,
                label: false
            },
            initialize: function (opts) {
                initialized = true;

                this
                        .setOptions(opts)
                        .build()
                        .events();

                return this;
            },
            setOptions: function (opts) {
                this.options = $.extend(true, {}, this.defaults, opts);

                return this;
            },
            build: function () {
                var self = this,
                        $el;

                // Base HTML Markup
                $el = $('<a />')
                        .addClass(self.options.buttonClass)
                        .attr({
                            'href': '#',
                        })
                        .append(
                                $('<i />')
                                .addClass(self.options.iconClass)
                                );

                // Visible Mobile
                if (!self.options.visibleMobile) {
                    $el.addClass('hidden-mobile');
                }

                // Label
                if (self.options.label) {
                    $el.append(
                            $('<span />').html(self.options.label)
                            );
                }

                this.options.wrapper.append($el);

                this.$el = $el;

                return this;
            },
            events: function () {
                var self = this,
                        _isScrolling = false;

                // Click Element Action
                self.$el.on('click', function (e) {
                    e.preventDefault();
                    $('body, html').animate({
                        scrollTop: 0
                    }, self.options.delay);
                    return false;
                });

                // Show/Hide Button on Window Scroll event.
                $(window).scroll(function () {

                    if (!_isScrolling) {

                        _isScrolling = true;

                        if ($(window).scrollTop() > self.options.offset) {

                            self.$el.stop(true, true).addClass('visible');
                            _isScrolling = false;

                        } else {

                            self.$el.stop(true, true).removeClass('visible');
                            _isScrolling = false;

                        }

                    }

                });

                return this;
            }

        }

    });

}).apply(this, [window.theme, jQuery]);

// Codemirror
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__codemirror';

    var PluginCodeMirror = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginCodeMirror.defaults = {
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true,
        theme: 'monokai'
    };

    PluginCodeMirror.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginCodeMirror.defaults, opts);

            return this;
        },
        build: function () {
            CodeMirror.fromTextArea(this.$el.get(0), this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginCodeMirror: PluginCodeMirror
    });

    // jquery plugin
    $.fn.themePluginCodeMirror = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginCodeMirror($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Colorpicker
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__colorpicker';

    var PluginColorPicker = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginColorPicker.defaults = {
    };

    PluginColorPicker.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginColorPicker.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.colorpicker(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginColorPicker: PluginColorPicker
    });

    // jquery plugin
    $.fn.themePluginColorPicker = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginColorPicker($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Datepicker
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__datepicker';

    var PluginDatePicker = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginDatePicker.defaults = {
    };

    PluginDatePicker.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setVars()
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setVars: function () {
            this.skin = this.$el.data('plugin-skin');

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginDatePicker.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.datepicker(this.options);

            if (!!this.skin) {
                this.$el.data('datepicker').picker.addClass('datepicker-' + this.skin);
            }

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginDatePicker: PluginDatePicker
    });

    // jquery plugin
    $.fn.themePluginDatePicker = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginDatePicker($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// iosSwitcher
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__IOS7Switch';

    var PluginIOS7Switch = function ($el) {
        return this.initialize($el);
    };

    PluginIOS7Switch.prototype = {
        initialize: function ($el) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        build: function () {
            var switcher = new Switch(this.$el.get(0));

            $(switcher.el).on('click', function (e) {
                e.preventDefault();
                switcher.toggle();
            });

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginIOS7Switch: PluginIOS7Switch
    });

    // jquery plugin
    $.fn.themePluginIOS7Switch = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginIOS7Switch($this);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Markdown
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__markdownEditor';

    var PluginMarkdownEditor = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginMarkdownEditor.defaults = {
        iconlibrary: 'fa'
    };

    PluginMarkdownEditor.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginMarkdownEditor.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.markdown(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginMarkdownEditor: PluginMarkdownEditor
    });

    // jquery plugin
    $.fn.themePluginMarkdownEditor = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginMarkdownEditor($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Masked Input
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__maskedInput';

    var PluginMaskedInput = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginMaskedInput.defaults = {
    };

    PluginMaskedInput.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginMaskedInput.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.mask(this.$el.data('input-mask'), this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginMaskedInput: PluginMaskedInput
    });

    // jquery plugin
    $.fn.themePluginMaskedInput = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginMaskedInput($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// MaxLength
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__maxlength';

    var PluginMaxLength = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginMaxLength.defaults = {
        alwaysShow: true,
        placement: 'bottom-left',
        warningClass: 'label label-success bottom-left',
        limitReachedClass: 'label label-danger bottom-left'
    };

    PluginMaxLength.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginMaxLength.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.maxlength(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginMaxLength: PluginMaxLength
    });

    // jquery plugin
    $.fn.themePluginMaxLength = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginMaxLength($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// MultiSelect
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__multiselect';

    var PluginMultiSelect = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginMultiSelect.defaults = {
        templates: {
            filter: '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div>'
        }
    };

    PluginMultiSelect.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginMultiSelect.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.multiselect(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginMultiSelect: PluginMultiSelect
    });

    // jquery plugin
    $.fn.themePluginMultiSelect = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginMultiSelect($this, opts);
            }

        });
    };

}).apply(this, [window.theme, jQuery]);

// Spinner
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__spinner';

    var PluginSpinner = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginSpinner.defaults = {
    };

    PluginSpinner.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginSpinner.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.spinner(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginSpinner: PluginSpinner
    });

    // jquery plugin
    $.fn.themePluginSpinner = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginSpinner($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// SummerNote
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__summernote';

    var PluginSummerNote = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginSummerNote.defaults = {
        onfocus: function () {
            $(this).closest('.note-editor').addClass('active');
        },
        onblur: function () {
            $(this).closest('.note-editor').removeClass('active');
        }
    };

    PluginSummerNote.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginSummerNote.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.summernote(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginSummerNote: PluginSummerNote
    });

    // jquery plugin
    $.fn.themePluginSummerNote = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginSummerNote($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// TextArea AutoSize
(function (theme, $) {

    theme = theme || {};

    var initialized = false;
    var instanceName = '__textareaAutosize';

    var PluginTextAreaAutoSize = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginTextAreaAutoSize.defaults = {
    };

    PluginTextAreaAutoSize.prototype = {
        initialize: function ($el, opts) {
            if (initialized) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginTextAreaAutoSize.defaults, opts);

            return this;
        },
        build: function () {

            autosize($(this.$el));

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginTextAreaAutoSize: PluginTextAreaAutoSize
    });

    // jquery plugin
    $.fn.themePluginTextAreaAutoSize = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginTextAreaAutoSize($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// TimePicker
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__timepicker';

    var PluginTimePicker = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginTimePicker.defaults = {
        disableMousewheel: true
    };

    PluginTimePicker.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginTimePicker.defaults, opts);

            return this;
        },
        build: function () {
            this.$el.timepicker(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginTimePicker: PluginTimePicker
    });

    // jquery plugin
    $.fn.themePluginTimePicker = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginTimePicker($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Animate
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__animate';

    var PluginAnimate = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginAnimate.defaults = {
        accX: 0,
        accY: -150,
        delay: 1
    };

    PluginAnimate.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginAnimate.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            var self = this,
                    $el = this.options.wrapper,
                    delay = 0;

            $el.addClass('appear-animation');

            if (!$('html').hasClass('no-csstransitions') && $(window).width() > 767) {

                $el.appear(function () {

                    delay = ($el.attr('data-appear-animation-delay') ? $el.attr('data-appear-animation-delay') : self.options.delay);

                    if (delay > 1) {
                        $el.css('animation-delay', delay + 'ms');
                    }

                    $el.addClass($el.attr('data-appear-animation'));

                    setTimeout(function () {
                        $el.addClass('appear-animation-visible');
                    }, delay);

                }, {accX: self.options.accX, accY: self.options.accY});

            } else {

                $el.addClass('appear-animation-visible');

            }

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginAnimate: PluginAnimate
    });

    // jquery plugin
    $.fn.themePluginAnimate = function (opts) {
        return this.map(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginAnimate($this, opts);
            }

        });
    };

}).apply(this, [window.theme, jQuery]);

// Carousel
(function (theme, $) {

    theme = theme || {};

    var initialized = false;
    var instanceName = '__carousel';

    var PluginCarousel = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginCarousel.defaults = {
    };

    PluginCarousel.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginCarousel.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            this.options.wrapper.owlCarousel(this.options).addClass("owl-carousel-init");

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginCarousel: PluginCarousel
    });

    // jquery plugin
    $.fn.themePluginCarousel = function (opts) {
        return this.map(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginCarousel($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Chart Circular
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__chartCircular';

    var PluginChartCircular = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginChartCircular.defaults = {
        accX: 0,
        accY: -150,
        delay: 1,
        barColor: '#0088CC',
        trackColor: '#f2f2f2',
        scaleColor: false,
        scaleLength: 5,
        lineCap: 'round',
        lineWidth: 13,
        size: 175,
        rotate: 0,
        animate: ({
            duration: 2500,
            enabled: true
        })
    };

    PluginChartCircular.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginChartCircular.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            var self = this,
                    $el = this.options.wrapper,
                    value = ($el.attr('data-percent') ? $el.attr('data-percent') : 0),
                    percentEl = $el.find('.percent'),
                    shouldAnimate,
                    data;

            shouldAnimate = $.isFunction($.fn[ 'appear' ]) && (typeof $.browser !== 'undefined' && !$.browser.mobile);
            data = {accX: self.options.accX, accY: self.options.accY};

            $.extend(true, self.options, {
                onStep: function (from, to, currentValue) {
                    percentEl.html(parseInt(currentValue));
                }
            });

            $el.attr('data-percent', (shouldAnimate ? 0 : value));

            $el.easyPieChart(this.options);

            if (shouldAnimate) {
                $el.appear(function () {
                    setTimeout(function () {
                        $el.data('easyPieChart').update(value);
                        $el.attr('data-percent', value);

                    }, self.options.delay);
                }, data);
            } else {
                $el.data('easyPieChart').update(value);
                $el.attr('data-percent', value);
            }

            return this;
        }
    };

    // expose to scope
    $.extend(true, theme, {
        Chart: {
            PluginChartCircular: PluginChartCircular
        }
    });

    // jquery plugin
    $.fn.themePluginChartCircular = function (opts) {
        return this.map(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginChartCircular($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Lightbox
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__lightbox';

    var PluginLightbox = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginLightbox.defaults = {
        tClose: 'Close (Esc)', // Alt text on close button
        tLoading: 'Loading...', // Text that is displayed during loading. Can contain %curr% and %total% keys
        gallery: {
            tPrev: 'Previous (Left arrow key)', // Alt text on left arrow
            tNext: 'Next (Right arrow key)', // Alt text on right arrow
            tCounter: '%curr% of %total%' // Markup for "1 of 7" counter
        },
        image: {
            tError: '<a href="%url%">The image</a> could not be loaded.' // Error message when image could not be loaded
        },
        ajax: {
            tError: '<a href="%url%">The content</a> could not be loaded.' // Error message when ajax request failed
        }
    };

    PluginLightbox.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginLightbox.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            this.options.wrapper.magnificPopup(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginLightbox: PluginLightbox
    });

    // jquery plugin
    $.fn.themePluginLightbox = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginLightbox($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Portlets
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__portlet',
            storageOrderKey = '__portletOrder',
            storageStateKey = '__portletState';

    var PluginPortlet = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginPortlet.defaults = {
        connectWith: '[data-plugin-portlet]',
        items: '[data-portlet-item]',
        handle: '.portlet-handler',
        opacity: 0.7,
        placeholder: 'portlet-placeholder',
        cancel: 'portlet-cancel',
        forcePlaceholderSize: true,
        forceHelperSize: true,
        tolerance: 'pointer',
        helper: 'original',
        revert: 200
    };

    PluginPortlet.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            var _self = this;

            this.options = $.extend(true, {}, PluginPortlet.defaults, opts, {
                wrapper: this.$el,
                update: _self.onUpdate,
                create: _self.onLoad
            });

            return this;
        },
        onUpdate: function (event, ui) {
            var key = storageOrderKey,
                    data = store.get(key),
                    $this = $(this),
                    porletId = $this.prop('id');

            if (!data) {
                data = {};
            }

            if (!!porletId) {
                data[porletId] = $this.sortable('toArray');
                store.set(key, data);
            }
        },
        onLoad: function (event, ui) {
            var key = storageOrderKey,
                    data = store.get(key),
                    $this = $(this),
                    porletId = $this.prop('id'),
                    portlet = $('#' + porletId);

            if (!!data) {
                var panels = data[porletId];

                if (!!panels) {
                    $.each(panels, function (index, panelId) {
                        $('#' + panelId).appendTo(portlet);
                    });
                }
            }
        },
        saveState: function (panel) {
            var key = storageStateKey,
                    data = store.get(key),
                    panelId = panel.prop('id');

            if (!data) {
                data = {};
            }

            if (!panelId) {
                return this;
            }

            var collapse = panel.find('.panel-actions').children('a.fa-caret-up, a.fa-caret-down'),
                    isCollapsed = !!collapse.hasClass('fa-caret-up'),
                    isRemoved = !panel.closest('body').get(0);

            if (isRemoved) {
                data[panelId] = 'removed';
            } else if (isCollapsed) {
                data[panelId] = 'collapsed';
            } else {
                delete data[panelId];
            }

            store.set(key, data);
            return this;
        },
        loadState: function () {
            var key = storageStateKey,
                    data = store.get(key);

            if (!!data) {
                $.each(data, function (panelId, state) {
                    var panel = $('#' + panelId);
                    if (!panel.data('portlet-state-loaded')) {
                        if (state == 'collapsed') {
                            panel.find('.panel-actions a.fa-caret-down').trigger('click');
                        } else if (state == 'removed') {
                            panel.find('.panel-actions a.fa-times').trigger('click');
                        }
                        panel.data('portlet-state-loaded', true);
                    }
                });
            }

            return this;
        },
        build: function () {
            var _self = this;

            if ($.isFunction($.fn.sortable)) {
                this.$el.sortable(this.options);
                this.$el.find('[data-portlet-item]').each(function () {
                    _self.events($(this));
                });
            }

            var portlet = this.$el;
            portlet.css('min-height', 150);

            return this;
        },
        events: function ($el) {
            var _self = this,
                    portlet = $el.closest('[data-plugin-portlet]');

            this.loadState();

            $el.find('.panel-actions').on('click', 'a.fa-caret-up, a.fa-caret-down, a.fa-times', function (e) {
                setTimeout(function () {
                    _self.saveState($el);
                }, 250);
            });

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginPortlet: PluginPortlet
    });

    // jquery plugin
    $.fn.themePluginPortlet = function (opts) {
        return this.map(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginPortlet($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Scrollable
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__scrollable';

    var PluginScrollable = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginScrollable.updateModals = function () {
        PluginScrollable.updateBootstrapModal();
    };

    PluginScrollable.updateBootstrapModal = function () {
        var updateBoostrapModal;

        updateBoostrapModal = typeof $.fn.modal !== 'undefined';
        updateBoostrapModal = updateBoostrapModal && typeof $.fn.modal.Constructor !== 'undefined';
        updateBoostrapModal = updateBoostrapModal && typeof $.fn.modal.Constructor.prototype !== 'undefined';
        updateBoostrapModal = updateBoostrapModal && typeof $.fn.modal.Constructor.prototype.enforceFocus !== 'undefined';

        if (!updateBoostrapModal) {
            return false;
        }

        var originalFocus = $.fn.modal.Constructor.prototype.enforceFocus;
        $.fn.modal.Constructor.prototype.enforceFocus = function () {
            originalFocus.apply(this);

            var $scrollable = this.$element.find('.scrollable');
            if ($scrollable) {
                if ($.isFunction($.fn['themePluginScrollable'])) {
                    $scrollable.themePluginScrollable();
                }

                if ($.isFunction($.fn['nanoScroller'])) {
                    $scrollable.nanoScroller();
                }
            }
        };
    };

    PluginScrollable.defaults = {
        contentClass: 'scrollable-content',
        paneClass: 'scrollable-pane',
        sliderClass: 'scrollable-slider',
        alwaysVisible: true,
        preventPageScrolling: true
    };

    PluginScrollable.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginScrollable.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            this.options.wrapper.nanoScroller(this.options);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginScrollable: PluginScrollable
    });

    // jquery plugin
    $.fn.themePluginScrollable = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginScrollable($this, opts);
            }

        });
    };

    $(function () {
        PluginScrollable.updateModals();
    });

}).apply(this, [window.theme, jQuery]);

// Slider
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__slider';

    var PluginSlider = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginSlider.defaults = {
    };

    PluginSlider.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setVars()
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setVars: function () {
            var $output = $(this.$el.data('plugin-slider-output'));
            this.$output = $output.get(0) ? $output : null;

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            var _self = this;
            this.options = $.extend(true, {}, PluginSlider.defaults, opts);

            if (this.$output) {
                $.extend(this.options, {
                    slide: function (event, ui) {
                        _self.onSlide(event, ui);
                    }
                });
            }

            return this;
        },
        build: function () {
            this.$el.slider(this.options);

            return this;
        },
        onSlide: function (event, ui) {
            if (!ui.values) {
                this.$output.val(ui.value);
            } else {
                this.$output.val(ui.values[ 0 ] + '/' + ui.values[ 1 ]);
            }

            this.$output.trigger('change');
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginSlider: PluginSlider
    });

    // jquery plugin
    $.fn.themePluginSlider = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginSlider($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Toggle
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__toggle';

    var PluginToggle = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginToggle.defaults = {
        duration: 350,
        isAccordion: false,
        addIcons: true
    };

    PluginToggle.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginToggle.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            var self = this,
                    $wrapper = this.options.wrapper,
                    $items = $wrapper.find('.toggle'),
                    $el = null;

            $items.each(function () {
                $el = $(this);

                if (self.options.addIcons) {
                    $el.find('> label').prepend(
                            $('<i />').addClass('fa fa-plus'),
                            $('<i />').addClass('fa fa-minus')
                            );
                }

                if ($el.hasClass('active')) {
                    $el.find('> p').addClass('preview-active');
                    $el.find('> .toggle-content').slideDown(self.options.duration);
                }

                self.events($el);
            });

            if (self.options.isAccordion) {
                self.options.duration = self.options.duration / 2;
            }

            return this;
        },
        events: function ($el) {
            var self = this,
                    previewParCurrentHeight = 0,
                    previewParAnimateHeight = 0,
                    toggleContent = null;

            $el.find('> label').click(function (e) {

                var $this = $(this),
                        parentSection = $this.parent(),
                        parentWrapper = $this.parents('.toggle'),
                        previewPar = null,
                        closeElement = null;

                if (self.options.isAccordion && typeof (e.originalEvent) != 'undefined') {
                    closeElement = parentWrapper.find('.toggle.active > label');

                    if (closeElement[0] == $this[0]) {
                        return;
                    }
                }

                parentSection.toggleClass('active');

                // Preview Paragraph
                if (parentSection.find('> p').get(0)) {

                    previewPar = parentSection.find('> p');
                    previewParCurrentHeight = previewPar.css('height');
                    previewPar.css('height', 'auto');
                    previewParAnimateHeight = previewPar.css('height');
                    previewPar.css('height', previewParCurrentHeight);

                }

                // Content
                toggleContent = parentSection.find('> .toggle-content');

                if (parentSection.hasClass('active')) {

                    $(previewPar).animate({
                        height: previewParAnimateHeight
                    }, self.options.duration, function () {
                        $(this).addClass('preview-active');
                    });

                    toggleContent.slideDown(self.options.duration, function () {
                        if (closeElement) {
                            closeElement.trigger('click');
                        }
                    });

                } else {

                    $(previewPar).animate({
                        height: 0
                    }, self.options.duration, function () {
                        $(this).removeClass('preview-active');
                    });

                    toggleContent.slideUp(self.options.duration);

                }

            });
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginToggle: PluginToggle
    });

    // jquery plugin
    $.fn.themePluginToggle = function (opts) {
        return this.map(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginToggle($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);


// Widget - Toggle
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__widgetToggleExpand';

    var WidgetToggleExpand = function ($el, opts) {
        return this.initialize($el, opts);
    };

    WidgetToggleExpand.defaults = {
    };

    WidgetToggleExpand.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build()
                    .events();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, WidgetToggleExpand.defaults, opts);

            return this;
        },
        build: function () {
            return this;
        },
        events: function () {
            var _self = this,
                    $toggler = this.$el.find('.widget-toggle');

            $toggler.on('click.widget-toggler', function () {
                _self.$el.hasClass('widget-collapsed') ? _self.expand(_self.$el) : _self.collapse(_self.$el);
            });

            return this;
        },
        expand: function (content) {
            content.children('.widget-content-expanded').slideDown('fast', function () {
                $(this).css('display', '');
                content.removeClass('widget-collapsed');
            });
        },
        collapse: function (content) {
            content.children('.widget-content-expanded').slideUp('fast', function () {
                content.addClass('widget-collapsed');
                $(this).css('display', '');
            });
        }
    };

    // expose to scope
    $.extend(theme, {
        WidgetToggleExpand: WidgetToggleExpand
    });

    // jquery plugin
    $.fn.themePluginWidgetToggleExpand = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new WidgetToggleExpand($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Word Rotate
(function (theme, $) {

    theme = theme || {};

    var instanceName = '__wordRotate';

    var PluginWordRotate = function ($el, opts) {
        return this.initialize($el, opts);
    };

    PluginWordRotate.defaults = {
        delay: 2000
    };

    PluginWordRotate.prototype = {
        initialize: function ($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                    .setData()
                    .setOptions(opts)
                    .build();

            return this;
        },
        setData: function () {
            this.$el.data(instanceName, this);

            return this;
        },
        setOptions: function (opts) {
            this.options = $.extend(true, {}, PluginWordRotate.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },
        build: function () {
            var $el = this.options.wrapper,
                    itemsWrapper = $el.find(".word-rotate-items"),
                    items = itemsWrapper.find("> span"),
                    firstItem = items.eq(0),
                    firstItemClone = firstItem.clone(),
                    itemHeight = firstItem.height(),
                    currentItem = 1,
                    currentTop = 0;

            itemsWrapper.append(firstItemClone);

            $el
                    .height(itemHeight)
                    .addClass("active");

            setInterval(function () {

                currentTop = (currentItem * itemHeight);

                itemsWrapper.animate({
                    top: -(currentTop) + "px"
                }, 300, function () {

                    currentItem++;

                    if (currentItem > items.length) {

                        itemsWrapper.css("top", 0);
                        currentItem = 1;

                    }

                });

            }, this.options.delay);

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginWordRotate: PluginWordRotate
    });

    // jquery plugin
    $.fn.themePluginWordRotate = function (opts) {
        return this.each(function () {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginWordRotate($this, opts);
            }

        });
    }

}).apply(this, [window.theme, jQuery]);

// Notifications - Config
(function ($) {

    'use strict';

    // use font awesome icons if available
    if (typeof PNotify != 'undefined') {
        PNotify.prototype.options.styling = "fontawesome";

        $.extend(true, PNotify.prototype.options, {
            shadow: false,
            stack: {
                spacing1: 15,
                spacing2: 15
            }
        });

        $.extend(PNotify.styling.fontawesome, {
            // classes
            container: "notification",
            notice: "notification-warning",
            info: "notification-info",
            success: "notification-success",
            error: "notification-danger",
            // icons
            notice_icon: "fa fa-exclamation",
            info_icon: "fa fa-info",
            success_icon: "fa fa-check",
            error_icon: "fa fa-times"
        });
    }

}).apply(this, [jQuery]);