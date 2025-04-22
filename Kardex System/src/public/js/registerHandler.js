const nurseForm = document.getElementById("nurse-form");
const passcodeBtn = document.getElementById("submit-passcode");
const passcodeErrorMsg = document.getElementById("passcode-helper");

const firstname = document.getElementById('first-name');
const midInit = document.getElementById('middle-initial');
const lastname = document.getElementById('last-name');
const email = document.getElementById('email');
const department = document.getElementById('department');
const contact = document.getElementById('contact');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm-password');
const passcode = document.getElementById('passcode');
const confirmPasscode = document.getElementById('confirm-passcode');

document.getElementById('close-popup').addEventListener('click', () => {
  closePasscodePopup();
  passcodeErrorMsg.classList.add("text-gray-500");
  passcodeErrorMsg.classList.remove("text-red-600", "font-semibold");
});

// STEP 1: On main form submit
nurseForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  if (!isPasswordMatch()) {
    return;
  } else { 
    removeInputError(password);
    removeInputError(confirmPassword);
  }

  const isSuccess = await submitInitialData(); // Step 1: validate and post nurse data
  if (isSuccess) openPasscodePopup(); // only if step 1 succeeded
});

// STEP 2: On passcode submit
passcodeBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  if (!isPasscodeValid()) return;

  const isSuccess = await submitPasscodeData(); // Step 2: send passcode + nurse data
  if (isSuccess) {
    alert("Nurse successfully added!");
    resetForm("nurse-form");
    closePasscodePopup();
    window.location.href = "mainMenu.html";
  }
});

function isPasswordMatch() {
  const existingError = password.parentElement.querySelector(".password-error");
  if (existingError) existingError.remove();

  if (password.value !== confirmPassword.value) {
    showInputError(password, "Passwords do not match.");
    showInputError(confirmPassword, "Passwords do not match.");
    return false;
  }
  return true;
}

function isPasscodeValid() {
  const highlightHelperError = () => {
    passcodeErrorMsg.classList.remove("text-gray-500");
    passcodeErrorMsg.classList.add("text-red-600", "font-semibold");
  };

  if (passcode.value.trim() !== confirmPasscode.value.trim() || !/^\d{4}$/.test(passcode.value.trim())) {
    highlightHelperError();
    return false;
  }

  return true;
}

// Extracted function: builds base nurse data (without passcode)
function getNurseData() {
  return {
    lastname: lastname.value.trim(),
    firstname: firstname.value.trim(),
    midinit: midInit.value.trim(),
    email: email.value.trim(),
    password: password.value.trim(),
    department_id: department.value,
    contactNo: contact.value.trim()
  };
}

// STEP 1: Send nurse data (without passcode)
async function submitInitialData() {
  const nurseData = getNurseData();

  try {
    const response = await fetch("/kardex_system/src/routes/index.php/register/step1", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(nurseData)
    });

    const result = await response.json();
    console.log(result.errors);
    if (!result.success) {
      if (result.errors.emptyDepartment) showInputError(department, "Please select a Department");
      if (result.errors.emptyFirstname) showInputError(firstname, "First name cannot be empty");
      if (result.errors.emptyEmail) {
        showInputError(email, "Email name cannot be empty");
      } else {
        if (result.errors.emailExists) showInputError(email, "Email already exists");
      }
      if (result.errors.emptyLastname) showInputError(lastname, "Last name cannot be empty");
      if (result.errors.emptyPassword) showInputError(password, "Password cannot be empty");
      if (result.errors.emptyPassword) showInputError(confirmPassword, "Password cannot be empty");
     

      return false;
    }

    return true;
  } catch (err) {
    console.error("Step 1 error:" + result.errors, err);
    alert("Something went wrong. Please try again");
    return false;
  }
}

// STEP 2: Send full data including passcode
async function submitPasscodeData() {
  const nurseDataWithPasscode = {
    ...getNurseData(),
    passcode: passcode.value.trim()
  };

  console.log("Final nurse data with passcode:", nurseDataWithPasscode);

  try {
    const response = await fetch("/kardex_system/src/routes/index.php/register/step2", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(nurseDataWithPasscode)
    });

    const result = await response.json();

    if (!result.success) {
      alert("Step 2 Error: " + result.message);
      return false;
    }

    return true;
  } catch (err) {
    console.error("Step 2 error:", err);
    alert("Something went wrong during step 2.");
    return false;
  }
}

// Reset form and popup
function resetForm(formId) {
  const form = document.getElementById(formId);
  form.reset();
  passcode.value = "";
  confirmPasscode.value = "";
}

// Show/hide popup
function openPasscodePopup() {
  document.getElementById("passcodePopup").classList.remove("hidden");
}

function closePasscodePopup() {
  document.getElementById("passcodePopup").classList.add("hidden");
}
