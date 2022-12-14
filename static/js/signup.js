/*!
 * Author:        Pierre-Henry Soria <hello@ph7builder.com>
 * Copyright:     (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * License:       MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 */


/**
 * Check username with Ajax.
 *
 * @return {Void}
 */
$('#username').keyup(function () {
    $('.your-username').hide();
    var sUsername = $('#username').val();

    $.post(pH7Url.base + 'user/asset/ajax/form/checkUsername', {'username': sUsername}, function (oData) {
        if (oData.status == 1) {
            $('.username').fadeIn();
            $('#username').css('border', 'solid #00cc00 1px');
            $('.username').css('color', "#149541");
        } else {
            $('.username').fadeIn();
            $('#username').css('border', 'solid #cc0000 1px');
            $('.username').css('color', '#F55');
        }
        $('.username').text(sUsername.substring(0, 60));
    }, 'json');
});
