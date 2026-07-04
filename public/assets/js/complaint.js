/**
 * Complaint Form Shared Logic
 */

function setType(type, element) {
    const input = document.getElementById("complaint_type");
    if (input) {
        input.value = type;
    }
    document.querySelectorAll(".btn-type").forEach(btn => btn.classList.remove("active"));
    if (element) {
        element.classList.add("active");
    }
}

function toggleAnonymous(isAnonymous) {
    const wrapper = document.getElementById("identity-fields-wrapper");
    const nameInput = document.getElementById("complainant_name");
    const phoneInput = document.getElementById("complainant_phone");
    const emailInput = document.getElementById("complainant_email");
    
    if (!wrapper || !nameInput || !phoneInput || !emailInput) return;

    if (isAnonymous) {
        wrapper.classList.add("hidden-fields");
        nameInput.value = ""; 
        phoneInput.value = ""; 
        emailInput.value = "";
        nameInput.disabled = true; 
        phoneInput.disabled = true; 
        emailInput.disabled = true;
    } else {
        wrapper.classList.remove("hidden-fields");
        nameInput.disabled = false; 
        phoneInput.disabled = false; 
        emailInput.disabled = false;
    }
}

function toggleMobileMenu() {
    const toggle = document.querySelector('.menu-toggle');
    const links = document.getElementById('navLinks');
    if (toggle && links) {
        toggle.classList.toggle('active');
        links.classList.toggle('active');
    }
}
