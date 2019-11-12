// Some variable about DOM
const left = document.getElementById("left");
const right = document.getElementById("right");
const arrow = document.getElementById("arrow");
const loading = document.getElementById("loading");
const content = document.getElementById("content");
const inside = document.getElementById("inside");
const button = document.getElementById("btn-form");
const searchResult = document.getElementById("livesearch");
const lastLink = document.getElementById("lastlink");
const searchBar = document.getElementById("search");
const result = document.getElementById("result");
const addDiv = document.getElementById("add");
const addform = document.getElementById("addform");
const addplus = document.getElementById("addplus");
let spanLinks = document.getElementsByClassName("tags");

if (spanLinks) {
  for (let link of spanLinks) {
    link.addEventListener("click", function() {
      searchBar.value = "";
      console.log(link.textContent);
      showResult(link.textContent);
    });
  }
}

function newSearch(str) {
  searchBar.value = "";
  showResult(str);
}

// Animation when you click on the arrow button
document.getElementById("btn-move").addEventListener("click", function() {
  if (left.className === "left") {
    left.classList.add("desactivate");
    right.classList.add("active");
    addDiv.classList.add("hidden");
    arrow.style.transform = "rotate(0deg)";
    loading.style.transform = "translateX(300vw)";
    content.style.transform = "translateY(0)";
  } else {
    left.classList.remove("desactivate");
    right.classList.remove("active");
    addDiv.classList.remove("hidden");
    arrow.style.transform = "rotate(180deg)";
    loading.style.transform = "translateX(0)";
    content.style.transform = "translateY(-300vh)";
  }
});

// Animation on click on plus button
addplus.addEventListener("click", function() {
  if (!addplus.classList.contains("close")) {
    addplus.classList.add("close");
    addplus.style.transform = "rotate(0deg)";
    addform.classList.remove("clicked");
  } else {
    addplus.classList.remove("close");
    addplus.style.transform = "rotate(45deg)";
    addform.classList.add("clicked");
  }
});

// Looking for a link
function showResult(str) {
  console.log(str);
  if (str.length <= 2) {
    if ($("#result h2")) {
      $("#result h2").remove();
    }
    searchResult.innerHTML = "";
    if (!result.classList.contains("hidden")) {
      result.classList.add("hidden");
    }
    return;
  }

  $.get("/lookingfor", { search: str }, function(data) {
    result.classList.remove("hidden");
    if (data !== "error") {
      if ($("#result h2")) {
        $("#result h2").remove();
        $("#result").prepend("<h2>Voici les liens recherchés</h2>");
      }
      searchResult.innerHTML = "";
      const resultat = JSON.parse(data);
      for (let [key, value] of Object.entries(resultat)) {
        searchResult.innerHTML += `<li><a href="${value.url}">${value.title}</a>${value.description}<span class="tags" onclick="newSearch('${value.tag}')">${value.tag}</span></li>`;
        spanLinks = document.getElementsByClassName("tags");
      }
    } else {
      searchResult.innerHTML = `Aucun resultat`;
    }
  });
}

// Add a new link
function addLink() {
  const title = document.getElementById("title").value;
  const url = document.getElementById("url").value;
  const description = document.getElementById("description").value;
  const tag = document.getElementById("tag").value;

  $.post(
    "/newlink",
    { title: title, url: url, description: description, tag: tag },
    function(data) {
      if (data !== "error") {
        lastLink.innerHTML = "";
        const resultat = JSON.parse(data);
        for (let [key, value] of Object.entries(resultat)) {
          lastLink.innerHTML += `<li><a href="${value.url}">${value.title}</a>${value.description}<span class="tags">${value.tag}</span></li>`;
        }
        document.getElementById("form-link").reset();
      } else {
        alert("Une erreur s'est produite!");
      }
    }
  );
}
