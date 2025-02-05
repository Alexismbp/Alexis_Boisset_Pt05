/**
 *
 * @author Alexis Boisset
 *
 * Script per gestionar els articles compartits
 * Aquest fitxer gestiona la visualització i actualització dels articles compartits,
 * incloent la generació de codis QR per cada article
 */

// Event listener principal que s'executa quan el DOM està completament carregat
document.addEventListener("DOMContentLoaded", function () {
  const articlesContainer = document.querySelector(
    ".shared-articles-container"
  );
  articlesContainer.style.transition = "opacity 0.3s ease";

  document
    .getElementById("update-shared-articles")
    .addEventListener("click", updateArticles);

  // Asignar listeners a los botones inicialmente
  assignQRListeners();
});

/**
 * Actualitza la llista d'articles mitjançant una crida AJAX
 * Fa una petició al servidor, actualitza el contingut i gestiona les transicions
 */
function updateArticles() {
  const articlesContainer = document.querySelector(
    ".shared-articles-container"
  );
  articlesContainer.style.opacity = 0;

  fetch(baseUrl + "ajax-shared-articles")
    .then((response) => response.text())
    .then((text) => {
      try {
        return JSON.parse(text);
      } catch (e) {
        throw new Error("Error parseando JSON: " + e.message);
      }
    })
    .then((data) => {
      if (data.error) {
        throw new Error(data.error);
      }
      let inner = "";
      data.forEach((article) => {
        inner += generateArticleHTML(article);
      });
      setTimeout(() => {
        articlesContainer.innerHTML = inner;
        articlesContainer.style.opacity = 1;
        // Reasignar el listener a los nuevos botones
        assignQRListeners();
      }, 300);
    })
    .catch((error) => {
      articlesContainer.innerHTML = `<p class="error">Error: ${error.message}</p>`;
      articlesContainer.style.opacity = 1;
    });
}

/**
 * Genera l'HTML per un article individual
 * @param {Object} article - Objecte amb les dades de l'article
 * @returns {string} HTML formatejat per l'article
 */
function generateArticleHTML(article) {
  return `
    <div class="shared-article-card">
        <h3>${article.article_title}</h3>
        <p><strong>Partido:</strong> ${article.equipo_local} vs ${
    article.equipo_visitante
  }</p>
        <p><strong>Data partit:</strong> ${article.data}</p>
        <p><strong>Mostrar Título:</strong> ${
          article.show_title ? "Sí" : "No"
        }</p>
        <p><strong>Mostrar Contenido:</strong> ${
          article.show_content ? "Sí" : "No"
        }</p>
        <p><small>Creado: ${article.created_at}</small></p>
        <a href="${baseUrl}shared/${
    article.token
  }?action=edit" class="btn">Dar de alta</a>
        <button class="btn-show-qr" data-token="${
          article.token
        }">Mostrar QR</button>
        <div class="qr-container" style="display:none; margin-top:10px;">
            <img src="" alt="QR Code" />
        </div>
    </div>
    `;
}

/**
 * Assigna els listeners als botons de QR
 * S'ha d'executar cada cop que s'actualitza el contingut
 */
function assignQRListeners() {
  document.querySelectorAll(".btn-show-qr").forEach((btn) => {
    btn.addEventListener("click", handleQRButtonClick);
  });
}

/**
 * Gestiona el clic als botons de QR
 * Fa una petició al servidor per generar el codi QR i el mostra/amaga
 */
function handleQRButtonClick() {
  const token = this.getAttribute("data-token");
  const qrContainer = this.nextElementSibling;
  const img = qrContainer.querySelector("img");

  const formData = new FormData();
  formData.append("token", token);

  fetch(baseUrl + "share", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error en la solicitud");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success && data.qr) {
        img.setAttribute("src", data.qr);
        qrContainer.style.display =
          qrContainer.style.display === "none" ? "block" : "none";
      } else {
        throw new Error(data.error || "Error al generar el código QR");
      }
    })
    .catch((error) => {
      console.error(error);
      alert(error.message);
    });
}
