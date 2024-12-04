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

            <?php 
            $hasArticle = isset($partit['article_id']) && !empty($partit['article_id']);
            if ($hasArticle): ?>
                <div class="article-preview">
                    <h3><?php echo htmlspecialchars($partit['article_title']); ?></h3>
                    <?php if (!isset($_SESSION['loggedin']) || $partit['article_user_id'] == $_SESSION['userid']): ?>
                        <p><?php echo nl2br(htmlspecialchars($partit['article_content'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                include BASE_PATH . 'views/components/match-actions.component.php';
            } ?>
            <td>
                <a href="<?php echo BASE_URL; ?>view-match/<?php echo $partit['id']; ?>" class="btn btn-primary">
                    Veure
                </a>
            </td>
        </div>
    <?php endforeach; ?>
</div>

