const toggleLike = document.getElementById("likeButton");
const commentSection = document.getElementById("commentSection");
const sendComment = document.getElementById("sendComment");
const deleteSnapshot = document.getElementById("deleteSnapshot");
let i = 0;

Share = {
  facebook: function(isUrl) {
    url = "https://www.facebook.com/sharer.php?";
    url += "&u=" + encodeURIComponent(isUrl);
    Share.popup(url);
  },
  twitter: function(isUrl, text) {
    url = "http://twitter.com/share?";
    url += "text=" + encodeURIComponent(text);
    url += "&url=" + encodeURIComponent(isUrl);
    Share.popup(url);
  },
  popup: function(url) {
    window.open(url, "");
  }
};

const shareLink = media => {
  if (media === "facebook") {
    url = "http://www.facebook.com/sharer.php?s=100";
    url += "&p[title]=" + encodeURIComponent(ptitle);
    url += "&p[summary]=" + encodeURIComponent(text);
    url += "&p[url]=" + encodeURIComponent(purl);
    url += "&p[images][0]=" + encodeURIComponent(pimg);
    Share.popup(url);
  } else if (media === "twitter") {
  }
};

// check if logged avoir error msg console
if (userId) {
  // double check
  if (galleryName === userName) {
    // delete snapshot and related infos
    deleteSnapshot.addEventListener("click", () => {
      if (confirm("Are you sure you want to delete this snapshot?")) {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "./../actions/deleteSnapshot.php");
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.send(
          JSON.stringify({
            idSnapshot: idSnapshot,
            userId: userId,
            userName: userName,
            confirm: "OK"
          })
        );
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.response === "TRUE") {
              window.location.replace("./../../index.php");
            }
          }
        };
      } else {
        // Do nothing!
      }
    });
  }

  // ajax like
  toggleLike.addEventListener("click", () => {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "./../actions/updateLike.php");
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(
      JSON.stringify({
        addLike: !isLiked,
        id_user: userId,
        type_element: "snapshot",
        id_element: idSnapshot,
        date: new Date().toLocaleString()
      })
    );
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.response === "TRUE") {
          isLiked = !isLiked;
          nbLikes += isLiked === true ? 1 : -1;
          toggleLike.innerHTML =
            isLiked === true ? nbLikes + " | UNLIKE" : nbLikes + " | LIKE";
        }
      }
    };
  });

  //encode variables
  function encodeHTML(s) {
    return s
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/＂/g, "&#65282;")
      .replace(/＇/g, "&#65287;")
      .replace(/`/g, "&#96;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&apos;");
  }

  // ajax comment
  sendComment.addEventListener("click", () => {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "./../actions/sendComment.php");
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    let contentComment = document.getElementById("newComment").value;
    xmlhttp.send(
      JSON.stringify({
        name_user: userName,
        id_image: idSnapshot,
        authorMail: authorMail,
        content: contentComment,
        date: new Date().toLocaleString()
      })
    );
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.response === "TRUE") {
          userName = encodeHTML(userName);
          authorMail = encodeHTML(authorMail);
          contentComment = encodeHTML(contentComment);
          i++;
          let renderContent = document.createElement("div");
          let renderComment = document.createElement("div");
          let deleteBtn = document.createElement("button");
          deleteBtn.setAttribute(
            "onclick",
            "deleteComment(" + i + ", " + idSnapshot + ", 'comment" + i + "');"
          );
          deleteBtn.classList.add("deleteSnapshot");
          deleteBtn.innerHTML = "X";
          renderContent.appendChild(deleteBtn);
          renderComment.innerHTML = contentComment;
          renderContent.id = "comment" + i;
          renderContent.style.padding = "1rem";
          renderContent.style.margin = "5px";
          renderContent.style.border = "1px solid #c9ada7";
          renderContent.classList.add("notification");
          renderComment.classList.add("commentContent");
          let renderAuthor = document.createElement("div");
          renderAuthor.innerHTML =
            userName + " | " + new Date().toLocaleString();
          renderAuthor.classList.add("commentTitle");
          renderContent.appendChild(renderAuthor);
          renderContent.appendChild(renderComment);
          commentSection.insertBefore(
            renderContent,
            commentSection.childNodes[4]
          );
          document.getElementById("newComment").value = "";
        }
      }
    };
  });
}

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
        confirm: "OK"
      })
    );
    xmlhttp.onreadystatechange = function() {
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

// ajax refresh content
window.onload = () => {
  while (i < comments.length) {
    let renderContent = document.createElement("div");
    let renderComment = document.createElement("div");
    if (comments[i]["NAME_USER"] === userName) {
      let deleteBtn = document.createElement("button");
      deleteBtn.setAttribute(
        "onclick",
        "deleteComment(" +
          comments[i]["ID"] +
          ", " +
          comments[i]["ID_IMAGE"] +
          ", 'comment" +
          comments[i]["ID"] +
          "');"
      );
      deleteBtn.classList.add("deleteSnapshot");
      deleteBtn.innerHTML = "X";
      renderContent.appendChild(deleteBtn);
    }
    renderComment.innerHTML = comments[i]["CONTENT"];
    renderContent.id = "comment" + comments[i]["ID"];
    renderContent.style.padding = "1rem";
    renderContent.style.margin = "5px";
    renderContent.style.border = "1px solid #c9ada7";
    renderContent.classList.add("notification");
    renderContent.classList.add("contentHasDelete");
    renderComment.classList.add("commentContent");
    let renderAuthor = document.createElement("div");
    renderAuthor.innerHTML =
      comments[i]["NAME_USER"] + " | " + comments[i]["DATE"];
    renderAuthor.classList.add("commentTitle");
    renderContent.appendChild(renderAuthor);
    renderContent.appendChild(renderComment);
    commentSection.appendChild(renderContent);
    i++;
  }
  i = comments.length ? comments[0]["ID"] : 0;
  if (userId) {
    if (isLiked === true) {
      toggleLike.innerHTML = nbLikes + " | UNLIKE";
    } else {
      toggleLike.innerHTML = nbLikes + " | LIKE";
    }
  }
};
