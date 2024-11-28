
<?php
if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>" 
               class="<?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?>&lliga=<?php echo $lligaSeleccionada; ?>&partitsPerPage=<?php echo $partitsPerPage; ?>">Següent &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>