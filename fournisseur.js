const boutton = document.getElementById("button");
const boutondiv = document.getElementById("boutondiv");
const dialog = document.querySelector("dialog");
// const down = document.getElementById("down");

let index1 = 2;
let inputsremplis = true;
boutton.addEventListener("click", (eo) => {
  const allnewinputs = document.querySelectorAll(".nouveauinput");
  allnewinputs.forEach((element) => {
    if (element.value === "") {
      inputsremplis = false;
      return;
    } else {
      inputsremplis = true;
      return;
    }
  });
  if (!inputsremplis) {
    dialog.showModal();
    setTimeout(() => dialog.close(), 1300);
  }
  if (inputsremplis) {
    const newDiv = document.createElement("div");
    newDiv.innerHTML = `<p class="prdp">${"Produit " + index1}</p>
    <input type="text" name="P[]"  class="nouveauinput"></input>
    <p class="prdp">${"Désignation produit " + index1}</p>
    <input type="text" name="D[]"  class="nouveauinput"></input>
    <p class="prdp">${"Prix unitaire en DH du produit " + index1}</p>
    <input type="number" name="PU[]" class="nouveauinput nprd"></input> <br>`;
    boutondiv.appendChild(newDiv);
    index1 += 1;
  }
});