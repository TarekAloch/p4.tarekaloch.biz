<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><? $t->block('title'); ?>WordBlocks v1<? $t->endblock(true); ?></title>
    <meta name="description" content="A word game to play with friends!">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <? $t->block('meta'); ?>
    <? $t->endblock(true); ?>

<meta http-equiv="refresh" content="100">
<link href="http://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:700" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/public/assets/css/normalize.css" />
<link rel="stylesheet" href="/public/assets/css/main.css">
<script src="/public/assets/js/libs/jquery-1.7.2.min.js"></script>
<script src="/public/assets/js/libs/jquery-ui.min.js"></script>
</head>
<body>
    <a class="logo-link" href="/"><h2>WordBlocks</h2></a>
    <hr/>
    <p>Find someone to play with and add their email!</p>
    <div class="container_12" id="main-content">
        <? $t->block('content'); ?>
        <? $t->endblock(true); ?>
    </div>
    <hr/>
    <? $t->block('footer'); ?>
    <? $t->endblock(true); ?>
    
    <script>
        $(document).data('view_data', <?= json_encode($this->_get_view_data($data)); ?>);
    </script>
    <? foreach ($this->_scripts as $script): ?>
        <script src="<?=$script?>"></script>
    <? endforeach; ?>

</body>
</html>
