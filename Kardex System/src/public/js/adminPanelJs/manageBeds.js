function openBedModal() {
    document.getElementById('bedModal').classList.remove('hidden');
}

function closeBedModal() {
    document.getElementById('bedModal').classList.add('hidden');
    document.getElementById('bedForm').reset();
}

async function viewAllBeds() {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/allBeds", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const result = await response.json();
 
        if (!result.success || !Array.isArray(result.data)) {
            console.error("Failed to fetch  beds or data malformed:", result);
            alert("Failed to load bed data.");
            return false;
        }

        const beds = result.data;
        const tableBody = document.getElementById("bedTableBody");
        tableBody.innerHTML = ""; // Clear previous entries

        if (beds.length === 0) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No beds found.</td>
            `;
            tableBody.appendChild(row);
            return true;
        }
        beds.forEach(bed => {
            const row = document.createElement("tr");
            row.classList.add("border-b", "border-gray-200");
        
            row.innerHTML = `
                <td class="px-4 py-4 font-bold">${bed.bed_number}</td>
                <td class="px-4 py-4">${bed.deptname}</td>
                <td class="px-4 py-4 ${bed.status === 'Occupied' ? 'text-red-500 font-bold' : ''}">
                    ${bed.status}
                </td>
                <td class="px-4 py-4">
                    <button onclick="deleteBedById(${bed.bed_id})"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Remove
                    </button>
                </td>
            `;
        
            document.getElementById("bedTableBody").appendChild(row);
        });
        
        return true;

    } catch (err) {
        console.error("Error fetching beds:", err);
        alert("Something went wrong. Please try again.");
        return false;
    }
}


async function deleteBedById(bed_id) {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/deleteBed", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ bed_id })
        });

        const result = await response.json();

        if (!result.success) {
            alert(result.error || "Failed to delete bed.");
            return;
        }

        viewAllBeds(); // Refresh the list

    } catch (err) {
        console.error("Error deleting bed:", err);
        alert("Something went wrong. Please try again.");
    }
}

// Handle submit new bed
document.getElementById('bedForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const bedNumberInput = document.getElementById('bedNumberInput');
    const bedNumber = bedNumberInput.value.trim();
    
    const departmentSelect = document.getElementById('departmentSelect');
    const department = departmentSelect.value;
    
    const newBed = {
         bed_number: bedNumber, 
         department_id: department 
    }

    try {
        const response = await fetch("/kardex_system/src/routes/index.php/addBed", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(newBed)
        });
     
        const result = await response.json();
        
        if (!result.success){
            if (result.errors.emptyBed) showInputError(bedNumberInput, result.errors.emptyBed);
            if (result.errors.invalidBedNumber) showInputError(bedNumberInput, result.errors.invalidBedNumber);                
            if (result.errors.bedNumberTaken) showInputError(bedNumberInput, result.errors.bedNumberTaken);  
            if (result.errors.emptyDepartment) showInputError(departmentSelect, result.errors.emptyDepartment);
            return false;
        }

        closeBedModal();
        viewAllBeds();
     
    } catch (err) {
        console.error(err);
        alert("Something went wrong.");
    }
});
