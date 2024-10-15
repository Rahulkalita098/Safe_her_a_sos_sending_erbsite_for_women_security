document.getElementById('sosButton').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(sendSOS, showError);
    } else {
        document.getElementById('status').textContent = 'Geolocation is not supported by this browser.';
    }
});

function sendSOS(position) {
    const xhr = new XMLHttpRequest();
    const params = `latitude=${position.coords.latitude}&longitude=${position.coords.longitude}`;

    xhr.open('POST', 'sos.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        document.getElementById('status').textContent = this.responseText;
    };
    xhr.send(params);
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            document.getElementById('status').textContent = 'User denied the request for Geolocation.';
            break;
        case error.POSITION_UNAVAILABLE:
            document.getElementById('status').textContent = 'Location information is unavailable.';
            break;
        case error.TIMEOUT:
            document.getElementById('status').textContent = 'The request to get user location timed out.';
            break;
        case error.UNKNOWN_ERROR:
            document.getElementById('status').textContent = 'An unknown error occurred.';
            break;
    }
}
