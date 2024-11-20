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
    </form>
</div>