<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/styles/modal.css">
<meta name="base-url" content="<?php echo BASE_URL; ?>">
<script src="<?php echo BASE_URL; ?>scripts/modal.js" defer></script>
<dialog data-modal class="modal">
    <div class="modal-header">
        <div class="modal-title">Compartir</div>
        <button data-close-button class="button-close" id="close">&times;</button>
    </div>
    <div class="modal-body">
        <div class="modal-form">
            <p>Selecciona els camps que vols compartir:</p>
            <form id="share-form">
                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                <input type="hidden" name="match_id" value="<?php echo $article['match_id']; ?>">
            
                <label for="titol">Compartir titol: </label>
                <input type="checkbox" name="titol" id="titol">
                
                <!-- Mostrar titol actual -->
                <span class="form-field-display"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                <br>
                
                <label for="cos">Compartir cos: </label>
                <input type="checkbox" name="cos" id="cos">

                <!-- Mostrar cos actual -->
                <span class="form-field-display"><?php echo htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8'); ?></span>
                <input type="submit" value="Compartir">
            </form>
        </div>
        <div class="modal-qr" id="qr-code">
            <!-- QR se cargará aquí -->
        </div>
    </div>
</dialog>
<div id="modal-overlay" data-overlay></div>