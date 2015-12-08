<div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $schema['title']; ?>
        ：</label>

    <div class="col-sm-9">
        <div class="skin skin-flat">
            <?php if ($schema['option']): ?>
            <?php foreach ($schema['option'] as $key => $value): ?>
            <label for="" class="checkbox-inline">
                <?php echo $value; ?>
                <input type="checkbox"
                       name="<?php echo $schema['name']; ?>"
                       value="<?php echo $key; ?>" <?php if (in_array($key, $schema['option_value_schema'])) {
                    echo 'checked="checked"';
                } ?> aria-describedby="help-block" tabindex="11"
                       id="flat-checkbox-1"/>
            </label>
            <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $schema['notice']; ?></span>
        </div>
    </div>
</div>