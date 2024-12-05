<!-- Alexis Boisset -->
<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
    <div class="league-selector">
        <form action="<?php echo BASE_URL; ?>/" method="GET">
            <label for="lliga">Selecciona una liga:</label>
            <select name="lliga" id="lliga" onchange="this.form.submit()">
                <?php foreach ($lligues as $lliga): ?>
                    <option value="<?php echo $lliga; ?>" <?php echo ($lliga === $lligaSeleccionada) ? 'selected' : ''; ?>>
                        <?php echo $lliga; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
<?php else: ?>
    <label for="lliga">Lliga seleccionada:</label>
    <select id="lliga" name="lliga" disabled>
        <option value="<?php echo $lligaSeleccionada; ?>" selected><?php echo $lligaSeleccionada; ?></option>
    </select>
<?php endif; ?>