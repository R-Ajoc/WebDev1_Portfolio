loadPatientInformation();
let deletedFiles = [];


function fillUpDetails(elementId, details, inputType = 'text') {
    const container = document.getElementById(elementId);
    
    container.innerHTML = ""; // Clear existing inputs if needed

    if (Array.isArray(details) && details.length !== 0) {
        details.forEach(value => {

            const input = document.createElement("input");
            input.type =  (inputType === 'date') ? 'date' : 'text';
            input.value = value;

            input.className = "w-full border-b border-black px-2 focus:outline-none focus:ring-0";
            container.appendChild(input);
        });
    }    
}


function fillMultiRowTable(dataArray, multiRoleBodyId, addRowId) {
    const tbody = document.getElementById(multiRoleBodyId);
    const addRow = document.getElementById(addRowId);

    // Clear all but the add row
    tbody.innerHTML = '';
    tbody.appendChild(addRow);

    if (multiRoleBodyId == 'laboratoryBody') {
        dataArray.forEach(infusion => displayLaboratoryRow(infusion, multiRoleBodyId, addRowId));
    } else {
        dataArray.forEach(infusion => displayInfusionRow(infusion, multiRoleBodyId, addRowId));
    }
}

function displayInfusionRow(data, infusionBodyId, addRowId) {
    const tbody = document.getElementById(infusionBodyId);
    const addRow = document.getElementById(addRowId);

    const newRow = document.createElement('tr');
    newRow.className = "border-b border-black";

    const safeDate = (data.date && data.date !== '0000-00-00') ? data.date : '';
    
    newRow.innerHTML = `
        <td class="p-1 border-r border-black">
            <input type="date" class="w-full focus:outline-none focus:ring-0" value="${safeDate}" />
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


function displayLaboratoryRow(data, laboratoryBodyId, addRowId) {
    const tbody = document.getElementById(laboratoryBodyId);
    const addRow = document.getElementById(addRowId);
    console.log(data);
    const newRow = document.createElement('tr');
    newRow.className = "border-b border-black";

    const safeDate = (data.date && data.date !== '0000-00-00') ? data.date : '';

    newRow.innerHTML = `
        <td class="p-1 border-r border-black">
            <input type="date" class="w-full focus:outline-none focus:ring-0" value="${safeDate}" />
        </td>
        <td class="p-1 border-r border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0" value="${data.laboratory_diagnostic || ''}" />
        </td>
        <td class="p-1 border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0" value="${data.status || ''}" />
        </td>
    `;

    tbody.insertBefore(newRow, addRow);
}


async function loadPatientInformation() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    try {
        const treatmentRequest = fetch(`/kardex_system/src/routes/index.php/treatments/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const referralRequest = fetch(`/kardex_system/src/routes/index.php/referrals/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const attachmentRequest = fetch(`/kardex_system/src/routes/index.php/attachments/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const infusionRequest = fetch(`/kardex_system/src/routes/index.php/infusions/display_table1/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const infusion2Request = fetch(`/kardex_system/src/routes/index.php/infusions/display_table2/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const procedureRequest = fetch(`/kardex_system/src/routes/index.php/procedures/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const laboratoryRequest = fetch(`/kardex_system/src/routes/index.php/laboratory/${id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const [
            treatmentResult,
            referralResult,
            attachmentResult,
            infusionResult,
            infusion2Result,
            procedureResult,
            laboratoryResult
          ] = await Promise.allSettled([
            treatmentRequest,
            referralRequest,
            attachmentRequest,
            infusionRequest,
            infusion2Request,
            procedureRequest,
            laboratoryRequest
          ]);
          
        // Referral Table
        if (referralResult.status === "fulfilled") {
            const referrals = await referralResult.value.json();
            if (Array.isArray(referrals) && referrals.length > 0) {
                document.getElementById('deptSelect').value = referrals[0]?.department_id || '';
                document.getElementById('reason').value = referrals[0].reason;
            } else {
                console.warn("No referral data found.");
                // Optionally clear fields
                document.getElementById('deptSelect').value = '';
                document.getElementById('reason').value = '';
            }
        } else {
            console.error("Failed to load referrals:", referralResult.reason);
        }
                                                               
        
        // Infusion table 1
        if (infusionResult.status === "fulfilled") {
            const infusionData = await infusionResult.value.json();
            if (Array.isArray(infusionData) && infusionData.length > 0) 
                fillMultiRowTable(infusionData, 'infusionTableBody', 'addRow');
            else console.log("No infusion data found.");
        } else {
            console.error("Failed to load infusions:", infusionResult.reason);
        }

        //Infusion Table 2
        if (infusion2Result.status === "fulfilled") {
            const infusion2Data = await infusion2Result.value.json();
            if (Array.isArray(infusion2Data) && infusion2Data.length > 0) 
                fillMultiRowTable(infusion2Data, 'infusionTableBody2', 'addRow2');
            else console.log("No infusion data found.");
        } else {
            console.error("Failed to load infusions:", infusion2Result.reason);
        }

        //Laboratory Table
        if (laboratoryResult.status === "fulfilled") {
            const labData = await laboratoryResult.value.json();
            if (Array.isArray(labData) && labData.length > 0) 
                fillMultiRowTable(labData, 'laboratoryBody', 'addLaboratoryRow');
            else console.log("No laboratory data found.");
        } else {
            console.error("Failed to load infusions:", infusion2Result.reason);
        }

        let combinedData = {
            procedure_text: null,
            treatments: null,
            attachments: null,
            contraption_dates: null
          };
          
          // 🔹 TREATMENT

          if (treatmentResult.status === "fulfilled") {
            const treatment = await treatmentResult.value.json();  
            combinedData.treatments = treatment[0];
              
          } else {
              console.error("Failed to load treatment:", treatmentResult.reason);
          }
          
          // 🔹 ATTACHMENTS
          if (attachmentResult.status === "fulfilled") {
              const attachments = await attachmentResult.value.json();
              combinedData.attachments = attachments[0];
          } else {
              console.error("Failed to load attachments:", attachmentResult.reason);
          }
          
          // PROCEDURES
          let procedures = null;
          if (procedureResult.status === "fulfilled") {
              procedures = await procedureResult.value.json(); 
              if (Array.isArray(procedures) && procedures.length > 0) {
                  combinedData.procedure_text = procedures[0].procedure_text || null;
                  combinedData.contraption_dates = {
                      iv_access: procedures[0].iv_access || null,
                      iv_tubing: procedures[0].iv_tubing || null,
                      ngt: procedures[0].ngt || null,
                      date_contraption_others: procedures[0].date_contraption_others || null
                  };
              }
          } else {
              console.error("Failed to load procedures:", procedureResult.reason);
          }
          
          // ✅ Call function to populate form with all data
          populatePatientData(combinedData);
          


        return true;
    } catch (err) {
        console.error("Unexpected error:", err);
        return false;
    }
}
async function loadDataToForm(data, category, inputType = 'text') {
    console.log("Received:", data);
    
    let parsed = [];

    try {
        parsed = Array.isArray(data) ? data : JSON.parse(data);
    } catch (e) {
        console.warn("Failed to parse JSON, using raw data:", data);
        parsed = [data];
    }

    // 🔥 Flatten if it's a 2D array
    if (Array.isArray(parsed) && parsed.every(item => Array.isArray(item))) {
        parsed = parsed.flat();
    }

    if (Array.isArray(parsed)) {
        fillUpDetails(category, parsed, inputType);
    }
}


function populatePatientData(data) {
    console.log(data);
    // Procedure
    if (data.procedure_text) {
      document.getElementById('procedure_text').value = data.procedure_text || '';
    }
  
    // Date Contraption
    if (data.contraption_dates) {
      document.getElementById('iv_access').value = data.contraption_dates.iv_access || '';
      document.getElementById('iv_tubing').value = data.contraption_dates.iv_tubing || '';
      document.getElementById('ngt').value = data.contraption_dates.ngt || '';
      document.getElementById('date_contraption_others').value = data.contraption_dates.date_contraption_others || '';
    }
  
    // Treatments
        console.log(data.treatments);
    if (data.treatments) {
      document.getElementById('vs_value').value = data.treatments.vs_value || '';
      document.getElementById('io_value').value = data.treatments.io_value || '';
      document.getElementById('nvs_value').value = data.treatments.nvs_value || '';
      document.getElementById('position_value').value = data.treatments.position_value || '';
      document.getElementById('treatmentOhters').value = data.treatments.others_text || '';
    }
  
    // Attachments
    if (data.attachments) {
      const setCheck = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.checked = !!val;
      };
      const setValue = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val || '';
      };
  
      setCheck('o2-checkbox', data.attachments.o2_checkbox);
      setValue('o2-value', data.attachments.o2_value);
      setValue('o2-lpm', data.attachments.o2_lpm_value);
  
      setCheck('ngt-checkbox', data.attachments.ngt_checkbox);
      setValue('ngt-value', data.attachments.ngt_value);
  
      setCheck('ogt-checkbox', data.attachments.ogt_checkbox);
      setValue('ogt-value', data.attachments.ogt_value);
  
      setCheck('fbc-checkbox', data.attachments.fbc_checkbox);
      setValue('fbc-value', data.attachments.fbc_value);
  
      setCheck('ctt-checkbox', data.attachments.ctt_checkbox);
      setValue('ctt-value', data.attachments.ctt_value);
  
      setCheck('ett_size_check', data.attachments.ett_size_checkbox);
      setValue('ett_size_value', data.attachments.ett_size_value);
      setValue('ett_level_value', data.attachments.ett_level_value);
  
      setCheck('tracheostomy_attached_check', data.attachments.tracheostomy_attached_checkbox);
  
      setCheck('ppv_check', data.attachments.ppv_checkbox);
      setValue('ppv_value', data.attachments.ppv_value);
      setValue('ppv_lmin_value', data.attachments.ppv_lmin_value);
  
      setCheck('mechanical_ventilator', data.attachments.mechanical_ventilator_checkbox);
  
      setCheck('mode_check', data.attachments.mode_checkbox);
      setValue('mode_value', data.attachments.mode_value);
      setValue('fio2_value', data.attachments.fio2_value);
  
      setCheck('bur_check', data.attachments.bur_checkbox);
      setValue('bur_value', data.attachments.bur_value);
      setValue('pfr_value', data.attachments.pfr_value);
  
      setCheck('tv_check', data.attachments.tv_checkbox);
      setValue('tv_value', data.attachments.tv_value);
      setValue('peep_value', data.attachments.peep_value);
  
      setCheck('cardiac_monitor', data.attachments.cardiac_monitor_checkbox);
      setCheck('pulse_oximeter', data.attachments.pulse_oximeter_checkbox);
  
      setValue('attachmentOthers_value', data.attachments.attachmentOthers_value);
    }
  }
  