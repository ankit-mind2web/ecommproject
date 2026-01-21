<?php
/**
 * Email Layout Template
 * 
 * Base layout for all emails with header, footer, and card wrapper.
 * Templates extend this using $this->extend() and $this->section()
 */

$theme = include __DIR__ . '/theme.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? $theme['brand_name']) ?></title>
</head>
<body style="margin: 0; padding: 0; font-family: <?= $theme['font_family'] ?>; line-height: 1.5; color: <?= $theme['color_text'] ?>; background-color: <?= $theme['color_bg'] ?>;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: <?= $theme['color_bg'] ?>; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: <?= $theme['color_primary'] ?>; padding: 24px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 600;"><?= $theme['brand_name'] ?></h1>
                        </td>
                    </tr>
                    
                    <!-- Content Card -->
                    <tr>
                        <td style="background-color: <?= $theme['color_bg_card'] ?>; padding: 32px;">
                            <?= $this->renderSection('content') ?>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: <?= $theme['color_bg_card'] ?>; padding: 20px 32px; border-top: 1px solid <?= $theme['color_border'] ?>; border-radius: 0 0 8px 8px;">
                            <p style="margin: 0; font-size: 12px; color: <?= $theme['color_text_light'] ?>; text-align: center;">
                                &copy; <?= date('Y') ?> <?= $theme['brand_name'] ?>. All rights reserved.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
