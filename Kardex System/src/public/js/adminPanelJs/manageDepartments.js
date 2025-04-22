function openDepartmentModal() {
    document.getElementById("departmentModal").classList.remove("hidden");
}

function closeDepartmentModal() {
    document.getElementById("departmentModal").classList.add("hidden");
}

async function viewAllDepartments() {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/departmentsBedCount", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const result = await response.json();

        if (!result.success || !Array.isArray(result.data)) {
            console.error("Failed to load department data:", result);
            alert("Could not load department list.");
            return;
        }

        const departments = result.data;
        const tableBody = document.getElementById("department-table-body");
        tableBody.innerHTML = ""; // Clear previous

        if (departments.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-4">No departments found.</td>
                </tr>
            `;
            return;
        }

        departments.forEach((department, index) => {
            const row = document.createElement("tr");
            row.classList.add("border-b", "border-gray-200");

            row.innerHTML = `
                <td class="px-2 py-4 text-center font-medium">${index + 1}</td>
                <td class="px-4 py-4 font-semibold">${department.deptname}</td>
                <td class="px-4 py-4 text-center">${department.bed_count || 0}</td>
                 <td class="px-4 py-4 text-center">
                    <button onclick="deleteDepartment(${department.department_id})"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Remove
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });

    } catch (err) {
        console.error("Error loading departments:", err);
        alert("Something went wrong.");
    }
}

async function deleteDepartment(department_id) {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/deleteDepartment", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ department_id })
        });

        const result = await response.json();

        if (!result.success) {
            console.error("Delete failed:", result);
            alert(result.error || "Failed to delete department.");
            return;
        }

        alert("Department deleted successfully.");
        viewAllDepartments(); // Refresh

    } catch (err) {
        console.error("Error deleting department:", err);
        alert("Something went wrong. Please try again.");
    }
}
