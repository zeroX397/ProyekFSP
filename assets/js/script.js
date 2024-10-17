// DROPDOWN FUNCTION FOR ADMIN AND JOIN PROPOSAL

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function adminpageDropdown() {
    document.getElementById("dd-admin-page").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (e) {
    if (!e.target.matches('.dropbtn')) {
        var ddAdminPage = document.getElementById("dd-admin-page");
        var ddProposalPage = document.getElementById("proposalPage");
        if (ddAdminPage.classList.contains('show')) {
            ddAdminPage.classList.remove('show');
        }
        if (ddProposalPage.classList.contains('show')) {
            ddProposalPage.classList.remove('show');
        }
    }
}

function proposalDropdown() {
    document.getElementById("proposalPage").classList.toggle("show");
}


// DELETE CONFIRMATION WHEN DELETING DATA

function confirmDelete() {
    return confirm("Are you sure you want to delete this data?\nThis action cannot be undone!");
}
