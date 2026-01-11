<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{block name=title}Aplikacja{/block}</title>
  <style>
    :root{
      --bg:#0b1220;
      --card:#111a2e;
      --card2:#0f172a;
      --txt:#e5e7eb;
      --muted:#9ca3af;
      --acc:#22c55e;
      --acc2:#60a5fa;
      --danger:#ef4444;
      --border:rgba(255,255,255,.12);
    }
    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      margin:0;
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;
      background: radial-gradient(1200px 700px at 20% 10%, #1b2a55 0%, rgba(27,42,85,0) 60%),
                  radial-gradient(900px 500px at 80% 30%, #123a2b 0%, rgba(18,58,43,0) 55%),
                  var(--bg);
      color:var(--txt);
    }
    a{color:inherit;text-decoration:none}
    .container{max-width:1000px;margin:0 auto;padding:0 16px}
    .nav{
      position:sticky;top:0;z-index:10;
      backdrop-filter: blur(10px);
      background: rgba(10,15,30,.70);
      border-bottom:1px solid var(--border);
    }
    .nav-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 0;gap:16px}
    .brand{
      font-weight:800;letter-spacing:.6px;
      padding:8px 10px;border-radius:10px;
      border:1px solid transparent;
    }
    .brand:hover{border-color:var(--border);background:rgba(255,255,255,.04)}
    .nav-right{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;align-items:center}
    .nav-link{
      padding:8px 10px;border-radius:10px;
      border:1px solid transparent;
      color:var(--txt);
      font-weight:600;
      font-size:14px;
    }
    .nav-link:hover{border-color:var(--border);background:rgba(255,255,255,.04)}
    .badge{
      padding:6px 10px;border-radius:999px;
      border:1px solid var(--border);
      color:var(--muted);
      font-size:12px;
      white-space:nowrap;
    }

    .hero{padding:34px 0 18px}
    .hero-inner{display:flex;justify-content:center}
    .hero-box{
      width:100%;
      border:1px solid var(--border);
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
      border-radius:18px;
      padding:22px;
      box-shadow: 0 18px 60px rgba(0,0,0,.35);
    }
    .hero-title{font-size:34px;font-weight:900;letter-spacing:1px}
    .hero-sub{margin-top:8px;color:var(--muted);max-width:760px;line-height:1.45}
    .btn{
      display:inline-block;
      margin-top:14px;
      background: var(--acc);
      color:#07110b;
      font-weight:800;
      padding:10px 14px;
      border-radius:12px;
      border:0;
      cursor:pointer;
    }
    .btn:hover{filter:brightness(1.05)}
    .card{
      margin:18px 0 40px;
      border:1px solid var(--border);
      background: rgba(17,26,46,.72);
      border-radius:18px;
      padding:18px;
      box-shadow: 0 14px 44px rgba(0,0,0,.25);
    }
    .h2{font-size:20px;font-weight:900;margin:0 0 6px}
    .muted{color:var(--muted);font-size:14px}

    label{display:block;margin:10px 0 6px;color:var(--muted);font-size:13px}
    input, select{
      width:100%;
      padding:10px 12px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,.16);
      background: rgba(10,15,30,.6);
      color:var(--txt);
      outline:none;
    }
    input:focus, select:focus{border-color:rgba(96,165,250,.55)}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    .col{flex:1;min-width:220px}
    button{
      background: var(--acc2);
      color:#061018;
      font-weight:900;
      padding:10px 14px;
      border-radius:12px;
      border:0;
      cursor:pointer;
    }
    button:hover{filter:brightness(1.05)}

    .errors{
      border:1px solid rgba(239,68,68,.45);
      background: rgba(239,68,68,.10);
      border-radius:14px;
      padding:12px 14px;
      color:#fecaca;
    }
    .result{
      border:1px solid rgba(34,197,94,.35);
      background: rgba(34,197,94,.10);
      border-radius:14px;
      padding:12px 14px;
      color:#bbf7d0;
    }
    .info{
      border:1px solid rgba(96,165,250,.35);
      background: rgba(96,165,250,.10);
      border-radius:14px;
      padding:12px 14px;
      color:#dbeafe;
    }
    .footer-actions{margin-top:14px;display:flex;gap:12px;flex-wrap:wrap}
    .link{color:#93c5fd;font-weight:700}
    .link:hover{text-decoration:underline}
    footer{border-top:1px solid var(--border);padding:18px 0;color:var(--muted);font-size:13px}
  </style>
</head>
<body>

  <div class="nav" id="app_top">
    <div class="container">
      <div class="nav-inner">
        <a class="brand" href="{$app_root|default:''}/index.php#app_top">START</a>

        <div class="nav-right">
          <a class="nav-link" href="#app_top">Góra strony</a>
          <a class="nav-link" href="#app_content">Idź do formularza</a>

          {if $is_logged}
            <a class="nav-link" href="{$app_root|default:''}/index.php#app_content">Kredyt</a>
            <a class="nav-link" href="{$app_root|default:''}/app/calc.php#app_content">Kalkulator</a>
            <a class="nav-link" href="{$app_root|default:''}/app/security/logout.php">Wyloguj</a>
            <span class="badge">Zalogowany: {$user_login|default:''|escape} ({$user_role|default:''|escape})</span>
          {else}
            <a class="nav-link" href="{$app_root|default:''}/app/security/login.php#app_content">Zaloguj</a>
          {/if}
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    {block name=app_top}{/block}

    <div id="app_content">
      {block name=app_content}{/block}
    </div>

    <footer class="container">
      {block name=footer}© php_03_smarty{/block}
    </footer>
  </div>

  {block name=scripts}{/block}

</body>
</html>
