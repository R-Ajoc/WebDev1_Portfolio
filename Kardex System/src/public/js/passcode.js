let passcode = '';
function pressKey(num) {
    if (passcode.length < 4) {
      passcode += num;
      updateDots();
    }
  }

function showPasscodeUI(title, onConfirm, prevPage ) {
    console.log(onConfirm);
    const container = document.getElementById("passcodeContainer");
    document.getElementById("passcodeBackdrop").classList.remove('hidden');
    
    container.clas
    container.innerHTML = `
    <div class="flex flex-col items-center justify-center bg-[#97b5c4] p-6 rounded-xl z-[60] shadow-xl w-[500px] space-y-6 animate-fadeScaleIn relative">
      <img onclick="closePasscode('passcodeContainer')" src="../public/images/return-button.svg"
           class="absolute hover:scale-[1.04] w-12 h-12 -right-6 top-4 cursor-pointer z-10">
      
      <h2 class="text-white text-4xl font-bold text-center">${title}</h2>
      
      <div class="flex justify-center space-x-4" id="dots">
        ${[1, 2, 3, 4].map(i => `
          <div class="w-5 h-5 rounded-full border border-white" id="dot${i}"></div>
        `).join('')}
      </div>
      
      <div class="flex justify-center">
        <div class="grid grid-cols-3 gap-6">
          ${[1,2,3,4,5,6,7,8,9].map(num => `
            <button class="w-16 h-16 bg-white text-[#97b5c4] font-semibold rounded-full sm:text-4xl" onclick="pressKey('${num}')">${num}</button>
          `).join('')}
          <button class="w-16 h-16 bg-[#ff1a04] hover:bg-[#5eacde] text-white font-semibold rounded-full sm:text-4xl" onclick="clearPasscode()">C</button>
          <button class="w-16 h-16 bg-white text-[#97b5c4] font-semibold rounded-full sm:text-4xl" onclick="pressKey('0')">0</button>
          <button class="w-16 h-16 bg-[#ffb91d] hover:bg-[#5eacde] text-white font-semibold rounded-full sm:text-6xl" onclick="removeLast()">←</button>
        </div>
      </div>
      
      <div class="flex justify-center mt-4">
        <button class="bg-[#5eacde] hover:bg-[#2320d8] text-white font-bold py-4 px-10 sm:text-3xl rounded-xl hover:shadow-xl transform hover:scale-105 transition duration-300"
                onclick="${onConfirm}()">Confirm</button>
      </div>
    </div>
  `;
  
    container.classList.remove("hidden");
  }

  function closePasscode(containerId) {
    const container = document.getElementById(containerId);
    container.classList.add("hidden");
    document.getElementById("passcodeBackdrop").classList.add("hidden");
}

  function updateDots() {
    for (let i = 1; i <= 4; i++) {
      const dot = document.getElementById("dot" + i);
      if (i <= passcode.length) {
        dot.classList.add("bg-white");
      } else {
        dot.classList.remove("bg-white");
      }
    }
  }
  
  function clearPasscode() {
    passcode = "";
    updateDots();
  }
  
  function removeLast() {
    passcode = passcode.slice(0, -1);
    updateDots();
  }
  function showSuccessModal() {
    document.getElementById("successModal").classList.remove("hidden");
    clearPasscode();
  }
  
  function closeSuccessModal() {
    document.getElementById("successModal").classList.add("hidden");
    clearPasscode();
  }
  
  function closeErrorModal() {
    document.getElementById("errorModal").classList.add("hidden");
    clearPasscode();
  }
  
  
  async function confirmPasscode() {
    const passcodeToVerify = {
      user_id: user_id,
      inputPasscode: passcode
    }
  
    try {
      const response = await fetch("/kardex_system/src/routes/index.php/verify", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(passcodeToVerify)
      });
  
      const result = await response.json();
  
      if (!result.success) {
  
        document.getElementById("errorModal").classList.remove("hidden");
        clearPasscode();
  
        return false;
      }
      //success
      currentPasscode = passcode;
      clearPasscode();
      submitChanges();
      closePasscode('passcodeContainer');
      document.getElementById("successModal").classList.remove("hidden");
  
      return true;
    } catch (err) {
        console.log(err);
      alert("Something went wrong. Please try again");
      return false;
    }
  }
  