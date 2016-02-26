
var map;
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 45, lng: 150.644},
        zoom: 8
    });
};

function setMapToLocation(lat, long) {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: lat, lng: long},
        zoom: 12
    });
}
jQuery(function () {
    function getcitydetails(fqcn) {
        if (typeof fqcn == "undefined") fqcn = jQuery("#f_elem_city").val();
        var cityfqcn = fqcn;
        if (cityfqcn) {
            jQuery.getJSON(
                "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
                function (data) {
                    setMapToLocation(parseFloat(data.geobyteslatitude), parseFloat(data.geobyteslongitude));
                    console.log(data);
                }
            );
        }
    };

    jQuery("#f_elem_city").autocomplete({
        source: function (request, response) {
            jQuery.getJSON(
                "http://gd.geobytes.com/AutoCompleteCity?callback=?&q=" + request.term,
                function (data) {
                    response(data);
                }
            );
        },
        minLength: 3,
        select: function (event, ui) {
            var selectedObj = ui.item;
            jQuery("#f_elem_city").val(selectedObj.value);
            getcitydetails(selectedObj.value);
            return false;
        },
        open: function () {
            jQuery(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            jQuery(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
    jQuery("#f_elem_city").autocomplete("option", "delay", 100);
});