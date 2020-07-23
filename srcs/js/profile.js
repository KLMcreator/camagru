// option
const selectedOption = document.getElementById("selectedOption");
// button div
const deleteAccount = document.getElementById("deleteAccountDiv");
// mail div
const hiddenEmail = document.getElementById("hiddenEmail");
let userEmail = document.getElementById("userEmail");
let userEmailVerify = document.getElementById("userEmailVerify");
// username div
const hiddenUsername = document.getElementById("hiddenUsername");
let userLogin = document.getElementById("userLogin");
let userLoginVerify = document.getElementById("userLoginVerify");
// pwd div
const hiddenPassword = document.getElementById("hiddenPassword");
let userPassword = document.getElementById("userPassword");
let userPasswordVerif = document.getElementById("userPasswordVerif");
// check div
let helpMsg = document.getElementById("helpMsg");
let userCheckMsg = document.getElementById("userCheckMsg");
// notifs div
let toggleNotificationsDiv = document.getElementById("toggleNotificationsDiv");
let toggleNotifications = document.getElementById("toggleNotifications");

const check_field_match = (field) => {
  if (field === "mail") {
    if (userEmail.value === userEmailVerify.value) {
      userCheckMsg.innerHTML = "Your email is ok!";
      document.getElementById("editSubmitEmail").removeAttribute("disabled");
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "green";
    } else {
      userCheckMsg.innerHTML = "Your email must match the verification.";
      document.getElementById("editSubmitEmail").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
    }
  } else if (field === "login") {
    if (userLogin.value === userLoginVerify.value) {
      userCheckMsg.innerHTML = "Your login is ok!";
      document.getElementById("editSubmitLogin").removeAttribute("disabled");
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "green";
    } else {
      userCheckMsg.innerHTML = "Your login must match the verification.";
      document.getElementById("editSubmitLogin").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
    }
  } else if (field === "pwd") {
    // check length
    if (userPassword.value.length < 8) {
      userCheckMsg.innerHTML = "Your password must have at least 8 characters.";
      document.getElementById("editSubmitPassword").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
      // check if there's a lowercase char
    } else if (userPassword.value.search(/[a-z]/) < 0) {
      userCheckMsg.innerHTML =
        "Your password must have at least a lower case letter.";
      document.getElementById("editSubmitPassword").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
      // check if there's an uppercase char
    } else if (userPassword.value.search(/[A-Z]/) < 0) {
      userCheckMsg.innerHTML =
        "Your password must have at least an upper case letter.";
      document.getElementById("editSubmitPassword").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
      // check if there's a number char
    } else if (userPassword.value.search(/[0-9]/) < 0) {
      userCheckMsg.innerHTML = "Your password must have at least one number.";
      document.getElementById("editSubmitPassword").disabled = "true";
      userCheckMsg.style.display = "flex";
      userCheckMsg.style.color = "red";
      // we good
    } else {
      if (userPassword.value === userPasswordVerif.value) {
        userCheckMsg.innerHTML = "Your password is ok!";
        document
          .getElementById("editSubmitPassword")
          .removeAttribute("disabled");
        userCheckMsg.style.display = "flex";
        userCheckMsg.style.color = "green";
      } else {
        userCheckMsg.innerHTML = "Your password must match the verification.";
        document.getElementById("editSubmitPassword").disabled = "true";
        userCheckMsg.style.display = "flex";
        userCheckMsg.style.color = "red";
      }
    }
  }
};

// display onchange select
selectedOption.addEventListener("change", () => {
  hiddenEmail.style.display = "none";
  deleteAccount.style.display = "none";
  toggleNotificationsDiv.style.display = "none";
  hiddenUsername.style.display = "none";
  hiddenPassword.style.display = "none";
  helpMsg.style.display = "none";
  userCheckMsg.innerHTML = "";
  document.getElementById("userOldEmail").value = "";
  document.getElementById("userOldLogin").value = "";
  document.getElementById("userOldPassword").value = "";
  userEmail.value = "";
  userEmailVerify.value = "";
  userLogin.value = "";
  userLoginVerify.value = "";
  userPassword.value = "";
  userPasswordVerif.value = "";

  if (selectedOption.value === "hiddenEmail") {
    hiddenEmail.style.display = "flex";
    helpMsg.style.display = "flex";
  } else if (selectedOption.value === "hiddenPassword") {
    hiddenPassword.style.display = "flex";
    helpMsg.style.display = "flex";
  } else if (selectedOption.value === "hiddenUsername") {
    hiddenUsername.style.display = "flex";
    helpMsg.style.display = "flex";
  } else if (selectedOption.value === "deleteAccount") {
    deleteAccount.style.display = "flex";
  } else if (selectedOption.value === "toggleNotifications") {
    toggleNotificationsDiv.style.display = "flex";
  }
});

// double check delete account
deleteAccount.addEventListener("click", () => {
  if (
    confirm(
      "Are you sure you want to delete your account and all the related infos?"
    )
  ) {
    // Delete
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "./../actions/deleteAccount.php");
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(
      JSON.stringify({
        userId: userId,
        userMail: userMail,
        userName: userName,
        confirm: "OK",
      })
    );
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.response === "TRUE") {
          document.location.reload(true);
        }
      }
    };
  } else {
    // Do nothing!
  }
});

// ajax toggle notifs
toggleNotifications.addEventListener("click", () => {
  isNotifEnabled = isNotifEnabled == 1 ? 0 : 1;
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "./../actions/updateInfos.php");
  xmlhttp.setRequestHeader("Content-Type", "application/json");
  xmlhttp.send(
    JSON.stringify({
      isNotifEnabled: isNotifEnabled,
      userId: userId,
      userMail: userMail,
      userName: userName,
      editSubmit: "OKNOTIFS",
    })
  );
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.response === "TRUE") {
        toggleNotifications.innerHTML =
          isNotifEnabled == 1
            ? "DISABLE NOTIFICATIONS"
            : "ENABLE NOTIFICATIONS";
      }
    }
  };
});

// delete snapshot and related infos
const deleteSnapshot = (idSnapshot, divId) => {
  if (confirm("Are you sure you want to delete this snapshot?")) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "./../actions/deleteSnapshot.php");
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(
      JSON.stringify({
        idSnapshot: idSnapshot,
        userId: userId,
        userName: userName,
        confirm: "OK",
      })
    );
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.response === "TRUE") {
          document
            .getElementById(divId)
            .parentNode.removeChild(document.getElementById(divId));
        }
      }
    };
  } else {
    // Do nothing!
  }
};

// delete comment and related infos
const deleteComment = (idComment, idImage, divId) => {
  if (confirm("Are you sure you want to delete this comment?")) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "./../actions/deleteComment.php");
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(
      JSON.stringify({
        idComment: idComment,
        userName: userName,
        idImage: idImage,
        confirm: "OK",
      })
    );
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.response === "TRUE") {
          document
            .getElementById(divId)
            .parentNode.removeChild(document.getElementById(divId));
        }
      }
    };
  } else {
    // Do nothing!
  }
};

window.onload = () => {
  if (isNotifEnabled == 1) {
    toggleNotifications.innerHTML = "DISABLE NOTIFICATIONS";
  } else {
    toggleNotifications.innerHTML = "ENABLE NOTIFICATIONS";
  }
};
