var docCookies = {
    getItem: function (sKey) {
        return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
    },
    setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
        if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
        var sExpires = "";
        if (vEnd) {
            switch (vEnd.constructor) {
                case Number:
                    sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                    break;
                case String:
                    sExpires = "; expires=" + vEnd;
                    break;
                case Date:
                    sExpires = "; expires=" + vEnd.toUTCString();
                    break;
            }
        }
        document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
        return true;
    },
    removeItem: function (sKey, sPath, sDomain) {
        if (!sKey || !this.hasItem(sKey)) { return false; }
        document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + ( sDomain ? "; domain=" + sDomain : "") + ( sPath ? "; path=" + sPath : "");
        return true;
    },
    hasItem: function (sKey) {
        return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
    },
    keys: /* optional method: you can safely remove it! */ function () {
        var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
        for (var nIdx = 0; nIdx < aKeys.length; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
        return aKeys;
    }
};

function eraseCookie(name) {
    createCookie(name,"",-1);
}

document.getElementById("lang_ru").onclick = function() {
    docCookies.setItem("lang", "ru", Infinity,'/');
};

document.getElementById("lang_en").onclick = function() {
    docCookies.setItem("lang", "en", Infinity,'/');
};

$(document).on('copy', function(e) {
    if (navigator.userAgent.indexOf("Firefox")==-1) {
        var nonCopyable = $('.nonCopyable:not(.empty)');
        nonCopyable.each(function(index,el) {
            var $el = $(el);
            if ( $el.hasClass('empty') )  {
                return;
            }
            var width = $(el).width();
            var height = $(el).height();
            $el.data('content', $el.html()).css({"width" : width+'px', "height": height+1 + 'px'} ).html('');
        }).addClass('empty');
        var content = $('.nonCopyable').html();
        setTimeout(function() {
            nonCopyable.each(function(index,el) {
                $(el).html($(el).data('content')).removeClass('empty')./*.css({"width":'auto', height:'auto'}).*/data('content',null);
            }); });
    }
} );

$(function() {
    var formSubmitted = false;
    var commentForm = $('#commentForm');
    if (commentForm.length) {
        commentForm.submit(function(e){
            formSubmitted = true;
        });
        $(window).bind('beforeunload', function(){
            if(!formSubmitted && commentForm.find('[name="text"]').val()!='') {
                return 'Are you sure you want to leave?';
            }
        });
    }
    if ($('#galleria').length) {
        // Load the classic theme
        Galleria.loadTheme('/js/galleria/themes/classic/galleria.classic.min.js?v=1');

        Galleria.configure({
            autoplay: true
        });
        // Initialize Galleria
        Galleria.run('#galleria');
    }

    /*function checkAd() {
        var abner = $('.adsbygoogle');
        if (abner.length == 0 || abner.is(':hidden')) {
            $('.abner-under').hide();
            $('#sidebar').removeClass('abner'); //remove padding
        }
    }
    checkAd();
    setTimeout(checkAd, 1000);
    //}, 100);*/

    $(".open-panel").click(function(){

        $("html").toggleClass("openNav");

    });

    $(".close-panel, #content").click(function(){

        $("html").removeClass("openNav");

    });

    const closeAllPopups = function() {
        $('.sha256').removeClass('sha256_open');
    };
    const fixPopupPosition = function ($popup) {
        /*const rect = $popup[0].getBoundingClientRect();
        console.log(rect, window.innerWidth );
        const docWidth = window.innerWidth;
        if (rect.right > docWidth) {
            $popup.offset({left: docWidth - rect.width});
        }*/
        if ($popup.offset().left < 0) {
            $popup.offset({left: 0})
        }
    }
    const showPopup = function($parent, data, hashFileUrl = '', error = false) {
        const $existingPopup = $parent.find(".sha256__popup");
        let $popup = null;
        if (!$existingPopup.length) {
            $popup = $( '<div class="sha256__popup"><p class="sha256__paragraph"></p></div>').click(function(e) {
                e.stopPropagation();
            });
            $parent.append($popup);
        } else {
            $popup = $existingPopup;

        }
        if (!error && hashFileUrl) {
            data += '<a class="sha256__hash-file-link" href="' + hashFileUrl + '" target="_blank">Get hash file</a>';
        }
        $popup.find('.sha256__paragraph').html(data);
        $parent.addClass('sha256_open');
        fixPopupPosition($popup);
        $parent.toggleClass('sha256_error', error);
    }

    $('a').each(function(index, elem) {
        const $elem = $(elem);
        const url = $elem.attr('href');
        const filesDirectory = "/files/";
        const downloadsDirectory = "/downloads/";
        if (!url || (!url.startsWith(filesDirectory) && !url.startsWith(downloadsDirectory))) {
            return true;
        }

        if ($elem.next().hasClass('sha256')) {
            return true;
        }

        $( '<div class="sha256"></div>' ).append($('<span class="sha256__label">sha256</span>').click(function (e) {
            e.stopPropagation();

            const $container = $(e.target).closest('.sha256');

            const ajaxErrorFunction = function(jqXHR, textStatus, errorThrown) {
                showPopup($container, "An AJAX error occured: " + textStatus +
                    "<br>Error: " + errorThrown + "<br>Status: " + jqXHR.status, true);
            };

            const downloadSha256 = function($elem, url) {
                $.get(url, function(data) {
                    showPopup($container, data, url);
                }).error(ajaxErrorFunction);
            };

            let $existingPopup = $container.find('.sha256__popup');
            const isVisible =  $existingPopup.length  && $container.hasClass('sha256_open');
            closeAllPopups();

            if ($existingPopup.length && !$container.hasClass('sha256_error')) {
                if (!isVisible) {
                    fixPopupPosition($existingPopup);
                    $container.addClass('sha256_open');
                }
                return;
            }

            if ($elem.data('hash')) {
                showPopup($container, $elem.data('hash'), $elem.data('hash-file'));
                return;
            }
            if (url.startsWith(filesDirectory)) {
                let sha256Url = url + ".sha256";
                downloadSha256($elem, sha256Url);
            } else if (url.startsWith(downloadsDirectory)) {
                const downloadUrl = url + ".txt";
                $.get(downloadUrl, function(data) {
                    if (data.startsWith(filesDirectory)) {
                        downloadSha256($elem, data + ".sha256");
                    }
                }).error(ajaxErrorFunction);
            }

        })).insertAfter($elem);
    });

    $(window).click(function(e) {
       closeAllPopups();
    });

    $('.builds-hamburger__item-caption').click(function() {
        $this = $(this);
        $container = $(this).closest('.builds-hamburger__item');
        if (!$container.hasClass('builds-hamburger__item_open')) {
            const isFirstLevel = $this.hasClass('builds-hamburger__item-caption_first-level');
            console.log("isFirstLevel", isFirstLevel)
            if (isFirstLevel) {
                $siblings = $container.siblings('.builds-hamburger__item');
                $siblings.removeClass('builds-hamburger__item_open');
            }
            $container.addClass('builds-hamburger__item_open');
            $this.next().slideDown();
            if (isFirstLevel) {
                $siblings.children('.builds-hamburger__item-container').slideUp();
            }
        } else {
            $container.removeClass('builds-hamburger__item_open');
            //$container.children('.builds-hamburger__item-container').slideDown();
            $this.next().slideUp();
        }
    });

});
