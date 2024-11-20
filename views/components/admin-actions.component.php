
<div class="admin-actions">
    <h3>Acciones administrativas</h3>
    <div class="action-buttons">
        <a href="<?php echo BASE_URL; ?>partido/crear" class="button">Crear nuevo partido</a>
        <a href="<?php echo BASE_URL; ?>equipo/gestionar" class="button">Gestionar equipos</a>
        <a href="<?php echo BASE_URL; ?>liga/gestionar" class="button">Gestionar ligas</a>
    </div>
</div>

<style>
.admin-actions {
    margin: 20px 0;
    padding: 15px;
    background-color: #f5f5f5;
    border-radius: 5px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.button:hover {
    background-color: #0056b3;
}
</style>