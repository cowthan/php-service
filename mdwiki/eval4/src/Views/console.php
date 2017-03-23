<?php echo Meebio\PhpEvalConsole\Helper::template('partials/head'); ?>

<div id="console" class="console" data-action="<?php echo $config['execute_url']; ?>">
    <ul id="response" class="response">
    </ul>

    <nav id="controlbar" class="controlbar">
        <ul id="controls" class="controls">
        </ul>

        <div id="execute" class="execute">Execute</div>
    </nav>

    <section id="editor" class="editor">
    </section>
</div>

<?php echo Meebio\PhpEvalConsole\Helper::template('partials/templates'); ?>
<?php echo Meebio\PhpEvalConsole\Helper::template('partials/foot'); ?>
