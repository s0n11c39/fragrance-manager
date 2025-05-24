<?php if (!isset($preview)) $preview = ''; ?>
<div style="display:none; font-size:1px; color:#f9f9f9; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
  <?= htmlspecialchars($preview) ?>
</div>

<div style="font-family:Arial, sans-serif; font-size:16px; color:#333; background:#f9f9f9; padding:20px;">
  <div style="max-width:600px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);">

    <div style="padding:20px 30px; text-align:center; background:#f0f0f0;">
      <img src="<?= $logoUrl ?>" alt="Logo"
     width="96" height="50"
     style="display:block; width:96px; height:50px;">
    </div>

    <div style="padding:30px;">
      <p style="margin-top:0;">Hallo <strong><?= htmlspecialchars($name) ?></strong>,</p>

      <p>wir haben auf deine Defektmeldung geantwortet:</p>

      <blockquote style="border-left:4px solid #007BFF; padding-left:12px; margin:20px 0; color:#555;">
        <?= nl2br(htmlspecialchars($message)) ?>
      </blockquote>

      <p>Du kannst den vollständigen Verlauf und Status jederzeit hier einsehen:</p>

      <p style="margin: 24px 0;">
        <a href="<?= $link ?>" style="display:inline-block; padding:12px 20px; background:#007BFF; color:#fff; text-decoration:none; border-radius:4px;">
          Meldung anzeigen
        </a>
      </p>

      <p style="color:#888; font-size:14px;">Viele Grüße,<br>Dein L'CREE Team</p>
    </div>

  </div>
</div>
