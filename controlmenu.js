// Function to show/hide dropdown content
function toggleDropdown(id) {
  var dropdown = document.getElementById(id);
  dropdown.classList.toggle("show");
}

// Click handler for 'Access' action
function handleAccess(filename) {
  alert("Accessing " + filename);
  // Implement the access logic here
}

// Click handler for 'Information' action
function handleInformation(filename) {
  alert("Fetching info for " + filename);
  // Implement the information logic here
}

// Click handler for 'Document Version' action
function handleDocumentVersion(filename) {
  alert("Fetching document version for " + filename);
  // Implement the version logic here
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}