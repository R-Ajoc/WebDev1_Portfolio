
function removeInputError(inputElement){
    const parent = inputElement.parentElement;
  
    inputElement.classList.remove("border-red-500");
    const err = parent.querySelector(".input-error");
    if (err) err.remove();
  }
  
function showInputError(inputElement, message) {
    const parent = inputElement.parentElement;
  
    // Remove existing error if it exists
    const existingError = parent.querySelector(".input-error");
    if (existingError) existingError.remove();
  
    inputElement.classList.add("border-2", "border-red-500");
  
    // Create the error div
    const errorDiv = document.createElement("div");
    errorDiv.classList.add("text-red-600", "text-[12px]", "mt-[2px]", "input-error");
    errorDiv.textContent = message;
    parent.appendChild(errorDiv);
  
    // Add listener to clear error on input
    inputElement.addEventListener("click", () => {
      removeInputError(inputElement);
    }, { once: true }); // ensures it runs only the first time input happens

    inputElement.addEventListener("blur", () => {
        removeInputError(inputElement);
    }, { once: true }); // ensures it runs only the first time input happens
}

