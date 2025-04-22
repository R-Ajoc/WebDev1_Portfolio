document.getElementById('saveBtn').addEventListener('click', () => {
    document.getElementById("popupConfirmChanges").classList.remove("hidden");
    document.getElementById("popupConfirmChanges").classList.add("flex");
});

document.getElementById('saveChanges').addEventListener('click', () => {
    showPasscodeUI('Enter Passcode', "confirmPasscode");
});
document.getElementById('cancelChanges').addEventListener('click', () => {
    document.getElementById("popupConfirmChanges").classList.add("hidden");
});

function getCurrentDetails(elementId) {
    console.log(elementId);
    const container = document.getElementById(elementId);
    const inputs = container.querySelectorAll('input');
    const values = Array.from(inputs)
    .map(input => input.value?.trim())
    .filter(value => value && value !== "");
   
    console.log(values);
    return values;
}



function getEditedProfileDetails() {
    return {
     // IT DOESNT MATCH THE MODEL SINCE NO NAME, ETC
      bedNumber: document.getElementById("editBedNumber").value.trim(),
      gender: document.getElementById("editGender").value,
      status: document.getElementById("editStatus").value,
      date_of_birth: document.getElementById("editDOB").value,
      nationality: document.getElementById("editNationality").value.trim(),
      religion: document.getElementById("editReligion").value.trim(),
      physician: document.getElementById("editPhysician").value.trim(),
      diagnosis: document.getElementById("editDiagnosis").value.trim()
    };
  }
  
function getInfusionData(infusionBody, rowId) {
    const tbody = document.getElementById(infusionBody);
    const rows = Array.from(tbody.querySelectorAll('tr'));

    const infusionData = [];

    rows.forEach(row => {
        // Skip the last "Add Row"
        if (row.id === rowId) return;

        const inputs = row.querySelectorAll('input');

        infusionData.push({
            date: inputs[0]?.value || '',
            bottle_no: inputs[1]?.value.trim() || '',
            ivf: inputs[2]?.value.trim() || '',
            rate: inputs[3]?.value.trim() || ''
        });
    });

    return infusionData;
}

function getLaboratoryData() {
    const tbody = document.getElementById('laboratoryBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    const labData = [];

    rows.forEach(row => {
        // Skip the add row button
        if (row.id === 'addLaboratoryRow') return;

        const inputs = row.querySelectorAll('input');

        labData.push({
            date: inputs[0]?.value || '',
            laboratory_diagnostic: inputs[1]?.value.trim() || '',
            status: inputs[2]?.value.trim() || ''
        });
    });

    return labData;
}

function getProcedureData() {
    return { procedure_text : document.getElementById('procedure_text')?.value || null}
}

function getDateContraptionData() {
    return {
      iv_access: document.getElementById('iv_access')?.value || null,
      iv_tubing: document.getElementById('iv_tubing')?.value || null,
      ngt: document.getElementById('ngt')?.value || null,
      date_contraption_others: document.getElementById('date_contraption_others')?.value || null
    };
  }
  
function getTreatmentsData() {
    return {
      vs_value: document.getElementById('vs_value')?.value || null,
      io_value: document.getElementById('io_value')?.value || null,
      nvs_value: document.getElementById('nvs_value')?.value || null,
      position_value: document.getElementById('position_value')?.value || null,
      others_text: document.getElementById('treatmentOhters')?.value || null
    };
  }
  
function getAttachmentsData() {
    return {
      o2_checkbox: document.getElementById('o2-checkbox')?.checked ? 1 : 0,
      o2_value: document.getElementById('o2-value')?.value || null,
      o2_lpm_value: document.getElementById('o2-lpm')?.value || null,
  
      ngt_checkbox: document.getElementById('ngt-checkbox')?.checked ? 1 : 0,
      ngt_value: document.getElementById('ngt-value')?.value || null,
  
      ogt_checkbox: document.getElementById('ogt-checkbox')?.checked ? 1 : 0,
      ogt_value: document.getElementById('ogt-value')?.value || null,
  
      fbc_checkbox: document.getElementById('fbc-checkbox')?.checked ? 1 : 0,
      fbc_value: document.getElementById('fbc-value')?.value || null,
  
      ctt_checkbox: document.getElementById('ctt-checkbox')?.checked ? 1 : 0,
      ctt_value: document.getElementById('ctt-value')?.value || null,
  
      ett_size_checkbox: document.getElementById('ett_size_check')?.checked ? 1 : 0,
      ett_size_value: document.getElementById('ett_size_value')?.value || null,
      ett_level_value: document.getElementById('ett_level_value')?.value || null,
  
      tracheostomy_attached_checkbox: document.getElementById('tracheostomy_attached_check')?.checked ? 1 : 0,
  
      ppv_checkbox: document.getElementById('ppv_check')?.checked ? 1 : 0,
      ppv_value: document.getElementById('ppv_value')?.value || null,
      ppv_lmin_value: document.getElementById('ppv_lmin_value')?.value || null,
  
      mechanical_ventilator_checkbox: document.getElementById('mechanical_ventilator')?.checked ? 1 : 0,
  
      mode_checkbox: document.getElementById('mode_check')?.checked ? 1 : 0,
      mode_value: document.getElementById('mode_value')?.value || null,
      fio2_value: document.getElementById('fio2_value')?.value || null,
  
      bur_checkbox: document.getElementById('bur_check')?.checked ? 1 : 0,
      bur_value: document.getElementById('bur_value')?.value || null,
      pfr_value: document.getElementById('pfr_value')?.value || null,
  
      tv_checkbox: document.getElementById('tv_check')?.checked ? 1 : 0,
      tv_value: document.getElementById('tv_value')?.value || null,
      peep_value: document.getElementById('peep_value')?.value || null,
  
      cardiac_monitor_checkbox: document.getElementById('cardiac_monitor')?.checked ? 1 : 0,
      pulse_oximeter_checkbox: document.getElementById('pulse_oximeter')?.checked ? 1 : 0,
  
      attachmentOthers_value: document.getElementById('attachmentOthers_value')?.value || null
    };
  }
  
async function submitChanges(){
    console.log("SUBMIT");
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    const treatments = getTreatmentsData();
    const attachments = getAttachmentsData();
    const infusionContent = getInfusionData('infusionTableBody', 'addRow');
    const infusion2Content = getInfusionData('infusionTableBody2', 'addRow2');
    const laboratories = getLaboratoryData();
    const refferedDept = document.getElementById('deptSelect').value;
    const reason = document.getElementById('reason').value;

    console.log(attachments);



    const saveData = {
        referral: {
            patient_id: id,
            department_id: refferedDept,
            reason: reason,
        },
        infusions: {
            patient_id: id,
            ifusions: infusionContent.map(infusion => ({
                ...infusion,
                display_table: String(1)
            }))
        },
        infusions2: {
            patient_id: id,
            ifusions:infusion2Content.map(infusion2 => ({
                ...infusion2,
                display_table: String(2)
            }))
        },
        laboratories: {
            patient_id: id,
            laboratories:laboratories.map(laboratory => ({
                ...laboratory
            }))
        }
      };
 
      const profileDetails = getEditedProfileDetails();
  
      const profileRequest = fetch(`/kardex_system/src/routes/index.php/updatePatient/${id}`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(profileDetails)
      });

    // Treatments
    const treatmentRequest = fetch(`/kardex_system/src/routes/index.php/addTreatment/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            patient_id: id,
            treatments: treatments
        })
    });

    const referralRequest = fetch(`/kardex_system/src/routes/index.php/addReferral/${id}`, {
        method: "POST",
        body: JSON.stringify(saveData.referral)
    });

    // Procedure/Operaations
    const procedureRequest = fetch(`/kardex_system/src/routes/index.php/addProcedure/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify( {
            patient_id: id,
            procedure_text: getProcedureData(),
            contraption_dates: getDateContraptionData()
        })
    });

    console.log(attachments);
    const attachmentRequest = fetch(`/kardex_system/src/routes/index.php/addAttachment/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            patient_id: id,
            attachments: attachments
        })
    });


    submitIMultiRowData(id, saveData.infusions, saveData.infusions2, saveData.laboratories);
    // Await all
    const results = await Promise.allSettled([profileRequest, treatmentRequest, referralRequest, attachmentRequest, procedureRequest ]);

    results.forEach((result, index) => {
        if (result.status === "fulfilled") {
            console.log(`Request ${index + 1} succeeded`, result.value);
        } else {
            console.error(`Request ${index + 1} failed`, result.reason);
        }
    });
}

async function  submitIMultiRowData(id, infusions, infusions2, laboratories) {
    // Step 1: Delete current infusions
    const deleteResponse = await fetch(`/kardex_system/src/routes/index.php/deleteIVF/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(id)
    });

    if (!deleteResponse.ok) {
        console.error("Failed to delete current infusions");
        return;
    }

    // Step 2: Add first infusion table
    const infusionResponse = await fetch(`/kardex_system/src/routes/index.php/addIVF/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(infusions)
    });

    if (!infusionResponse.ok) {
        console.error("Failed to add first infusion table");
        return;
    }

    // Step 3: Add second infusion table
    const infusion2Response = await fetch(`/kardex_system/src/routes/index.php/addIVF/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(infusions2)
    });

    if (!infusion2Response.ok) {
        console.error("Failed to add second infusion table");
        return;
    }

    const laboratoryResponse = await fetch(`/kardex_system/src/routes/index.php/addLaboratory/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(laboratories)
    });

    if (!laboratoryResponse.ok) {
        console.error("Failed to add first laboratry table");
        return;
    }
}


