async function viewAllPatients() {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/allPatients", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const result = await response.json();
 
        if (!result.success || !Array.isArray(result.data)) {
            console.error("Failed to fetch  patients or data malformed:", result);
            alert("Failed to load patient data.");
            return false;
        }

        const patients = result.data;
        const patientBody = document.getElementById("patientBody");
        patientBody.innerHTML = ""; // Clear previous entries

        if (patients.length === 0) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No patients found.</td>
            `;
            patientBody.appendChild(row);
            return true;
        }
        patients.forEach((patient, index) => {
            const row = document.createElement("tr");
            row.classList.add("border-b", "border-gray-200"); // 👈 Add this line
        
            row.innerHTML = `
                <td class="px-2 py-4 text-center font-medium">${index + 1}</td>
                <td class="px-4 py-4 font-bold">${patient.fullname}</td>
                <td class="px-4 py-4">${patient.gender}</td>
                <td class="px-4 py-4">${patient.physician}</td>
                <td class="px-4 py-4">${patient.bed_number ? `BED ${patient.bed_number}` : `None`}</td>
                <td class="px-4 py-4">
                    ${new Date(patient.created_at).toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    })}
                </td>
                <td class="px-4 py-4 flex items-center gap-2">
                    <button onclick="openEditProfileModal(${patient.id})"
                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                        Edit
                    </button>
                    <button onclick="deletePatientById(${patient.id})"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Remove
                    </button>
                </td>
            `;
            document.getElementById('patientBody').appendChild(row);
        });
        
        return true;

    } catch (err) {
        console.error("Error fetching patients:", err);
        alert("Something went wrong. Please try again.");
        return false;
    }
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

function saveEditedProfile() {
    // Optional: Add logic to gather and save the edited data
    
    closeEditProfileModal();
}

function openEditProfileModal(patient) {
    // Show modal
    console.log(patient);
    document.getElementById('editProfileModal').classList.remove('hidden');

    // Fill the form with the patient object
    document.getElementById('editBedNumber').value = patient.bed_number || '';
    document.getElementById('editGender').value = patient.gender || '';
    document.getElementById('editStatus').value = patient.status || '';
    document.getElementById('editDOB').value = patient.date_of_birth || '';
    document.getElementById('editNationality').value = patient.nationality || '';
    document.getElementById('editReligion').value = patient.religion || '';
    document.getElementById('editPhysician').value = patient.physician || '';
    document.getElementById('editDiagnosis').value = patient.diagnosis || '';

    // Save ID globally for later
    window.currentEditPatientId = patient.id;
}


async function deletePatientById(patient_id) {
    try {
        const response = await fetch(`/kardex_system/src/routes/index.php/deletePatient`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ patient_id })
        });

        const result = await response.json();

        if (!result.success) {
            alert(result.error || "Failed to delete patient.");
            return;
        }

        viewAllPatients(); // Refresh the list

    } catch (err) {
        console.error("Error deleting patient:", err);
        alert("Something went wrong. Please try again.");
    }
}

