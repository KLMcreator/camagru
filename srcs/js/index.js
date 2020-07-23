const encodeHTML = s => {
  return s
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/＂/g, "&#65282;")
    .replace(/＇/g, "&#65287;")
    .replace(/`/g, "&#96;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&apos;");
};

const updateLike = (id, isLiked) => {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "./srcs/actions/updateLike.php");
  xmlhttp.setRequestHeader("Content-Type", "application/json");
  xmlhttp.send(
    JSON.stringify({
      addLike: !isLiked,
      id_user: userId,
      type_element: "snapshot",
      id_element: id,
      date: new Date().toLocaleString()
    })
  );
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.response === "TRUE") {
        isLiked = !isLiked;
        document.getElementById("likeButton" + id).innerHTML =
          isLiked === true ? "UNLIKE" : "LIKE";
        document
          .getElementById("likeButton" + id)
          .setAttribute("onclick", "updateLike(" + id + "," + isLiked + ")");
      }
    }
  };
};

const sendComment = id => {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "./srcs/actions/sendComment.php");
  xmlhttp.setRequestHeader("Content-Type", "application/json");
  let contentComment = document.getElementById("newComment" + id).value;
  xmlhttp.send(
    JSON.stringify({
      name_user: userName,
      id_image: id,
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
        let renderContent = document.createElement("div");
        renderContent.innerHTML = userName + ": " + contentComment;
        renderContent.classList.add("divComment");
        document
          .getElementById("commentSection" + id)
          .insertBefore(
            renderContent,
            document.getElementById("commentSection" + id).childNodes[0]
          );
        if (
          document.getElementById("commentSection" + id).childNodes.length > 3
        ) {
          document
            .getElementById("commentSection" + id)
            .removeChild(
              document.getElementById("commentSection" + id).lastElementChild
            );
        }
        document.getElementById("newComment" + id).value = "";
      }
    }
  };
};
