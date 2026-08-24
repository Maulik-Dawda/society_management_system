<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Database Setup Required - Meridian Heights CHS</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  body { background: #F3F1E9; color: #23281F; font-family: 'Inter', sans-serif; padding: 40px 20px; }
  .card { max-width: 680px; margin: 0 auto; background: #FFFEFA; border: 1px solid #DAD5C4; border-radius: 12px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
  h1 { font-family: 'Fraunces', serif; color: #123D31; font-size: 26px; margin-bottom: 8px; }
  p { color: #5B5F52; font-size: 14px; line-height: 1.6; margin-bottom: 20px; }
  .err-box { background: #F4E1D8; color: #B14A2E; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(177,74,46,0.3); font-family: 'IBM Plex Mono', monospace; font-size: 13px; margin-bottom: 24px; word-break: break-all; }
  .steps { background: #E4EDE7; border-left: 4px solid #1F5C4A; padding: 18px; border-radius: 6px; margin-bottom: 24px; }
  .steps h3 { color: #123D31; font-size: 15px; margin-bottom: 10px; }
  .steps ol { padding-left: 20px; font-size: 13.5px; color: #23281F; line-height: 1.8; }
  .code-block { background: #23281F; color: #F3F1E9; padding: 14px; border-radius: 6px; font-family: 'IBM Plex Mono', monospace; font-size: 12.5px; overflow-x: auto; margin-top: 8px; }
</style>
</head>
<body>
<div class="card">
    <h1>⚠️ Database Setup Required</h1>
    <p>The application could not connect to your MySQL Database on your hosting server.</p>
    
    <div class="err-box">
        <strong>Error details:</strong><br>
        <?= htmlspecialchars($errorMessage) ?>
    </div>

    <div class="steps">
        <h3>🛠️ How to configure Database Credentials on Hostinger / Web Host:</h3>
        <ol>
            <li>Log into your <b>Hostinger Control Panel (hPanel)</b> and navigate to <b>Databases > MySQL Databases</b>.</li>
            <li>Create a new MySQL Database, Database User, and Password.</li>
            <li>Create a file named <code>.env</code> in your root directory (same folder as <code>index.php</code>) with the following contents:
                <div class="code-block">
DB_HOST=localhost
DB_NAME=your_hostinger_db_name
DB_USER=your_hostinger_db_user
DB_PASS=your_hostinger_db_password
                </div>
            </li>
            <li>Import <code>database/schema.sql</code> into your database using <b>phpMyAdmin</b> if tables are not created automatically.</li>
            <li>Refresh this page!</li>
        </ol>
    </div>
</div>
</body>
</html>
