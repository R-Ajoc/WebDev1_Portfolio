
const openTreatment = document.getElementById('openTreatment');
const openReferrals = document.getElementById('openReferrals');
const openTopAttachments = document.getElementById('openTopAttachments');
const openBottomAttachments = document.getElementById('openBottomAttachments');
const openProcedures = document.getElementById('openProcedures');
const openDateContraptions = document.getElementById('openDateContraptions');


const popupTitle = document.getElementById('popupTitle');


let currentEditType = "";
let currentRoute = "";

let treatmentValues = "";

openTreatment.addEventListener('click', () => openPopup('Add Treatment', 'addTreatment'));
openTopAttachments.addEventListener('click', () => openAttachmentPopup('Add Top Attachment'));
openBottomAttachments.addEventListener('click', () => openAttachmentPopup('Add Bottom Attachment'));
openProcedures.addEventListener('click', () => openPopup('Add Procedures/Operations', 'addReferral'));
openDateContraptions.addEventListener('click', () => openPopup('Add Date Contraptions', 'addReferral'));

const addRowPopupBtn = document.getElementById('addRowInputPopup');

addRowPopupBtn.addEventListener('click', () => addRowInputPopup('popupInputRows'));


function openPopup(type, route) {
    currentEditType = type;
    currentRoute = route;
    document.getElementById("editPopup").classList.remove("hidden");

    switch (currentEditType) {
        case 'Add Treatment':
            treatmentValues = getCurrentDetails('treatmentDetails')
            fillUpPopupDetails(treatmentValues, type);
         
            break;
        case 'Add Procedures/Operations':
            procedureValues = getCurrentDetails('proceduresDetails')
            fillUpPopupDetails(procedureValues, type);
            break;
        case 'Add Date Contraptions':
            contraptionValues = getCurrentDetails('dateContraptionsDetails')
            fillUpPopupDetails(contraptionValues, type);
            break;
        
    }
}

function closePopup(isCancelled) {
    document.getElementById("editPopup").classList.add("hidden");

    currentRoute = "";

    if (!isCancelled){
        switch (currentEditType) {
            case 'Add Treatment':
                treatmentValues = getPopupInputValues('popupInputRows')
                reloadChanges(currentEditType, treatmentValues);
                break;
            case 'Add Proceduares/Operations':
                attachmentBottomValues = getPopupInputValues('popupInputRows')
                reloadChanges(currentEditType, attachmentBottomValues);
                break;
            case 'Add Date Contraptions':
                attachmentBottomValues = getPopupInputValues('popupInputRows')
                reloadChanges(currentEditType, attachmentBottomValues);
                break;
        }
    }
   
}

function reloadChanges(currentEditType, values){
    switch (currentEditType) {
        case 'Add Treatment':
            fillUpDetails('treatmentDetails', values)
            break;
        case 'Add Top Attachment':
            console.log('YAWA' + values)
           
            break;
        case 'Add Bottom Attachment':
            fillUpAttachments('attachmentBottomDetails', values)
            break;
        case 'Add Procedures/Operations':
            fillUpDetails('proceduresDetails', values)
            break;
        case 'Add Date Contraptions':
            fillUpDetails('dateContraptionsDetails', values)
            break;
    }   
}






function getCurrentDetails(elementId){
    return getPopupInputValues(elementId);
}


function fillUpPopupDetails(details, type) {
    popupTitle.textContent = type;
  
    const container = document.getElementById('popupInputRows');
    container.innerHTML = ""; // Clear existing rows before adding new ones
   
    const numberOfRows = Math.max(details.length, 4); // Ensure at least 4 rows

    for (let i = 0; i < numberOfRows; i++) {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'w-full border-b border-l border-r border-black p-2 focus:outline-none focus:ring-0';
        input.value = details[i] || ""; // Fill with value if available
        container.appendChild(input);
    }
}



function addRowInputPopup(){
    const container = document.getElementById('popupInputRows');

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'w-full border-b border-l border-r border-black p-2 focus:outline-none focus:ring-0';

    container.appendChild(input);
}

function saveTreatment() {
    const treatmentText = document.getElementById("treatmentInput").value;
    submitChanges(treatmentText);
    closePopup(false);
    // Optionally update the treatment paragraph here
}


async function submitChanges(){
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    const treatmentContent = getCurrentDetails('treatmentDetails');
    const attachmentContent = getPopupFileInputValues();
    const infusionContent = getInfusionData('infusionTableBody', 'addRow');
    const infusion2Content = getInfusionData('infusionTableBody2', 'addRow2');
    const procedureContent = getCurrentDetails('proceduresDetails');
    const dateContraptionContent = getCurrentDetails('dateContraptionsDetails');

    console.log(procedureContent);
    console.log(dateContraptionContent);

    const saveData = {
        treatment: {
          patient_id: id,
          treatment_text: JSON.stringify([treatmentContent]),
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
        procedure: {
            patient_id: id,
            procedure_name: JSON.stringify([procedureContent]),

        },
      };
      
    // Treatments
    const treatmentRequest = fetch(`/kardex_system/src/routes/index.php/addTreatment/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(saveData.treatment)
    });

    // Procedure/Operaations
    const procedureRequest = fetch(`/kardex_system/src/routes/index.php/addTreatment/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(saveData.treatment)
    });

    // Second fetch: send attachment using FormData
    const formData = new FormData();
    formData.append("patient_id", id);
    if (attachmentContent.length > 0) {
        formData.append("file", attachmentContent[0]); // Assuming one file for now
    }

    const attachmentRequest = fetch(`/kardex_system/src/routes/index.php/addAttachment/${id}`, {
        method: "POST",
        body: formData
    });

    submitInfusions(id, saveData.infusions, saveData.infusions2);
    // Await all
    const results = await Promise.allSettled([treatmentRequest, attachmentRequest ]);

    results.forEach((result, index) => {
        if (result.status === "fulfilled") {
            console.log(`Request ${index + 1} succeeded`, result.value);
        } else {
            console.error(`Request ${index + 1} failed`, result.reason);
        }
    });
}

async function  submitInfusions(id, infusions, infusions2) {
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

}


loadPatientInformation();

async function loadPatientInformation() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    try {
        const treatmentRequest = fetch(`/kardex_system/src/routes/index.php/treatments/${id}`, {
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

        const [treatmentResult, attachmentResult, infusionResult, infusion2Result ] = await Promise.allSettled([treatmentRequest, 
                                                                                               attachmentRequest, 
                                                                                               infusionRequest, 
                                                                                               infusion2Request]);

                                                                      

        // 🔹 TREATMENT
        
        let treatmentData = null;
        if (treatmentResult.status === "fulfilled") {
            treatmentData = await treatmentResult.value.json();
        
            if (Array.isArray(treatmentData) && treatmentData.length > 0) {
                // one treatment record
                const raw = treatmentData[0].treatment_text;
        
                try {
                    const parsed = JSON.parse(raw);
                    console.log("Parsed treatment:", parsed);
        
                    if (Array.isArray(parsed)) {
                        for (const row of parsed) {
                            // row is like ["lobaga", "yawaa", ...]
                            reloadChanges('Add Treatment', row); // wrap row to make it a 2D array
                        }
                    } else {
                        reloadChanges('Add Treatment', [[raw]]);
                    }
                } catch (e) {
                    console.log([[raw]]);
                    reloadChanges('Add Treatment', [[raw]]);
                }
            }
        } else {
            console.error("Failed to load treatment:", treatmentResult.reason);
        }
        
        
        

        // 🔹 ATTACHMENTS
        if (attachmentResult.status === "fulfilled") {
            const attachments = await attachmentResult.value.json();

            topAttachmentDbFiles = attachments
            // fillUpAttachments('attachmentTopDetails', [], attachments); // ✅ empty fileList, valid DB list
       
        } else {
            console.error("Failed to load attachments:", attachmentResult.reason);
        }

        console.log(infusionResult);
        // INFUSIONS
        if (infusionResult.status === "fulfilled") {
            const infusionData = await infusionResult.value.json();
            console.log("Infusion data:", infusionData);

            if (Array.isArray(infusionData) && infusionData.length > 0) {
                fillInfusionTable(infusionData, 'infusionTableBody', 'addRow');
            } else {
                console.log("No infusion data found.");
            }
        } else {
            console.error("Failed to load infusions:", infusionResult.reason);
        }

        if (infusion2Result.status === "fulfilled") {
            const infusion2Data = await infusion2Result.value.json();
            console.log("Infusion data:", infusion2Data);

            if (Array.isArray(infusion2Data) && infusion2Data.length > 0) {
                fillInfusionTable(infusion2Data, 'infusionTableBody2', 'addRow2');
            } else {
                console.log("No infusion data found.");
            }
        } else {
            console.error("Failed to load infusions:", infusion2Result.reason);
        }


        return true;
    } catch (err) {
        console.error("Unexpected error:", err);
        return false;
    }
}


