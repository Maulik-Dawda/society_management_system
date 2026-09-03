<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Meridian Heights CHS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --paper: #F3F1E9;
            --paper-raised: #FFFEFA;
            --ink: #23281F;
            --ink-soft: #5B5F52;
            --line: #DAD5C4;
            --green: #1F5C4A;
            --green-dark: #123D31;
            --green-tint: #E4EDE7;
            --gold: #B9812A;
            --gold-tint: #F5E9D2;
            --rust: #B14A2E;
            --rust-tint: #F4E1D8;
            --radius: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--paper); color: var(--ink); font-family: 'Inter', sans-serif; min-height: 100vh; }

        /* General Auth Container & Cards */
        .auth-container { max-width: 460px; margin: 50px auto; padding: 20px; }
        .auth-card { background: var(--paper-raised); border: 1px solid var(--line); border-radius: var(--radius); padding: 36px 32px; box-shadow: 0 8px 24px rgba(35,40,31,0.06); }
        .auth-header { text-align: center; margin-bottom: 26px; }
        .auth-header h2 { font-family: 'Fraunces', serif; font-size: 26px; font-weight: 600; color: var(--green-dark); margin-bottom: 6px; }
        .auth-header p { font-size: 13.5px; color: var(--ink-soft); line-height: 1.5; }

        /* Form Fields */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); margin-bottom: 7px; }
        .form-control { width: 100%; font-family: 'Inter', sans-serif; font-size: 14px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; background: var(--paper); color: var(--ink); transition: all 0.15s ease; }
        .form-control:focus { outline: none; border-color: var(--green); background: #fff; box-shadow: 0 0 0 3px rgba(31,92,74,0.12); }

        /* Buttons */
        .btn { width: 100%; border: none; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 14.5px; padding: 13px 20px; border-radius: 8px; cursor: pointer; background: var(--green); color: #fff; transition: background 0.15s ease, transform 0.1s ease; text-align: center; display: inline-block; text-decoration: none; }
        .btn:hover { background: var(--green-dark); }
        .btn:active { transform: scale(0.99); }
        .btn.btn-outline { background: transparent; color: var(--green-dark); border: 1.5px solid var(--green); }
        .btn.btn-outline:hover { background: var(--green-tint); }

        /* Flash & Alerts */
        .alert { padding: 13px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; line-height: 1.5; }
        .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
        .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
        .alert-info { background: var(--gold-tint); color: var(--gold); border: 1px solid rgba(185,129,42,0.3); }

        /* Password Rules Indicator */
        .rule-list { list-style: none; margin-top: 10px; font-size: 12px; }
        .rule-item { margin-bottom: 5px; color: var(--ink-soft); display: flex; align-items: center; gap: 6px; }
        .rule-item.valid { color: var(--green); font-weight: 600; }
        .rule-item.invalid { color: var(--rust); }

        .auth-footer { text-align: center; margin-top: 22px; font-size: 13px; color: var(--ink-soft); }
        .auth-footer a { color: var(--green); text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<?php
$flashError = Session::getFlash('error');
$flashSuccess = Session::getFlash('success');
$flashErrors = Session::getFlash('errors');
?>
<?php if ($flashError): ?>
    <div style="max-width: 600px; margin: 20px auto -30px;">
        <div class="alert alert-danger"><?= htmlspecialchars($flashError) ?></div>
    </div>
<?php endif; ?>
<?php if ($flashSuccess): ?>
    <div style="max-width: 600px; margin: 20px auto -30px;">
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
    </div>
<?php endif; ?>
<?php if (!empty($flashErrors)): ?>
    <div style="max-width: 600px; margin: 20px auto -30px;">
        <div class="alert alert-danger">
            <ul style="padding-left: 20px;">
                <?php foreach ($flashErrors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
