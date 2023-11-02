function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
    document.getElementById("mySidebar").style.left = "0";
    document.getElementById("menuButton").style.display = "none";
}

// JavaScript to close the sidebar
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("mySidebar").style.left = "-250px";
    document.getElementById("menuButton").style.display = "block";
}