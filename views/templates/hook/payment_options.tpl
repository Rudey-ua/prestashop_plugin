<form method="post" action="{$action}">
    <select name="issuer" required>
        <option value="" selected disabled>Выберите эмитента</option>
        {foreach from=$banks item=bank}
            <option value="{$bank.id}">{$bank.name}</option>
        {/foreach}
    </select>
</form>