/**
 * Script per gestionar la lectura de codis QR
 *
 * @author Alexis Boisset
 *
 * Aquest fitxer conté la lògica per:
 * - Capturar l'enviament del formulari de lectura QR
 * - Enviar la imatge del codi QR al servidor
 * - Mostrar el resultat de la lectura
 * - Gestionar el botó per anar a l'enllaç descodificat
 */

// Alexis Boisset
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("qr-form");
  const goToLinkBtn = document.getElementById("go-to-link");
  const qrOutput = document.getElementById("qr-output");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    fetch(BASE_URL + "qr-read", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          qrOutput.value = data.data;
          goToLinkBtn.style.display = "inline-block";
        } else {
          qrOutput.value = "Error: " + data.error;
          goToLinkBtn.style.display = "none";
        }
      })
      .catch((error) => {
        qrOutput.value = "Error: " + error;
        goToLinkBtn.style.display = "none";
      });
  });

  goToLinkBtn.addEventListener("click", () => {
    const url = qrOutput.value;
    if (url) {
      window.location.href = url;
    }
  });
});
