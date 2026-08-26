function checkSession(redirectPage) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/sessionCheck.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var isLoggedIn = xhr.responseText === 'true';
            
            if (!isLoggedIn) {
                window.location.href = "Login.php?redirect=" + encodeURIComponent(redirectPage);
            } else {
                window.location.href = redirectPage;
            }
        }
    };
    xhr.send();
    return false;
}

function toggleDropdown(event) {
    event.stopPropagation();
    var dropdown = document.getElementById("profile-dropdown");
    
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
}

window.onclick = function(event) {
    var dropdown = document.getElementById("profile-dropdown");
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    }
};


