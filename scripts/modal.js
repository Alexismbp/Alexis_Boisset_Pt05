/**
 * Gestió del modal per compartir contingut mitjançant codis QR
 *
 * @author Alexis Boisset
 *
 * Funcionalitats principals:
 * - Obertura i tancament del modal
 * - Gestió del formulari de compartició
 * - Validació de les opcions seleccionades
 * - Generació i visualització del codi QR
 * - Gestió d'errors en la generació del QR
 *
 * Elements principals:
 * - Modal amb formulari de compartició
 * - Overlay per enfosquir el fons
 * - Contenidor per mostrar el codi QR generat
 * - Botons d'obertura i tancament del modal
 */

// Alexis Boisset
document.addEventListener("DOMContentLoaded", () => {
  const openModalButton = document.querySelector("[data-modal-target]");
  const closeModalButtons = document.querySelectorAll("[data-close-button]");
  const overlay = document.querySelector("[data-overlay]");
  const form = document.getElementById("share-form");
  const qrCodeContainer = document.getElementById("qr-code");
  const base_url = document
    .querySelector('meta[name="base-url"]')
    .getAttribute("content");

  openModalButton.addEventListener("click", () => {
    const modal = document.querySelector("[data-modal]");
    modal.showModal();
    overlay.classList.add("active");
  });

  closeModalButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const modal = button.closest("dialog");
      modal.close();
      overlay.classList.remove("active");
    });
  });

  overlay.addEventListener("click", () => {
    const modal = document.querySelector("dialog[open]");
    if (modal) {
      modal.close();
      overlay.classList.remove("active");
    }
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Verificar checkboxes
    const titolChecked = form.querySelector('input[name="titol"]').checked;
    const cosChecked = form.querySelector('input[name="cos"]').checked;

    if (!titolChecked && !cosChecked) {
      qrCodeContainer.innerHTML =
        '<p class="error">Has de seleccionar almenys una opció per compartir</p>';
      return;
    }

    const formData = new FormData(form);

    fetch(`${base_url}controllers/utils/qr.php`, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success && data.qr) {
          qrCodeContainer.innerHTML = `
                    <img src="${data.qr}" alt="QR Code" style="max-width: 200px;">
                    <p>URL: <a href="${data.url}" target="_blank">${data.url}</a></p>
                `;
        } else {
          throw new Error(data.error || "Error al generar el código QR");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        qrCodeContainer.innerHTML = `<p class="error">Error: ${error.message}</p>`;
      });
  });
});
