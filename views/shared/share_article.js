document.addEventListener("DOMContentLoaded", () => {
  const shareButton = document.getElementById("share-article-btn");
  shareButton.addEventListener("click", () => {
    const showTitle = document.getElementById("show-title").checked;
    const showContent = document.getElementById("show-content").checked;

    // Validar que al menos uno esté seleccionado
    if (!showTitle && !showContent) {
      alert(
        "Debes seleccionar al menos el título o el contenido para compartir"
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
        titol: showTitle ? 1 : 0,
        cos: showContent ? 1 : 0,
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
