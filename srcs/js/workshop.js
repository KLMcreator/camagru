// webcam
const player = document.getElementById("snapshotWebcam");
const constraints = { audio: false, video: { width: 420, height: 320 } };
// snapshot render
const canvas = document.getElementById("displaySnapshot");
const context = canvas.getContext("2d");
// filter render
const canvasFilter = document.getElementById("displayFilter");
const contextFilter = canvasFilter.getContext("2d");
// import render
const canvasImport = document.getElementById("displayImportSnapshot");
const contextImport = canvasImport.getContext("2d");
// live render
const canvasLiveFilter = document.getElementById("displayLiveFilter");
const contextLiveFilter = canvasLiveFilter.getContext("2d");

// control buttons
const sizeUp = document.getElementById("sizeUp");
const sizeDown = document.getElementById("sizeDown");
const captureButton = document.getElementById("takeSnapshot");
const importSnapshot = document.getElementById("importSnapshot");
const toggleEdit = document.getElementById("toggleEdit");
const saveSnapshot = document.getElementById("saveSnapshot");
const clearImport = document.getElementById("clearImport");

let isStreaming;

// filter variables
let posX = 0;
let posY = 0;
let layerWidth = 150;
let layerHeight = 150;
let imgObject = new Image();

// Flip image for a mirror effect
context.translate(canvas.width, 0);
context.scale(-1, 1);

const selectLayer = (dir, id) => {
  const oldSelected = document.getElementsByClassName("selected");
  if (isStreaming || importSnapshot.value != "") {
    document.getElementById("takeSnapshot").removeAttribute("disabled");
  }
  document.getElementById("sizeUp").removeAttribute("disabled");
  document.getElementById("sizeDown").removeAttribute("disabled");
  if (oldSelected[0]) {
    oldSelected[0].classList.remove("selected");
    posX = 0;
    posY = 0;
    layerWidth = 150;
    layerHeight = 150;
  }
  const newSelected = document.getElementById(id);
  newSelected.classList.add("selected");
  if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
    clearContext(contextLiveFilter, canvas);
    imgObject.onload = () => {
      contextLiveFilter.drawImage(
        imgObject,
        posX,
        posY,
        layerWidth,
        layerHeight
      );
    };
  } else {
    clearContext(contextFilter, canvasFilter);
    imgObject.onload = () => {
      contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
    };
  }
  imgObject.src = dir;
};

// Clear a canvas by his name and context
const clearContext = (contextName, canvasName) => {
  contextName.save();
  contextName.setTransform(1, 0, 0, 1, 0, 0);
  contextName.clearRect(0, 0, canvasName.width, canvasName.height);
  contextName.restore();
};

clearImport.addEventListener("click", e => {
  clearImport.setAttribute("disabled", true);
  clearContext(contextFilter, canvasFilter);
  clearContext(contextLiveFilter, canvas);
  clearContext(context, canvas);
  clearContext(contextImport, canvas);
  importSnapshot.value = "";
  navigator.mediaDevices
    .getUserMedia(constraints)
    .then(stream => {
      isStreaming = stream;
      player.srcObject = stream;
    })
    .catch(function(err) {
      /* handle the error */
    });
});

// Check if there's something imported or if it changes then draw in canvas
importSnapshot.addEventListener("change", e => {
  if (isStreaming) {
    isStreaming.getVideoTracks()[0].stop();
  }
  clearImport.removeAttribute("disabled");
  let reader = new FileReader();
  reader.onload = function(event) {
    let img = new Image();
    img.onload = function() {
      contextImport.drawImage(img, 0, 0, canvas.width, canvas.height);
    };
    img.src = event.target.result;
  };
  reader.readAsDataURL(e.target.files[0]);
});

// On Capture Event, clear then draw then flip for the filter to be in the same position/angle and re flip
captureButton.addEventListener("click", () => {
  document.getElementById("toggleEdit").removeAttribute("disabled");
  document.getElementById("saveSnapshot").removeAttribute("disabled");
  document.getElementById("renderTitle").style.display = "block";
  document.getElementById("snapshotRender").style.display = "block";
  clearContext(contextFilter, canvasFilter);
  clearContext(context, canvas);
  if (importSnapshot.files.length === 0) {
    context.drawImage(player, 0, 0, canvas.width, canvas.height);
  } else {
    context.translate(canvas.width, 0);
    context.scale(-1, 1);
    context.drawImage(canvasImport, 0, 0, canvas.width, canvas.height);
    context.translate(canvas.width, 0);
    context.scale(-1, 1);
  }
  context.translate(canvas.width, 0);
  context.scale(-1, 1);
  contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
  context.translate(canvas.width, 0);
  context.scale(-1, 1);
});

// Merge snapshot with filter (allow edit after capture) and save
saveSnapshot.addEventListener("click", () => {
  let canvasURL = canvas.toDataURL();
  let canvasURLFilter = canvasFilter.toDataURL();
  let fullDate = new Date().toLocaleString();
  let fullTimeStamp = Date.now();
  let xmlhttp = new XMLHttpRequest();
  let confirmedRand = Math.floor(
    Math.random() * (144202442 - 9224042249 + 1) + 144202442
  );
  lastId ? lastId++ : (lastId = 1);
  xmlhttp.open("POST", "./../actions/saveSnapshot.php");
  xmlhttp.setRequestHeader("Content-Type", "application/json");
  xmlhttp.send(
    JSON.stringify({
      dir: canvasURL,
      layer: canvasURLFilter,
      layerWidth: layerWidth,
      confirmedRand: confirmedRand,
      posX: posX,
      posY: posY,
      id_user: userId,
      id_username: userLogin,
      id_mail: userMail,
      filter_name: imgObject.src,
      date: fullDate,
      timestamp: fullTimeStamp
    })
  );
  //   get return in console.log
  xmlhttp.onreadystatechange = () => {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.response === "TRUE") {
        clearContext(contextFilter, canvasFilter);
        clearContext(context, canvas);
        let snapshotDiv = document.createElement("div");
        snapshotDiv.classList.add("snapshot");
        let snapshotA = document.createElement("a");
        snapshotA.href = "./snapshot.php?id=" + lastId;
        let snapshotCardDiv = document.createElement("div");
        snapshotCardDiv.classList.add("snapshotCard");
        snapshotCardDiv.classList.add("card");
        snapshotCardDiv.classList.add("is-shadowless");
        let snapshotCardImage = document.createElement("div");
        snapshotCardImage.classList.add("card-image");
        let snapshotFigureImage = document.createElement("figure");
        snapshotFigureImage.classList.add("image");
        let snapshotImage = document.createElement("img");
        snapshotImage.id = "snapshot" + lastId;
        snapshotImage.src =
          "./../assets/snapshots/" +
          userId +
          "" +
          confirmedRand +
          "" +
          fullTimeStamp +
          ".png";
        snapshotImage.alt = "snapshot" + lastId;
        snapshotImage.style.borderTopLeftRadius = "7px";
        snapshotImage.style.borderTopRightRadius = "7px";
        let snapshotContent = document.createElement("div");
        snapshotContent.classList.add("snapshotLegend");
        let snapshotAuthor = document.createElement("div");
        let snapshotDate = document.createElement("div");
        snapshotAuthor.classList.add("footerCard");
        snapshotAuthor.innerHTML = "@" + userLogin;
        snapshotDate.classList.add("footerCard");
        snapshotDate.innerHTML = fullDate;
        snapshotDiv.appendChild(snapshotA);
        snapshotA.appendChild(snapshotCardDiv);
        snapshotCardDiv.appendChild(snapshotCardImage);
        snapshotCardImage.appendChild(snapshotFigureImage);
        snapshotFigureImage.appendChild(snapshotImage);
        snapshotCardDiv.appendChild(snapshotContent);
        snapshotContent.appendChild(snapshotAuthor);
        snapshotContent.appendChild(snapshotDate);
        document
          .getElementById("scrollableGallery")
          .insertBefore(
            snapshotDiv,
            document.getElementById("scrollableGallery").childNodes[2]
          );
      }
    }
  };
  document.getElementById("sizeUp").setAttribute("disabled", true);
  document.getElementById("sizeDown").setAttribute("disabled", true);
  document.getElementById("toggleEdit").setAttribute("disabled", true);
  document.getElementById("saveSnapshot").setAttribute("disabled", true);
  document.getElementById("renderTitle").style.display = "none";
  document.getElementById("snapshotRender").style.display = "none";
});

// Toggle edit scenes between live and captured
toggleEdit.addEventListener("click", () => {
  if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
    toggleEdit.innerHTML = "EDIT LIVE";
  } else {
    toggleEdit.innerHTML = "EDIT SNAPSHOT";
  }
});

// Size up and size down
sizeUp.addEventListener("click", () => {
  layerWidth = layerWidth + 5;
  layerHeight = layerHeight + 5;
  if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
    clearContext(contextLiveFilter, canvas);
    contextLiveFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
  } else {
    clearContext(contextFilter, canvasFilter);
    contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
  }
});

sizeDown.addEventListener("click", () => {
  layerWidth = layerWidth - 5;
  layerHeight = layerHeight - 5;
  if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
    clearContext(contextLiveFilter, canvas);
    contextLiveFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
  } else {
    clearContext(contextFilter, canvasFilter);
    contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
  }
});

// Move filter with arrow keys
document.onkeydown = function(e) {
  if ([37, 38, 39, 40].indexOf(e.keyCode) > -1) {
    e.preventDefault();
    if (e.keyCode === 37) {
      posX = posX - 5;
      if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
        clearContext(contextLiveFilter, canvas);
        contextLiveFilter.drawImage(
          imgObject,
          posX,
          posY,
          layerWidth,
          layerHeight
        );
      } else {
        clearContext(contextFilter, canvasFilter);
        contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
      }
    } else if (e.keyCode === 38) {
      posY = posY - 5;
      if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
        clearContext(contextLiveFilter, canvas);
        contextLiveFilter.drawImage(
          imgObject,
          posX,
          posY,
          layerWidth,
          layerHeight
        );
      } else {
        clearContext(contextFilter, canvasFilter);
        contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
      }
    } else if (e.keyCode === 39) {
      posX = posX + 5;
      if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
        clearContext(contextLiveFilter, canvas);
        contextLiveFilter.drawImage(
          imgObject,
          posX,
          posY,
          layerWidth,
          layerHeight
        );
      } else {
        clearContext(contextFilter, canvasFilter);
        contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
      }
    } else if (e.keyCode === 40) {
      posY = posY + 5;
      if (toggleEdit.innerHTML === "EDIT SNAPSHOT") {
        clearContext(contextLiveFilter, canvas);
        contextLiveFilter.drawImage(
          imgObject,
          posX,
          posY,
          layerWidth,
          layerHeight
        );
      } else {
        clearContext(contextFilter, canvasFilter);
        contextFilter.drawImage(imgObject, posX, posY, layerWidth, layerHeight);
      }
    }
  }
};

// Stream the camera
navigator.mediaDevices
  .getUserMedia(constraints)
  .then(stream => {
    isStreaming = stream;
    player.srcObject = stream;
  })
  .catch(function(err) {
    /* handle the error */
  });

// arrows
const leftPaddle = document.getElementById("leftPaddle");
const rightPaddle = document.getElementById("rightPaddle");
// main div
const menuWrapper = document.getElementById("menuWrapper");
// menu
const scrollingMenu = document.getElementById("scrollingMenu");
// items
const scrollingMenuItem = document.getElementById("scrollingMenuItem");
const classScrollingMenuItem = document.querySelectorAll(".scrollingMenuItem");
// sz
const itemsLength = classScrollingMenuItem.length;
const itemSize = 150;
const paddleMargin = 20;

const getMenuWrapperSize = () => {
  return menuWrapper.offsetWidth;
};

// resize wrapper on resize window
window.addEventListener("resize", () => {
  menuWrapperSize = getMenuWrapperSize();
});

let getMenuSize = () => {
  return itemsLength * itemSize;
};

let menuWrapperSize = getMenuWrapperSize();
let menuVisibleSize = menuWrapperSize;
let menuSize = getMenuSize();
let menuInvisibleSize = menuSize - menuWrapperSize;

let getMenuPosition = function() {
  return scrollingMenu.scrollLeft;
};

// onscroll event
scrollingMenu.addEventListener("scroll", () => {
  menuInvisibleSize = menuSize - menuWrapperSize;
  let menuPosition = getMenuPosition();
  let menuEndOffset = menuInvisibleSize - paddleMargin;

  if (menuPosition <= paddleMargin) {
    leftPaddle.classList.add("hidden");
    rightPaddle.classList.remove("hidden");
  } else if (menuPosition < menuEndOffset) {
    leftPaddle.classList.remove("hidden");
    rightPaddle.classList.remove("hidden");
  } else if (menuPosition >= menuEndOffset) {
    leftPaddle.classList.remove("hidden");
    rightPaddle.classList.add("hidden");
  }
});

// move left
leftPaddle.addEventListener("click", () => {
  scrollingMenu.scrollLeft -= 250;
});

// move right
rightPaddle.addEventListener("click", () => {
  scrollingMenu.scrollLeft += 250;
});
