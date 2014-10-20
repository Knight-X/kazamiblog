<h2>Create New Item</h2>

<?php echo validation_errors(); ?>

<?php echo form_open('news/create') ?>

    <label for="title">TITLE</label>
    <input type="input" name="title" /><br />

    <label for="text">Cotent</label>
    <textarea name="text"></textarea><br />

    <input type="submit" name="submit" value="Create News Item" />
</form>
