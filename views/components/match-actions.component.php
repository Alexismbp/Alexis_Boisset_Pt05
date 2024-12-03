<div class="match-actions">
    <a href="<?php echo BASE_URL; ?>create-match?id=<?php echo $partit['id']; ?>&edit=true" class="btn-edit">Editar</a>
    <form action="<?php echo BASE_URL; ?>delete-match" method=" " class="delete-form">
        <input type="hidden" name="match_id" value="<?php echo $partit['id']; ?>">
        <button type="submit" class="btn-delete">Eliminar</button>
    </form>
</div>