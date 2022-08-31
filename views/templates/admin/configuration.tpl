{if $message != null}
    {$message}
{/if}

<form action="" method="post">
    <div class="form-group">
        <label class="form-control-label" for="input1">API-key</label>
        <input required name="API_KEY" value="{$API_KEY}" type="text" class="form-control" id="input1" />
    </div>

    <div class="form-group">
        <div class="md-checkbox">
            <label>
                <input type="checkbox"{if {$status} == 1} checked {/if} value="yes" name="certificate"/>
                <i class="md-checkbox-control"></i>
                {l s='Want to use a assets?'}
            </label>
        </div>
    </div>

    <div class="form-group">
        <button name="btnSubmit" type="submit" class="btn btn-success">{l s='Success'}</button>
    </div>
</form>