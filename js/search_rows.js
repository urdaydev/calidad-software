const searchCategoryInput = document.querySelector('#search-rows');
searchCategoryInput.addEventListener('keyup', (e) => {
    const value = e.target.value;
    const tbody = document.querySelector('#tbody-rows');
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        const columns = row.querySelectorAll('td');
        let found = false;
        columns.forEach(column => {
            if (column.textContent.toLowerCase().includes(value.toLowerCase())) {
                found = true;
            }
        });
        if (found) {
            row.classList.remove('d-none');
        } else {
            row.classList.add('d-none');
        }
    });
});