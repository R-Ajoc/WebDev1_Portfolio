const newPatientPopup = document.getElementById('newPatientPopup');
const addNewPatientBtn = document.getElementById('new-patient');
const closePatientForm = document.getElementById('close-patientForm');

const firstname = document.getElementById('first-name');
const midInit = document.getElementById('middle-initial');
const lastname = document.getElementById('last-name');
const bed = document.getElementById('bed');
const dateOfBirth = document.getElementById('dob');
const gender = document.getElementById('gender');
const civilStatus = document.getElementById('status');
const nationality = document.getElementById('nationality');
const religion = document.getElementById('religion');
const physician = document.getElementById('physician');
const diagnosis = document.getElementById('diagnosis');

const patientForm = document.getElementById('patientForm');

patientForm.addEventListener('submit', function(e) {
    e.preventDefault();
    submitNewPatientData();
});

function getPatientData() {
    return {
        lastname: lastname.value.trim(),
        firstname: firstname.value.trim(),
        midInit: midInit.value.trim(),
        bedNumber: bed.value.trim(),
        dob: dateOfBirth.value,
        gender: gender.value,
        civilStatus: civilStatus.value.trim(),
        nationality: nationality.value.trim(),
        religion: religion.value.trim(),
        physician: physician.value.trim(),
        diagnosis: diagnosis.value.trim()
    };
}

async function submitNewPatientData(){
    const patientData = getPatientData();

    try {
        const response = await fetch("/kardex_system/src/routes/index.php/addPatient", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(patientData)
        });

        const result = await response.json();
        console.log(result.errors);

        if (!result.success) {
            if (result.errors.emptyLastname) showInputError(lastname, result.errors.emptyLastname);
            if (result.errors.emptyFirstname) showInputError(firstname, result.errors.emptyFirstname);
            if (result.errors.emptyBed) showInputError(bed, result.errors.emptyBed);
            if (result.errors.emptyDiagnosis) showInputError(diagnosis, result.errors.emptyDiagnosis);
          
          return false;
        }
        // loadPatientFolders();
        closeNewPatientPopup();
        viewAllPatients();
        
        patientForm.reset();
        removeInputError(lastname);
        removeInputError(firstname);
        removeInputError(bed);
        removeInputError(diagnosis);
        
        return true;
      } catch (err) {
          console.log(err);
        alert("Something went wrong. Please try again");
        return false;
      }
}

async function loadAvailableBeds() {
    try {
        const response = await fetch("/kardex_system/src/routes/index.php/allBeds", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        });
        
        const result = await response.json(); 

        const beds = result.data;
        bed.innerHTML = ''; // Clear previous options

        const hasAvailableBeds = beds.some(b => b.status === 'Available');
        
        if (hasAvailableBeds) {
            // Add a default "Select a bed" option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select a bed';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            bed.appendChild(defaultOption);
        
            beds.forEach(b => {
                if (b.status === 'Available') {
                    const option = document.createElement('option');
                    option.value = b.bed_number; // or b.bed_uuid if you're using UUIDs
                    option.textContent = `Bed ${b.bed_number} - ${b.deptname}`;
                    bed.appendChild(option);
                }
            });
        } else {
            // If no beds are available, show message
            const noBedsOption = document.createElement('option');
            noBedsOption.value = '';
            noBedsOption.textContent = 'No beds available';
            noBedsOption.disabled = true;
            noBedsOption.selected = true;
            bed.appendChild(noBedsOption);
        }
        
    } catch (error) {
        console.error('Error loading beds:', error);
        bed.innerHTML = '<option value="">Failed to load beds</option>';
    }
}

function openNewPatientPopup() {
    newPatientPopup.classList.remove('hidden');
    loadAvailableBeds();
}

function closeNewPatientPopup() {
    newPatientPopup.classList.add('hidden');
}

addNewPatientBtn.addEventListener('click', () => openNewPatientPopup());
closePatientForm.addEventListener('click', () => closeNewPatientPopup());

