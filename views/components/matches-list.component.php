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