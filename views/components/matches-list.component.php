<div class="matches-list">
    <?php foreach ($partits as $partit): ?>
        <div class="match-card">
            <p class="teams">
                <?php echo htmlspecialchars($partit['equip_local']); ?> vs <?php echo htmlspecialchars($partit['equip_visitant']); ?>
            </p>
            <?php if ($partit['jugat']): ?>
                <p class="score">
                    <?php echo $partit['gols_local']; ?> - <?php echo $partit['gols_visitant']; ?>
                </p>
            <?php else: ?>
                <p class="match-date">
                    Fecha: <?php echo date('d/m/Y', strtotime($partit['data'])); ?>
                </p>
            <?php endif; ?>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <?php include BASE_PATH . 'views/components/match-actions.component.php'; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php if ($totalPages > 1): ?>
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?><?php echo isset($_GET['lliga']) ? '&lliga=' . htmlspecialchars($_GET['lliga']) : ''; ?><?php echo isset($_GET['partitsPerPage']) ? '&partitsPerPage=' . htmlspecialchars($_GET['partitsPerPage']) : ''; ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo isset($_GET['lliga']) ? '&lliga=' . htmlspecialchars($_GET['lliga']) : ''; ?><?php echo isset($_GET['partitsPerPage']) ? '&partitsPerPage=' . htmlspecialchars($_GET['partitsPerPage']) : ''; ?>"
               <?php echo ($currentPage == $i) ? 'class="active"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?><?php echo isset($_GET['lliga']) ? '&lliga=' . htmlspecialchars($_GET['lliga']) : ''; ?><?php echo isset($_GET['partitsPerPage']) ? '&partitsPerPage=' . htmlspecialchars($_GET['partitsPerPage']) : ''; ?>">Siguiente</a>
        <?php endif; ?>
    <?php endif; ?>
</div>