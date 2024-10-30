jQuery(document).ready(function ($) {
    var selector = '.whoisxmlapicom-geoip-element';
    var element = $(selector);
    var tooltip = element.tooltipster({
        content: '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>',
        animation: 'fade',
        theme: 'tooltipster-light',
        contentAsHTML: true,
        interactive: true,
        functionBefore: function (instance, helper) {
            var $origin = $(helper.origin);
            var url = 'https://geoipify.whoisxmlapi.com/api/geo-short-info';
            if ($origin.data('loaded') === true)
                return;

            $.post(
                url,
                {
                    ip: helper.origin.textContent
                },
                function (data, status, request) {
                    instance.content(buildHtmlView(data, buildLink($origin.text()), $origin.data('target')));
                    $origin.data('loaded', true);
                }
            );
        }
    }).tooltipster('instance');
});


function buildHtmlView(data, url, target) {
    var result = '<div class="geoip-info-box">';
    result += '<p>IP: <strong>' + data.ip + '</strong></p>';
    result += '<p>Country: <strong>' + data.country + '</strong></p>';
    result += '<p>City: <strong>' + data.city + '</strong></p>';
    result += '<p><a href="' + url + '" target="' + target + '">Full report</a></p>';
    return result;
}

function buildLink(ip) {
    return 'https://geoipify.whoisxmlapi.com/geo/' + ip;
}