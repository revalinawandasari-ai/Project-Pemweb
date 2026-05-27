// Generate options for day, month, and year once
function populateDateOptions() {
    const daySelects = document.querySelectorAll('.day-select');
    const monthSelects = document.querySelectorAll('.month-select');
    const yearSelects = document.querySelectorAll('.year-select');
    
    // Populate days (01-31)
    for (let i = 1; i <= 31; i++) {
        const val = String(i).padStart(2, '0'); // ← "01", "02", dst
        daySelects.forEach(select => {
            select.innerHTML += `<option value="${val}">${val}</option>`;
        });
    }

    // Populate months (01-12)
    for (let i = 1; i <= 12; i++) {
        const val = String(i).padStart(2, '0'); // ← "01", "02", dst
        monthSelects.forEach(select => {
            select.innerHTML += `<option value="${val}">${val}</option>`;
        });
    }

    // Populate years
    const currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= currentYear - 100; i--) {
        yearSelects.forEach(select => {
            select.innerHTML += `<option value="${i}">${i}</option>`;
        });
    }
}

populateDateOptions();