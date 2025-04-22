function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.remove('hidden');
    sidebar.classList.toggle('-translate-x-full');
    sidebar.classList.toggle('translate-x-0');
    overlay.classList.toggle('hidden');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.add('-translate-x-full');
    sidebar.classList.remove('translate-x-0');
    overlay.classList.add('hidden');
}

  // Helper to get query parameter from URL
  function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }
  function calculateAge(dateInput) {
    const birthDate = new Date(dateInput);
    const today = new Date();
    
    let age = today.getFullYear() - birthDate.getFullYear();
    
    const m = today.getMonth() - birthDate.getMonth();
    const d = today.getDate() - birthDate.getDate();
    
    // Adjust if birthday hasn't occurred yet this year
    if (m < 0 || (m === 0 && d < 0)) {
        age--;
    }

    return age;
}

  
  // Example fetch call (adjust endpoint to your actual route)
  async function fetchPatientDetails() {
    const id = getQueryParam('id');
    if (!id) {
      alert("No patient ID provided!");
      return;
    }
    console.log(id);
    try {
        const response = await fetch(`/kardex_system/src/routes/index.php/viewPatient/${id}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        });
      const result = await response.json();
      const patient = result.patient;

      console.log(patient);
      // Populate sidebar
      document.getElementById('displayName').innerHTML = `${patient.lastname},<br>${patient.firstname}`;

      // Replace the content section instead of appending to the entire <aside>
      const aside = document.querySelector('aside');
      

      let date_of_birth;

      if (patient.date_of_birth && patient.date_of_birth !== '0000-00-00') {
        const { date: dobDate } = formatDateTime(patient.date_of_birth)
        date_of_birth = dobDate;
      } else date_of_birth = 'Not provided'

      const { date: admissionDate, time: admissionTime } = formatDateTime(patient.created_at);

        // Fill inputs Edit Profile Inforation Form
        document.getElementById("editBedNumber").value = patient.bed_number || 'No bed assigned';
        document.getElementById("editGender").value = patient.gender || '';
        document.getElementById("editStatus").value = patient.status || '';
        document.getElementById("editDOB").value = date_of_birth || '';
        document.getElementById("editNationality").value = patient.nationality || 'Unknown';
        document.getElementById("editReligion").value = patient.religion || 'Unknown';
        document.getElementById("editPhysician").value = patient.physician || 'Not assigned';
        document.getElementById("editDiagnosis").value = patient.diagnosis || 'Not provided';          

      const detailsDiv = aside.querySelector('.mt-4');
      detailsDiv.innerHTML = `
        <p class="mb-2"><strong>BED #:</strong> ${patient.bed_number ? `${patient.bed_number} (${patient.deptname})` : 'No bed assigned'}</p>
        <p class="mb-2"><strong>Age:</strong> ${calculateAge(date_of_birth) || 'Not provided'}</p>
        <p class="mb-2"><strong>Sex:</strong> ${patient.gender || 'Not specified'}</p>
        <p class="mb-2"><strong>Status:</strong> ${patient.status || 'Not provided'}</p>
        <p class="mb-2"><strong>Date of Birth:</strong> ${date_of_birth }</p>
        <p class="mb-2"><strong>Nationality:</strong> ${patient.nationality || 'Not provided'}</p>
        <p class="mb-2"><strong>Religion:</strong> ${patient.religion || 'Not provided'}</p>
        <p class="mb-2"><strong>Date of Admission:</strong> ${admissionDate || 'Not provided'}</p>
        <p class="mb-2"><strong>Time of Admission:</strong> ${admissionTime || 'Not provided'}</p>
        <p class="mb-2"><strong>Physician:</strong> ${patient.physician || 'Not assigned'}</p>
        <p class="mb-2"><strong>Diagnosis:</strong> ${patient.diagnosis || 'Not provided'}</p>
      `;
       
      
      // You can also populate treatments, referrals, etc. if your API returns those
    } catch (error) {
      console.error("Failed to load patient data", error);
    }
  }

  fetchPatientDetails();

