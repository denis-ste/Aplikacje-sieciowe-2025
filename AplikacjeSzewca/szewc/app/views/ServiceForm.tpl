{extends file="layouts/layout.tpl"}

{block name=content}
{if $mode == 'new'}
    <h2>Dodaj usługę</h2>
    <form method="post" action="{$conf->action_url}service_new" class="auth-form">
{else}
    <h2>Edytuj usługę #{$service.id}</h2>
    <form method="post" action="{$conf->action_url}service_edit?id={$service.id}" class="auth-form">
{/if}

    <div class="form-group">
        <label for="name">Nazwa</label>
        <input id="name" type="text" name="name" value="{$service.name|default:''}" required>
    </div>

    <div class="form-group">
        <label for="price">Cena (zł)</label>
        <input id="price" type="number" step="0.01" name="price" value="{$service.price|default:''}" required>
    </div>

    {if $mode == 'edit'}
        <div class="form-group">
            <label for="is_active">Aktywna</label>
            <select id="is_active" name="is_active">
                <option value="Y" {if $service.is_active=='Y'}selected{/if}>Y</option>
                <option value="N" {if $service.is_active=='N'}selected{/if}>N</option>
            </select>
        </div>
    {/if}

    <button type="submit" class="btn-submit">Zapisz</button>

</form>
{/block}
