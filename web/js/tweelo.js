var tweelo = tweelo || {
        map: null
    };
$(document).ready(function () {
    getUserCurrentLocation();
    $('#search').on('click', searchCities);
    $(document).on('keypress', '#city', function(e) {
        if (e.which == 13) {
            $('#search').trigger('click');
        }
    });


});

function drawMap(lat, lng) {
    console.log("draw at: " + lat + " " + lng);
    tweelo.map = new GMaps({
        el: '#map',
        lat: lat,
        lng: lng,
        zoom: 13
    });

    tweelo.map.drawOverlay({
        lat: lat,
        lng: lng,
        content: '<div class="overlay">Bangkok</div>'
    });
}

function getUserCurrentLocation() {
    if (navigator.geolocation) {
        var geoOptions = {
            maximumAge: 5 * 60 * 1000
        };
        var geoSuccess = function (position) {
            drawMap(position.coords.latitude, position.coords.longitude);
        };
        var geoError = function (position) {
            console.log('Error occurred. Error code: ' + error.code);
            // error.code can be:
            //   0: unknown error
            //   1: permission denied
            //   2: position unavailable (error response from location provider)
            //   3: timed out
        };
        navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
    }
}

function searchCities(term) {
    var term = $('#city').val();
    $.getJSON('cities', {term: term}, function (cities) {
        if (cities.length == 1) {

            changePositionForCity(cities[0]);
        } else if (cities.length > 1) {
            var html =  '<div class="list-group" id="multiple">';
            $.each(cities, function(index, city){
                html += '<a href="#" onClick="return false;" class="list-group-item">' + city + '</a>';
            });

            html += '</div>';
            bootbox.dialog({
                title: "Multiple cities found. Please chose one",
                message: html,
                animate: true
            });
           $('#multiple a').on('click', selectCityFromList);
        }
    });
}

function changePositionForCity(city) {
    $('#city').val(city);
    $.getJSON('position', {city: city}, function (position) {
        drawMap(position.lat, position.lng);
    }).then(function(){
        $.getJSON('tweets', {lat:position.lat, lng:position.lng}, function(data){

        });
    });


}

function selectCityFromList(e) {
    e.preventDefault();
    //$('#modal').modal('hide');
    changePositionForCity($(this).text());
    bootbox.hideAll();
}
//jQuery(function () {
//    function getcitydetails(fqcn) {
//        if (typeof fqcn == "undefined") fqcn = jQuery("#f_elem_city").val();
//        var cityfqcn = fqcn;
//        if (cityfqcn) {
//            jQuery.getJSON(
//                "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
//                function (data) {
//                    setMapToLocation(parseFloat(data.geobyteslatitude), parseFloat(data.geobyteslongitude));
//                    console.log(data);
//                }
//            );
//        }
//    };
//
//    jQuery("#f_elem_city").autocomplete({
//        source: function (request, response) {
//            jQuery.getJSON(
//                "http://gd.geobytes.com/AutoCompleteCity?callback=?&q=" + request.term,
//                function (data) {
//                    response(data);
//                }
//            );
//        },
//        minLength: 3,
//        select: function (event, ui) {
//            var selectedObj = ui.item;
//            jQuery("#f_elem_city").val(selectedObj.value);
//            getcitydetails(selectedObj.value);
//            return false;
//        },
//        open: function () {
//            jQuery(this).removeClass("ui-corner-all").addClass("ui-corner-top");
//        },
//        close: function () {
//            jQuery(this).removeClass("ui-corner-top").addClass("ui-corner-all");
//        }
//    });
//    jQuery("#f_elem_city").autocomplete("option", "delay", 100);
//});