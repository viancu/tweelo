var tweelo = tweelo || {
        map: null
    };

function historyAdd(city) {
    var history = Cookies.getJSON('history');

    var pos = $.inArray(city, history);
    if (pos !== -1) {
        history.splice(pos, 1);
    } else {
        if (history.length == 10) {
            history.pop();
        }
    }
    history.unshift(city);
    Cookies.set('history', history, {expires: 7});
    $('#city').val('');
}

function showHistory() {
    var cities = Cookies.getJSON('history');
    showCityList(cities, "Last 10 cities");
}

function historyInit() {
    if (!Cookies.get('history')) {
        Cookies.set('history', [], {expires: 7});
    }
}

if (!String.linkify) {
    String.prototype.linkify = function () {
        var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;
        var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
        var userPattern = /(^|\s)@(\w+)/gim;
        var hashTagPattern = /(^|\s)#(\w+)/gim;

        return this
            .replace(urlPattern, '<a href="$&" target="_blank">$&</a>')
            .replace(pseudoUrlPattern, '$1<a href="http://$2" target="_blank">$2</a>')
            .replace(userPattern, '<a href="http://www.twitter.com/$2" target="_blank">$&</a>')
            .replace(hashTagPattern, '<a href="http://www.twitter.com/hashtag/$2?src=hash" target="_blank">$&</a>')
    };
}

$(document).ready(function () {
    historyInit();
    getUserCurrentLocation();
    $('#search').on('click', searchCities);
    $('#history').on('click', showHistory);
    $(document).on('keypress', '#city', function (e) {
        if (e.which == 13) {
            $('#search').trigger('click');
        }
    });
});

function drawMap(lat, lng) {
    tweelo.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: {lat: lat, lng: lng}
    });
    console.log("draw at: " + lat + " " + lng);
}

function selectCityFromList(e) {
    e.preventDefault();
    changePositionForCity($(this).text());
    bootbox.hideAll();
}

function createCityTitle(city) {
    var cityData = city.split(",");
    var div = document.createElement('DIV');
    div.className = "city-title";
    div.innerHTML = '<h3>TWEETS ABOUT ' + cityData[0].toUpperCase() + '</h3>';
    return div;
}

function getUserCurrentLocation() {
    if (navigator.geolocation) {
        var geoOptions = {
            maximumAge: 5 * 60 * 1000
        };
        var geoSuccess = function (position) {
            drawMap(position.coords.latitude, position.coords.longitude);
        };
        var geoError = function (error) {
            console.log('Error occurred. Error code: ' + error.code);
        };
        navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
    }
}

function showCityList(cities, title) {
    var html = '<div class="list-group" id="multiple">';
    $.each(cities, function (index, city) {
        html += '<a href="#" onClick="return false;" class="list-group-item">' + city + '</a>';
    });
    html += '</div>';
    bootbox.dialog({
        title: title,
        message: html,
        animate: true
    });
    $('#multiple a').on('click', selectCityFromList);
}

function searchCities(term) {
    var term = $('#city').val();
    $.getJSON('cities', {term: term}, function (response) {
        if (!response.error) {
            var cities = response.data;
            if (cities.length == 1) {
                changePositionForCity(cities[0]);
            } else if (cities.length > 1) {
                showCityList(cities, "What city would you like to see tweets about?");
            }
        } else {
            bootbox.alert(response.message);
        }
    });
}

function changePositionForCity(city) {
    //$('#city').val(city);
    $.getJSON('position', {city: city}, function (response) {
        var position = false;
        if (!response.error) {
            position = response.data;
            drawMap(position.lat, position.lng);
        } else {
            bootbox.alert(response.message);
        }
    }).then(function (response) {
        if (!response.error) {
            historyAdd(city);
            var position = response.data;
            $.getJSON('tweets', {lat: position.lat, lng: position.lng, city: city}, function (response) {
                if (!response.error) {
                    var data = response.data;
                    var title = createCityTitle(city);
                    tweelo.map.controls[google.maps.ControlPosition.TOP_CENTER].push(title);
                    var infowindow = new google.maps.InfoWindow({maxWidth: 200});

                    $.each(data, function (index, tweet) {
                        var marker = new google.maps.Marker({
                            position: {lat: tweet.lat, lng: tweet.lng},
                            map: tweelo.map,
                            icon: {
                                url: tweet.profile_image_url,
                                size: new google.maps.Size(48, 48)
                            }
                        });

                        google.maps.event.addListener(marker, 'click', function (e) {
                            infowindow.setContent(tweet.text.linkify() + '<br/>' + tweet.created_at);
                            infowindow.open(tweelo.map, this);
                        });
                    });
                } else {
                    bootbox.alert(response.message);
                }
            });
        }
    });
}





