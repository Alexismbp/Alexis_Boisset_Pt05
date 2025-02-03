<!-- Alexis Boisset -->
<?php
if (!isset($totalPages)) {
    $totalPages = 1;
}
if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>&orderBy=<?php echo $_GET['orderBy'] ?? 'date_desc'; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>&orderBy=<?php echo $_GET['orderBy'] ?? 'date_desc'; ?>"
                class="<?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>&orderBy=<?php echo $_GET['orderBy'] ?? 'date_desc'; ?>">Següent &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>