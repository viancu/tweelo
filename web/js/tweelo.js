var tweelo = tweelo || {
        map: null,
        tweets: null
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
    }).then(function(position){
        $.getJSON('tweets', {lat:position.lat, lng:position.lng, city:city}, function(data){
            tweelo.tweets = data;
            $.each(data, function(index, tweet){
                console.log(tweet);
                tweelo.map.drawOverlay({
                    lat: tweet.lat,
                    lng: tweet.lng,
                    content: '<div><img src="'+ tweet.profile_image_url +'"/></div>'
                });
            });
        });
    });


}

function selectCityFromList(e) {
    e.preventDefault();
    //$('#modal').modal('hide');
    changePositionForCity($(this).text());
    bootbox.hideAll();
}