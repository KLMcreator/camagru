const check_password_match = () => {
  let userPwd = document.getElementById("userPwd").value;
  let userMessage = document.getElementById("userCheckPassword");
  // check length
  if (userPwd.length < 8) {
    userMessage.innerHTML = "Your password must have at least 8 characters.";
    document.getElementById("createSubmit").disabled = "true";
    document.getElementById("userCheckPassword").style.display = "block";
    document.getElementById("userCheckPassword").style.color = "red";
    // check if there's a lowercase char
  } else if (userPwd.search(/[a-z]/) < 0) {
    userMessage.innerHTML =
      "Your password must have at least a lower case letter.";
    document.getElementById("createSubmit").disabled = "true";
    document.getElementById("userCheckPassword").style.display = "block";
    document.getElementById("userCheckPassword").style.color = "red";
    // check if there's an uppercase char
  } else if (userPwd.search(/[A-Z]/) < 0) {
    userMessage.innerHTML =
      "Your password must have at least an upper case letter.";
    document.getElementById("createSubmit").disabled = "true";
    document.getElementById("userCheckPassword").style.display = "block";
    document.getElementById("userCheckPassword").style.color = "red";
    // check if there's a number char
  } else if (userPwd.search(/[0-9]/) < 0) {
    userMessage.innerHTML = "Your password must have at least one number.";
    document.getElementById("createSubmit").disabled = "true";
    document.getElementById("userCheckPassword").style.display = "block";
    document.getElementById("userCheckPassword").style.color = "red";
    // we good
  } else {
    if (userPwd === document.getElementById("userVerifPwd").value) {
      userMessage.innerHTML = "Your password is ok!";
      document.getElementById("createSubmit").removeAttribute("disabled");
      document.getElementById("userCheckPassword").style.display = "block";
      document.getElementById("userCheckPassword").style.color = "green";
    } else {
      userMessage.innerHTML = "Your password must match the verification.";
      document.getElementById("createSubmit").disabled = "true";
      document.getElementById("userCheckPassword").style.display = "block";
      document.getElementById("userCheckPassword").style.color = "red";
    }
  }
};
