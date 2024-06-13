const shuffleList = document.querySelector(".shuffle");
const shufflebuttons = document.querySelectorAll(".shuffle li");
const shufflebuttonsArray = Array.from(shufflebuttons);
const wrapper = document.querySelector(".wrapper");
const welcomeActivity = document.querySelectorAll(".wrapper p.activite");
const welcomeActivityArray = Array.from(welcomeActivity);
const regarderlist = document.querySelectorAll("boutonregarder");
const regarderlistArray = Array.from(regarderlist);
const welcome = document.querySelector(".welcome");
const dialog = document.querySelector("dialog");

let attributli = "";
let flagResultat = 0;
let flagDiv = 0;
let divresultatnontrouve = "";
let presultatnontrouve = "";
let h3resultatnontrouve = "";

welcomeActivityArray.forEach((element) => {
  element.parentElement.classList.add("dn");
  // le bouton regarder plus doit ne pas apparaitre
  regarderlistArray.forEach((item) => {
    item.classList.add("dn");
  });
});
shufflebuttonsArray.forEach((item) => {
  item.addEventListener("click", (eo) => {
    flagResultat = 0;
    // tout initialiser a non active au clik
    shufflebuttonsArray.forEach((btn) => {
      btn.classList.remove("active");
    });
    // tout initialiser a display none a chaque nouveau click
    welcomeActivityArray.forEach((element) => {
      element.parentElement.classList.add("dn");
      regarderlistArray.forEach((item) => {
        item.classList.add("dn");
      });
    });
    // Ajouter la classse active à li et stocker sa valeur
    item.classList.add("active");
    attributli = eo.target.getAttribute("data-activite");
    // Si le paragraph hidden de lactivite a le meme contenu que la li remove dn et on reconstitue le grid
    welcomeActivityArray.forEach((element) => {
      if (element.innerHTML.trim() === attributli.trim()) {
        flagResultat = 1;
        element.parentElement.classList.remove("dn");
        regarderlistArray.forEach((item) => {
          item.classList.remove("dn");
        });
      }
    });
    if (flagResultat === 0) {
      dialog.showModal();
      setTimeout(() => dialog.close(), 1300);
    }    
  });
});
