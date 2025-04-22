

function fillInfusionTable(dataArray, infusionBodyId, addRowId) {
    const tbody = document.getElementById(infusionBodyId);
    const addRow = document.getElementById(addRowId);

    // Clear all but the add row
    tbody.innerHTML = '';
    tbody.appendChild(addRow);

    dataArray.forEach(infusion => displayInfusionRow(infusion, infusionBodyId, addRowId));
}

function displayInfusionRow(data, infusionBodyId, addRowId) {

    const tbody = document.getElementById(infusionBodyId);
    const addRow = document.getElementById(addRowId);

    const newRow = document.createElement('tr');
    newRow.className = "border-b border-black";

    newRow.innerHTML = `
        <td class="p-1 border-r border-black">
            <input type="date" class="w-full focus:outline-none focus:ring-0" value="${data.date || ''}" />
        </td>
        <td class="p-1 border-r border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0" value="${data.bottle_no || ''}" />
        </td>
        <td class="p-1 border-r border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0"  value="${data.ivf || ''}" />
        </td>
        <td class="p-1 border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0"  value="${data.rate || ''}" />
        </td>
    `;

    tbody.insertBefore(newRow, addRow);
}



