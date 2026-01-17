{extends file="layouts/layout.tpl"}

{block name=content}
{if !$isLogged}
    <div class="start-center">
        <div>
            <h1>Zakład szewca</h1>
        </div>

        <div class="start-buttons">
            <a class="btn-menu" href="{$conf->action_url}login">Zaloguj</a>
            <a class="btn-menu" href="{$conf->action_url}register">Rejestracja</a>
        </div>
    </div>
{else}
    <div class="start-center">
        <div>
            <h1>Zakład szewca</h1>
            <p>Wybierz opcję z menu.</p>
        </div>
    </div>
{/if}
{/block}
