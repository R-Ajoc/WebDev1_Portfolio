let currentPasscode = "";
let newPasscode = "";
let verifyNewPasscode = "";

const user_id = document.body.dataset.userId;


function confirmNewPasscode() {
  if (passcode.length === 4) {
    newPasscode = passcode;
    clearPasscode();
    showPasscodeUI("Confirm New Passcode", "confirmRetypePasscode");
  } else {
    document.getElementById("errorModal").classList.remove("hidden");
    clearPasscode();
  }
}

async function confirmRetypePasscode() {
  verifyNewPasscode = passcode;
  console.log("Current:", currentPasscode ,"New:", newPasscode, "Verify:", verifyNewPasscode, "Entered:", passcode);
  
  if (newPasscode !== verifyNewPasscode) {
    document.getElementById("errorModal").classList.remove("hidden");
    clearPasscode(); 
    showPasscodeUI("Confirm New Passcode", "confirmRetypePasscode");
    return;
  }

  const passcodeToUpdate = {
    user_id: user_id,
    current_passcode: currentPasscode,
    new_passcode: verifyNewPasscode
  };

  try {
    const response = await fetch("/kardex_system/src/routes/index.php/updatePasscode", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(passcodeToUpdate)
    });

    const result = await response.json();

    if (!result.success) {
      document.getElementById("errorModal").classList.remove("hidden");
      return false;
    }

    showSuccessModal();
    return true;
  } catch (err) {
    alert("Something went wrong. Please try again");
    return false;
  }
}

//NAA SA PIKAS BOGO
function redirectToUserProfile() {
  const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../views/userProfile.php';
    document.body.appendChild(form);
    form.submit();
}


showPasscodeUI("Enter Old Task Passcode", "confirmPasscode");