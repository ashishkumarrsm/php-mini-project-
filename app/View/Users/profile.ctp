<section class="panel profile-card">
    <span class="eyebrow">Your account</span>
    <h1><?php echo h($user['User']['username']); ?></h1>
    <p>Keep your account details handy while you shop.</p>

    <div class="detail-stats">
        <div>
            <span>Email</span>
            <strong><?php echo h($user['User']['email']); ?></strong>
        </div>
        <div>
            <span>Role</span>
            <strong><?php echo !empty($user['User']['role']) ? h($user['User']['role']) : 'customer'; ?></strong>
        </div>
        <div>
            <span>Member ID</span>
            <strong>#<?php echo (int)$user['User']['id']; ?></strong>
        </div>
    </div>
</section>
