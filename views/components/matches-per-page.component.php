<div class="matches-per-page">
    <form action="<?php echo BASE_URL; ?>/" method="GET" class="form-partits-per-page">
        <?php if (isset($_GET['lliga'])): ?>
            <input type="hidden" name="lliga" value="<?php echo htmlspecialchars($_GET['lliga']); ?>">
        <?php endif; ?>
        
        <label for="partitsPerPage">Partits per pàgina:</label>
        <select name="partitsPerPage" id="partitsPerPage" onchange="this.form.submit()">
            <?php foreach ($partidosPerPageOptions as $option): ?>
                <option value="<?php echo $option; ?>" <?php echo ($option == $partitsPerPage) ? 'selected' : ''; ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="orderBy">Ordenar per:</label>
        <select name="orderBy" id="orderBy" onchange="this.form.submit()">
            <option value="date_asc" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == 'date_asc') ? 'selected' : ''; ?>>Data ↑</option>
            <option value="date_desc" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == 'date_desc') ? 'selected' : ''; ?>>Data ↓</option>
            <option value="name_asc" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == 'name_asc') ? 'selected' : ''; ?>>Equip Local A-Z</option>
            <option value="name_desc" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == 'name_desc') ? 'selected' : ''; ?>>Equip Local Z-A</option>
        </select>
    </form>
</div>