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

    $('a').each(function(index, elem) {
        const $elem = $(elem);
        const url = $elem.attr('href');
        if (!url || (!url.startsWith("/files/") && !url.startsWith("/downloads/"))) {
            return true;
        }

        $( '<div class="sha256-label">sha256</div>' ).click(function (e) {
            e.stopPropagation();
            let sha256Url = '';
            const $label = $(e.target);
            const downloadSha256 = function($elem, url) {
                $.get(url, function(data) {
                    const $popup = $( '<div class="sha256-popup"></div>' ).html("<p class='sha256-popup-p'>" + data + "</p>").click(function(e) {
                        e.stopPropagation();
                    });

                    $label.append($popup);
                });
            };
            let $existingPopup = $label.find('.sha256-popup');
            if ($existingPopup.length) {
                $existingPopup.show();
                return;
            }
            if (url.startsWith("/files/")) {
                sha256Url = url + ".sha256";
                downloadSha256($elem, sha256Url);
            } else if (url.startsWith("/downloads/")) {
                const downloadUrl = url + ".txt";
                $.get(downloadUrl, function(data) {
                    if (data.startsWith("/files/")) {
                        downloadSha256($elem, data+ ".sha256");
                    }
                });
            }

        }).insertAfter($elem);
    });

    $(window).click(function(e) {
        $('.sha256-popup').hide();
    });


});
