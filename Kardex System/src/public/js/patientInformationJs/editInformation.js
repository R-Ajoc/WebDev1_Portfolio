const addTreatmentRow = document.getElementById('addTreatmentRow').addEventListener('click', () => {addRow('treatmentDetails')});
document.getElementById('addAttachmentTopRow').addEventListener('click', () => {addRow('attachmentTopDetails', 'attachment')});
document.getElementById('addAttachmentBottomRow').addEventListener('click', () => {addRow('attachmentBottomDetails', 'attachment')});
document.getElementById('addProcedureRow').addEventListener('click', () => {addRow('procedureDetails')});
document.getElementById('addContraptionDateRow').addEventListener('click', () => {addRow('dateContraptionDetails', 'contraptionDate')});
document.getElementById('editPatientProfile').addEventListener('click', () => {showPopupEditPatientProfile()});

function showPopupEditPatientProfile( ){
    document.getElementById('editProfileModal').classList.remove('hidden');
}
function showPasscodePopup() {
    document.getElementById('passcodeBackdrop').classList.remove('hidden');
    document.getElementById('passcodeContainer').classList.remove('hidden');
  }
  
  function hidePasscodePopup() {
    document.getElementById('passcodeBackdrop').classList.add('hidden');
    document.getElementById('passcodeContainer').classList.add('hidden');
  }

  document.getElementById('passcodeBackdrop').addEventListener('click', hidePasscodePopup);


function addRow(containerId, type = 'default') {
    console.log(containerId);
    const container = document.getElementById(containerId);
    
    const inputWrapper = document.createElement('div');
    inputWrapper.className = 'relative flex items-center';

    let input;

    if (type === 'default') {
        input = document.createElement('input');
        input.type = 'text';
        input.className = 'w-full border-b h-6 border-black p-2 focus:outline-none focus:ring-0';
        container.appendChild(input);
    } else if (type === 'attachment') {
        input = document.createElement('input');
        input.type = 'file';
        input.className = "w-full border-b h-6 border-black file:px-2 file:py-[0px] file:text-xs file:bg-gray-200 file:text-gray-800 file:border file:border-gray-300 file:rounded text-xs pr-10";

        const previewIcon = setupFile(input); // pass input, get icon

        inputWrapper.appendChild(input);
        inputWrapper.appendChild(previewIcon);
    } else if ('contraptionDate'){
        input = document.createElement('input');
        input.type = 'date';
        input.className = 'w-full border-b h-6 border-black p-2 focus:outline-none focus:ring-0';
        container.appendChild(input);
    }

    container.appendChild(inputWrapper);
}

function addInfusionRow(infusionBodyId, addRowId, data = []) {
    const tbody = document.getElementById(infusionBodyId);
    const addRow = document.getElementById(addRowId);

    // Create new row
    const newRow = document.createElement('tr');
    newRow.className = "border-b border-black";

    // Add input cells
    newRow.innerHTML = `        <td class="p-1 border-r border-black">
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

    // Insert new row BEFORE the add row
    tbody.insertBefore(newRow, addRow);
}
function addLaboratoryRow(){
    const tbody = document.getElementById('laboratoryBody');
    const addRow = document.getElementById('addLaboratoryRow');

    // Create new row
    const newRow = document.createElement('tr');
    newRow.className = "border-b border-black";

    newRow.innerHTML = `
        <td class="p-1 border-r border-black">
            <input type="date" class="w-full focus:outline-none focus:ring-0" />
        </td>
        <td class="p-1 border-r border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0"  />
        </td>
        <td class="p-1 border-black">
            <input type="text" class="w-full focus:outline-none focus:ring-0"  />
        </td>
    `;


    // Insert before the add button row
    tbody.insertBefore(newRow, addRow);
}


function setupFile(input) {
    const previewIcon = document.createElement("img");
    previewIcon.src = '/kardex_system/src/public/images/eye.svg';
    previewIcon.className = "w-5 h-5 cursor-pointer absolute right-2 top-1/2 transform -translate-y-1/2 hidden";
    previewIcon.title = "Preview";

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = () => {
                previewIcon.dataset.image = reader.result;
                previewIcon.onclick = () => previewImage(previewIcon);
                previewIcon.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewIcon.classList.add('hidden');
        }
    });

    return previewIcon;
}

