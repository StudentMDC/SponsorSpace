const produit = document.querySelectorAll(".product");
const arrayproduit = Array.from(produit);
const cprd = document.querySelector("form #crpd");
const prd = document.createElement("span");
const valider = document.querySelector(".form #valider");

var nbrPrd = 0;

const myPromise = new Promise((resolve, reject) => {

  valider.addEventListener("mousedown", () => {
  // Resolve the promise when the "Valider" button is clicked
    resolve();
  });
  arrayproduit.forEach((element) => {
    element.addEventListener("click", (eo) => {
      console.log(element);
      nbrPrd++;
      globalThis.nbrPrd = nbrPrd;

      prd.innerHTML = "Produits";
      prd.style.display = "block";
      prd.style.marginBottom = "15px";
      cprd.prepend(prd);

      var prdid = document.createElement("input");
      prdid.type = "number";
      prdid.name = "I[]";
      prdid.classList.add("dn");
      prdid.value = element.children[1].getAttribute("data-idprod");
      cprd.append(prdid);

      var prdinput = document.createElement("input");
      prdinput.type = "text";
      prdinput.name = "P[]";
      prdinput.disabled = true;
      prdinput.style.border = "1px solid #ccc";
      prdinput.value = element.children[1].textContent;
      cprd.append(prdinput);

      var qteinput = document.createElement("input");
      qteinput.type = "number";
      qteinput.name = "QP[]";
      qteinput.placeholder = "Saisir la quantité désirée";
      qteinput.classList.add("nprd");
      cprd.append(qteinput);

      
    });
  });
});

myPromise.then(
  (params) => {
    var prdnbr = document.createElement("input");
    prdnbr.type = "number";
    prdnbr.name = "nbrPrd";
    prdnbr.classList.add("dn");
    prdnbr.value = nbrPrd;
    cprd.append(prdnbr);
  }
)
