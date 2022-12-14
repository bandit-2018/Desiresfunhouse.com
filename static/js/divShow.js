/*
 * Author:        Pierre-Henry Soria <hello@ph7builder.com>
 * Copyright:     (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * License:       MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 */

function pH7DivShow(sDiv) {
    if ($(sDiv).css('display') == 'none')
        $(sDiv).fadeIn(500).attr('style', 'display:block !important;visibility:visible !important');
    else
        $(sDiv).fadeOut(500).attr('style', 'display:none !important;visibility:none !important');
}

$('.divShow a').click(function () {
    $(this).attr('href', pH7DivShow($(this).attr('href')));
});
