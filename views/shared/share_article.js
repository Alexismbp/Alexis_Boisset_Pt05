// Alexis Boisset
document.addEventListener("DOMContentLoaded", () => {
  const shareButton = document.getElementById("share-article-btn");

  function showAlert(message) {
    const alert = document.createElement("div");
    alert.className = "custom-alert";
    alert.textContent = message;
    document.body.appendChild(alert);

    setTimeout(() => {
      alert.style.animation = "slideIn 0.3s ease-out reverse";
      setTimeout(() => alert.remove(), 300);
    }, 3000);
  }

  shareButton.addEventListener("click", () => {
    const showTitle = document.getElementById("show-title").checked;
    const showContent = document.getElementById("show-content").checked;

    // Validar que al menos un checkbox esté seleccionado
    if (!showTitle && !showContent) {
      showAlert(
        "Has de seleccionar almenys un camp per compartir (títol o contingut)"
      );
      return;
    }

    const articleId = shareButton.dataset.articleId;
    const matchId = shareButton.dataset.matchId;

    fetch(`${BASE_URL}ajax/share_article.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        article_id: articleId,
        match_id: matchId,
        show_title: showTitle ? 1 : 0,
        show_content: showContent ? 1 : 0,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          document.getElementById("qr-code").src = data.qr;
          document.getElementById("share-url").textContent = data.url;
          document.getElementById("share-url").href = data.url;
          document.getElementById("share-result").style.display = "block";
        } else {
          alert(`Error: ${data.error}`);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Ha ocurrido un error al compartir el artículo.");
      });
  });
});
