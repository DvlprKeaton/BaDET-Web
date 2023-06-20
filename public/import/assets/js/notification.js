window.Echo.channel('cases.created')
    .listen('CasesCreated', (event) => {
        // Play notification sound
        playNotificationSound();
        // Display popup message
        displayNotificationPopup('New Case Created', 'A new case has been created: ' + event.case.case_name);
    });

function playNotificationSound() {
    // Add code to play notification sound here
}

function displayNotificationPopup(title, message) {
    // Create a new div element to hold the notification popup
    var popupDiv = document.createElement('div');

    // Add the title and message to the div element
    var popupTitle = document.createElement('h3');
    popupTitle.innerHTML = title;
    popupDiv.appendChild(popupTitle);

    var popupMessage = document.createElement('p');
    popupMessage.innerHTML = message;
    popupDiv.appendChild(popupMessage);

    // Add styles to the popup div to make it look like a popup message
    popupDiv.style.position = 'fixed';
    popupDiv.style.top = '50px';
    popupDiv.style.left = '50%';
    popupDiv.style.transform = 'translateX(-50%)';
    popupDiv.style.backgroundColor = '#fff';
    popupDiv.style.padding = '20px';
    popupDiv.style.borderRadius = '5px';
    popupDiv.style.boxShadow = '0px 2px 10px rgba(0, 0, 0, 0.3)';
    popupDiv.style.zIndex = '9999';

    // Add the popup div to the document body
    document.body.appendChild(popupDiv);

    // Set a timeout to remove the popup after a certain amount of time (e.g. 5 seconds)
    setTimeout(function() {
        popupDiv.parentNode.removeChild(popupDiv);
    }, 5000);
}
