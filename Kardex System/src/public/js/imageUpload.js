// // Select all static file inputs in the DOM (not dynamically created ones in the popup)
// const fileInputs = document.querySelectorAll(".file-input");

// // Global state for attachments per section
// let selectedTopAttachmentFiles = [];       // Files for top attachment section
// let selectedBottomAttachmentFiles = [];    // Files for bottom attachment section
// let topAttachmentDbFiles = [];    // Files for bottom attachment section
// let selectedFiles = [];                    // Temporary working copy used in popup

// // Attach preview logic to all static file inputs (not dynamic ones created later)
// fileInputs.forEach(input => {
//     input.addEventListener("change", function () {
//         const file = input.files[0];
//         const previewBtn = input.nextElementSibling;

//         // If it's an image, enable preview
//         if (file && file.type.startsWith("image/")) {
//             previewBtn.classList.remove("hidden");
//             previewBtn.dataset.image = URL.createObjectURL(file);
//         } else {
//             // Hide preview for non-image or no file
//             previewBtn.classList.add("hidden");
//             previewBtn.removeAttribute("data-image");
//         }
//     });
// });

// // Opens a full-screen modal to preview the image
// function previewImage(element) {
//     const imageUrl = element.dataset.image;
//     const fileName = element.dataset.name;

//     console.log('Previewing:', fileName);
//     console.log('URL:', imageUrl);

//     if (imageUrl) {
        
//         const previewImg = document.getElementById("previewImage");
//         const modal = document.getElementById("imagePreviewModal");
//         previewImg.src = imageUrl;

//         // Optional: Show filename somewhere
//         const fileNameLabel = document.getElementById("previewFileName");
//         console.log(fileName);
//         if (fileNameLabel) fileNameLabel.textContent = fileName;

//         modal.classList.remove("hidden");
//     }
// }


// // Opens attachment edit popup for either top or bottom section
// function openAttachmentPopup(type) {
//     currentEditType = type;
//     document.getElementById("uploadPopup").classList.remove("hidden");

//     switch (type) {
//         case 'Add Top Attachment':
//             fillUpPopupAttachments(selectedTopAttachmentFiles, topAttachmentDbFiles);
//             break;
//         case 'Add Bottom Attachment':
//             fillUpPopupAttachments(selectedBottomAttachmentFiles, bottomAttachmentDbFiles);
//             break;
//     }
// }

// function closeUploadPopup(isCancelled) {
//     document.getElementById("uploadPopup").classList.add("hidden");

//     if (!isCancelled) {
//         const newFiles = selectedFiles.filter(f => f instanceof File);

//         if (currentEditType === 'Add Top Attachment') {
//             selectedTopAttachmentFiles = newFiles;
//             reloadChanges(currentEditType, newFiles, topAttachmentDbFiles); // ✅ includes DB files already
//         } else if (currentEditType === 'Add Bottom Attachment') {
//             selectedBottomAttachmentFiles = newFiles;
//             reloadChanges(currentEditType, newFiles, bottomAttachmentDbFiles);
//         }
//     }
    
//     fillUpAttachments('attachmentTopDetails', selectedFiles, topAttachmentDbFiles);
// }



// // Closes image preview modal
// function closeImagePreview() {
//     document.getElementById("imagePreviewModal").classList.add("hidden");
// }


// function fillUpPopupAttachments(fileList = [], fileListFromDb = []) {
//     const container = document.getElementById('popupAttachmentRows');
//     container.innerHTML = "";

//     // ✅ Store global working state
//     selectedFiles = [...fileList];                  // Files being uploaded

//     const totalFileCount = Math.max(4, selectedFiles.length + fileListFromDb.length);

//     // === STEP 1: Render files from the database first ===
//     for (let dbFile of fileListFromDb) {
//         const wrapper = document.createElement("div");
//         wrapper.className = "flex items-center justify-between w-full border-b border-black px-1";

//         const fileName = document.createElement("span");
//         fileName.textContent = dbFile.file_name;
//         fileName.className = "truncate max-w-[250px]";

//         const previewIcon = document.createElement("img");
//         previewIcon.src = '/kardex_system/src/public/images/eye.svg';
//         previewIcon.className = "w-6 h-6 cursor-pointer rounded";
//         previewIcon.dataset.image = dbFile.file_path;
//         previewIcon.onclick = () => previewImage(previewIcon);

//         wrapper.appendChild(fileName);
//         wrapper.appendChild(previewIcon);
//         container.appendChild(wrapper);
//     }

//     // === STEP 2: Ensure selectedFiles has at least enough slots to meet total count ===
//     while (selectedFiles.length < totalFileCount - fileListFromDb.length) {
//         selectedFiles.push(null);
//     }

//     // === STEP 3: Render uploaded files and input slots ===
//     for (let i = 0; i < selectedFiles.length; i++) {
//         const file = selectedFiles[i];
//         const wrapper = document.createElement("div");
//         wrapper.className = "flex items-center justify-between w-full border-b border-black px-1";

//         if (file && file instanceof File && file.type?.startsWith("image/")) {
//             const fileName = document.createElement("span");
//             fileName.textContent = file.name;
//             fileName.className = "truncate max-w-[250px]";

//             const previewIcon = document.createElement("img");
//             previewIcon.src = '/kardex_system/src/public/images/eye.svg';
//             previewIcon.className = "w-6 h-6 cursor-pointer rounded";
//             previewIcon.dataset.image = URL.createObjectURL(file);
//             previewIcon.onclick = () => previewImage(previewIcon);

//             wrapper.appendChild(fileName);
//             wrapper.appendChild(previewIcon);
//         } else {
//             const fileInput = document.createElement("input");
//             fileInput.type = "file";

//             const previewIcon = document.createElement("img");
//             previewIcon.src = '/kardex_system/src/public/images/eye.svg';
//             previewIcon.className = "w-6 h-6 hidden cursor-pointer rounded";
//             previewIcon.onclick = () => previewImage(previewIcon);

//             fileInput.addEventListener("change", () => {
//                 const selectedFile = fileInput.files[0];
//                 if (selectedFile) {
//                     selectedFiles[i] = selectedFile;

//                     if (selectedFile.type.startsWith("image/")) {
//                         previewIcon.classList.remove("hidden");
//                         previewIcon.dataset.image = URL.createObjectURL(selectedFile);
//                     } else {
//                         previewIcon.classList.add("hidden");
//                         delete previewIcon.dataset.image;
//                     }
//                 }
//             });

//             wrapper.appendChild(fileInput);
//             wrapper.appendChild(previewIcon);
//         }

//         container.appendChild(wrapper);
//     }
// }
