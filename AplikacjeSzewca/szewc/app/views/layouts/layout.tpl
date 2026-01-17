<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$pageTitle|default:"Zakład Szewca"}</title>

    <style>
        :root {
            --bg: #f6f7fb;
            --surface: #ffffff;
            --text: #0f472a;
            --muted: #64748b;
            --border: #e5e7eb;

            --topbar: #111827;
            --topbar_text: #ffffff;

            --primary: #14b8a6;
            --primary_hover: #0f766e;
            --secondary: #6366f1;
            --secondary_hover: #4f46e5;
            --danger: #ef4444;
            --danger_hover: #dc2626;
            /* kolor "Zalogowany: ..." ma byc taki sam jak przyciski menu */
            --badge: #14b8a6;
        }

        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: var(--bg);
            color: var(--text);
        }

        /* TOPBAR */
        nav {
            background: var(--topbar);
            color: var(--topbar_text);
            padding: 14px 22px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 10px;
        }
        nav a {
            color: var(--topbar_text);
            text-decoration: none;
            font-weight: 700;
        }
        nav a:hover { opacity: 0.9; }

        .nav-left { font-size: 14px; display: flex; align-items: center; }
        .nav-center { font-size: 20px; font-weight: 900; text-align: center; letter-spacing: 0.3px; }
        .nav-right { font-size: 14px; display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }

        /* LAYOUT */
        .container {
            display: flex;
            justify-content: center;
            padding: 34px 18px;
        }
        .content-wrap {
            width: 100%;
            max-width: 1100px;
        }
        .content-wrap h1,
        .content-wrap h2,
        .content-wrap h3 {
            text-align: center;
            margin-top: 0;
        }

        /* HERO (home) */
        .start-center {
            min-height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 18px;
        }
        .start-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* CARD + CENTER FORMS */
        .page-center { display: flex; justify-content: center; }
        .page-card {
            width: 100%;
            max-width: 560px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }

        /* BADGE */
        .badge {
            background: var(--badge);
            color: #062a2a;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
        }

        /* BUTTONS */
        .btn-menu,
        .btn,
        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 14px;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 800;
            transition: transform 0.03s ease, background 0.15s ease, border-color 0.15s ease;
            white-space: nowrap;
        }
        .btn-menu {
            background: var(--primary);
            color: #062a2a;
        }
        .btn-menu:hover { background: var(--primary_hover); color: #ffffff; }
        .btn {
            background: var(--danger);
            color: #ffffff;
        }
        .btn:hover { background: var(--danger_hover); }
        .btn-submit {
            width: 100%;
            background: var(--secondary);
            color: #ffffff;
        }
        .btn-submit:hover { background: var(--secondary_hover); }
        .btn-menu:active,
        .btn:active,
        .btn-submit:active { transform: translateY(1px); }

        /* home buttons bigger and horizontal */
        .start-buttons .btn-menu {
            min-width: 180px;
            border-radius: 999px;
            padding: 12px 18px;
        }

        /* ALERTS */
        .alert-danger {
            background: #fff1f2;
            color: #9f1239;
            padding: 12px 16px;
            margin-bottom: 16px;
            border: 1px solid #fecdd3;
            border-radius: 14px;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            padding: 12px 16px;
            margin-bottom: 16px;
            border: 1px solid #a7f3d0;
            border-radius: 14px;
        }
        .alert-danger ul, .alert-success ul { margin: 0; padding-left: 18px; }

        /* FORMS */
        .auth-form { margin-top: 14px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 14px; }
        .form-group label { margin-bottom: 6px; font-weight: 800; color: var(--text); }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 12px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #ffffff;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: 2px solid rgba(99, 102, 241, 0.25);
            border-color: rgba(99, 102, 241, 0.6);
        }

        /* TABLES */
        .table-wrap { width: 100%; max-width: 1050px; margin: 0 auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }
        th, td { padding: 12px 14px; border-bottom: 1px solid var(--border); text-align: left; }
        th { background: #f1f5f9; font-weight: 900; }
        tr:hover { background: #fafafa; }

        .pill { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #eef2ff; color: #3730a3; font-size: 12px; font-weight: 800; }

        @media (max-width: 520px) {
            nav { grid-template-columns: 1fr auto 1fr; padding: 12px 14px; }
            .nav-right { justify-content: center; }
            .start-buttons .btn-menu { min-width: 160px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        {if !empty($conf->roles)}
            <span class="badge">Zalogowany: {$smarty.session.user_login}</span>
        {else}
            &nbsp;
        {/if}
    </div>

    <div class="nav-center">Zakład szewca</div>

    <div class="nav-right">
        {if !empty($conf->roles)}

            {if \core\RoleUtils::inRole('CLIENT')}
                <a class="btn-menu" href="{$conf->action_url}client_dashboard">Panel</a>
                <a class="btn-menu" href="{$conf->action_url}order_new">Nowe zlecenie</a>
                <a class="btn-menu" href="{$conf->action_url}my_orders">Moje zlecenia</a>
            {/if}

            {if \core\RoleUtils::inRole('WORKER')}
                <a class="btn-menu" href="{$conf->action_url}worker_orders">Zlecenia</a>
            {/if}

            {if \core\RoleUtils::inRole('ADMIN')}
                <a class="btn-menu" href="{$conf->action_url}admin_services">Usługi (CRUD)</a>
            {/if}

            <a class="btn" href="{$conf->action_url}logout">Wyloguj</a>
        {else}
            &nbsp;
        {/if}
    </div>
</nav>

<div class="container">
    <div class="content-wrap">

        {* ===== KOMUNIKATY ===== *}
        {if isset($messages) && $messages->isError()}
            <div class="alert-danger">
                <ul>
                    {foreach $messages->getMessages() as $msg}
                        {if $msg->isError()}<li>{$msg->text}</li>{/if}
                    {/foreach}
                </ul>
            </div>
        {/if}

        {if isset($messages) && $messages->isInfo()}
            <div class="alert-success">
                <ul>
                    {foreach $messages->getMessages() as $msg}
                        {if $msg->isInfo()}<li>{$msg->text}</li>{/if}
                    {/foreach}
                </ul>
            </div>
        {/if}

        {block name=content}{/block}

    </div>
</div>

</body>
</html>
