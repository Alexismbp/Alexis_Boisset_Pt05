<!-- Alexis Boisset -->
<!-- Botons per editar i eliminar partit que es mostren a cada partit -->
<div class="match-actions">
    <a href="<?php echo BASE_URL; ?>edit-match/<?php echo $partit['id']; ?>" class="btn-edit">Editar</a>
    <form action="<?php echo BASE_URL; ?>delete-match" method="POST" class="delete-form" onsubmit="return confirmDelete(event)">
        <input type="hidden" name="partit_id" value="<?php echo $partit['id']; ?>">
        <button type="submit" class="btn-delete">Eliminar</button>
    </form>
</div>

<script>
    // Confirmar eliminació, si no confima no s'enviarà el formulari (event.preventDefault())
    function confirmDelete(event) {
        if (!confirm('¿Estás seguro de que quieres eliminar este partido?')) {
            event.preventDefault();
        }
    }
</script>