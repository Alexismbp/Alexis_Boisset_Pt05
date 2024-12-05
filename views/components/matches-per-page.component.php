<!-- Alexis Boisset -->
<div class="matches-per-page">
    <form action="<?php echo BASE_URL; ?>/" method="GET" class="form-partits-per-page">
        <?php if (isset($_GET['lliga'])): ?>
            <input type="hidden" name="lliga" value="<?php echo htmlspecialchars($_GET['lliga']); ?>">
        <?php endif; ?>
        
        <!-- Partits per pàgina currat -->
        <label for="partitsPerPage">Partits per pàgina:</label>
        <select name="partitsPerPage" id="partitsPerPage" onchange="this.form.submit()">
            <?php foreach ($partidosPerPageOptions as $option): ?>
                <option value="<?php echo $option; ?>" <?php echo ($option == $partitsPerPage) ? 'selected' : ''; ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Order by chapucero -->
        <label for="orderBy">Ordenar per:</label>
        <select name="orderBy" id="orderBy" onchange="this.form.submit()">
            <option value="date_desc" <?php echo ($orderBy == 'date_desc') ? 'selected' : ''; ?>>Data (més recent)</option>
            <option value="date_asc" <?php echo ($orderBy == 'date_asc') ? 'selected' : ''; ?>>Data (més antiga)</option>
            <option value="name_asc" <?php echo ($orderBy == 'name_asc') ? 'selected' : ''; ?>>Nom (A-Z)</option>
            <option value="name_desc" <?php echo ($orderBy == 'name_desc') ? 'selected' : ''; ?>>Nom (Z-A)</option>
        </select>
    </form>
</div>