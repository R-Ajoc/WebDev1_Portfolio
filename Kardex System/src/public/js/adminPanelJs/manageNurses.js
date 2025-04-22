function openNurseModal() {
    document.getElementById("nurseModal").classList.remove("hidden");
}

function closeNurseModal() {
    document.getElementById("nurseModal").classList.add("hidden");
}


async function viewAllNurses() {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/users", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const result = await response.json();
        // const result = await response.json();
   
        if (!result.success || !Array.isArray(result.data)) {
            console.error("Failed to fetch nurses or data malformed:", result);
            alert("Failed to load nurse data.");
            return false;
        }

        const nurses = result.data;
        const tableBody = document.getElementById("nurseTableBody");
        tableBody.innerHTML = ""; // Clear previous entries

        if (nurses.length === 0) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No nurses found.</td>
            `;
            tableBody.appendChild(row);
            return true;
        }

        nurses.forEach((nurse, index) => {
            const row = document.createElement("tr");
            row.classList.add("border-b", "border-gray-200");
        
            row.innerHTML = `
                <td class="px-2 py-4 text-center font-medium">${index + 1}</td>
                <td class="px-4 py-4 font-bold">${nurse.fullname}</td>
                <td class="px-4 py-4">${nurse.email}</td>
                <td class="px-4 py-4">${nurse.department || '—'}</td>
                <td class="px-4 py-4">${nurse.contact || '—'}</td>
                <td class="px-4 py-4">${nurse.created_at || '—'}</td>
                <td class="px-4 py-4">
                    <button onclick="deleteNurse(${nurse.id})"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Delete
                    </button>
                </td>
            `;
            document.getElementById("nurseTableBody").appendChild(row);
        });
        
        
        

        return true;

    } catch (err) {
        console.error("Error fetching nurses:", err);
        alert("Something went wrong. Please try again.");
        return false;
    }
}

async function deleteNurse(user_id) {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/deleteUser", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ user_id })
        });

        const result = await response.json();

        if (!result.success) {
            console.error("Delete failed:", result);
            alert(result.error || "Failed to delete nurse.");
            return;
        }

        alert("Nurse deleted successfully.");
        viewAllNurses(); // Refresh the list

    } catch (err) {
        console.error("Error deleting nurse:", err);
        alert("Something went wrong. Please try again.");
    }
}


