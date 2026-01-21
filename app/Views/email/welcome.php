<?= $this->extend('email/layout') ?>

<?= $this->section('content') ?>
<?php $theme = include __DIR__ . '/theme.php'; ?>

<h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: <?= $theme['color_text'] ?>;">
    Welcome to <?= $theme['brand_name'] ?>!
</h2>

<p style="margin: 0 0 16px 0; color: <?= $theme['color_text_muted'] ?>;">
    Hi <?= esc($name) ?>,
</p>

<p style="margin: 0 0 24px 0; color: <?= $theme['color_text_muted'] ?>;">
    Thank you for creating an account. Please confirm your email address to get started.
</p>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding: 8px 0 24px 0;">
            <a href="<?= esc($link) ?>" style="display: inline-block; padding: 12px 32px; background-color: <?= $theme['color_primary'] ?>; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px;">
                Confirm Email
            </a>
        </td>
    </tr>
</table>

<p style="margin: 0; font-size: 13px; color: <?= $theme['color_text_light'] ?>;">
    If you didn't create this account, you can safely ignore this email.
</p>

<?= $this->endSection() ?>
