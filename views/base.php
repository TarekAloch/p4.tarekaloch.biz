<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><? $t->block('title'); ?>WordBlocks v1<? $t->endblock(true); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="viewport" content="width=500, initial-scale=1">
    <? $t->block('meta'); ?>
    <? $t->endblock(true); ?>

    <meta http-equiv="refresh" content="100;">

    
<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:700' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="/public/assets/css/normalize.css" />
<style>
body{
    background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #ededed 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #ffffff 0%,#f6f6f6 47%,#ededed 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #ffffff 0%,#f6f6f6 47%,#ededed 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #ffffff 0%,#f6f6f6 47%,#ededed 100%); /* IE10+ */
background: linear-gradient(to bottom, #ffffff 0%,#f6f6f6 47%,#ededed 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 ); /* IE6-9 */
    padding: 0;
    margin:0;
    text-align: center;
    line-height: 1;
}
h2{font-family: 'Pacifico', Arial, sans-serif;font-weight:700;font-size:3em;
   text-align: center; 
}
.game_div {
     text-decoration: none;
    font:24px/1em 'Droid Sans', sans-serif;
    text-align: center;
    margin: 0 auto;
     -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    padding:0;
    width:auto;
    }

    .game_div td{
    background: rgb(247, 247, 247);
    border: 1px solid rgba(147, 184, 189, .8);
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 20px rgba(105, 108, 109, .3), 0 0 8px 5px rgba(208, 223, 226, .4) inset;
    box-shadow: 0 5px 20px rgba(105, 108, 109, .3), 0 0 8px 5px rgba(208, 223, 226, .4) inset;
    padding: 20px;
    }
.game_div > *{margin:0 auto;
    background: rgba(222,222,222,.3);
    border:inset 3px #ddd;
    /*box-shadow*/
    -webkit-box-shadow: 1px -1px #fff, -1px 1px #ccc;
    -moz-box-shadow: 1px -1px #fff, -1px 1px #ccc;
    box-shadow: 1px -1px #fff, -1px 1px #ccc;
}

.game_div td.disabled{
    color:#fff;
    text-shadow: 1px 1px 0px rgba(255,255,255,0.15);
  box-shadow: inset 1px 1px 2px rgba(255,255,255,0.3), 
              inset 3px 15px 45px rgba(0,0,0,0.2),
              inset -1px -1px 2px rgba(0,0,0,0.5), 
              inset -3px -15px 45px rgba(255,255,255,0.1),
              1px 5px 10px -4px rgba(0,0,0,1);
                background-color:#EEEEEE;
  background: linear-gradient(#EDEAE1,#CDC8B5);
  text-align:center;
  box-shadow:
    0 5px 2px 3px rgba(158, 158, 158, 0.4), 
    0 3px 5px #B7B6B6, 
    0 0 0 2px #BBB7AE, 
    inset 0 -3px 1px 2px rgba(186, 178, 165, 0.5),
    inset 0 3px 1px 2px rgba(246, 245, 241, 0.3);
  cursor:pointer;
}



input[type="submit"]{
    position: relative;
    color: rgba(255,255,255,1);
    text-decoration: none;
    background-color: rgba(219,87,5,1);
    font-family: 'Yanone Kaffeesatz';
    font-weight: 700;
    font-size: 2em;
    display: block;
    padding: 4px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    -webkit-box-shadow: 0px 9px 0px rgba(219,31,5,1), 0px 9px 25px rgba(0,0,0,.7);
    -moz-box-shadow: 0px 9px 0px rgba(219,31,5,1), 0px 9px 25px rgba(0,0,0,.7);
    box-shadow: 0px 9px 0px rgba(219,31,5,1), 0px 9px 25px rgba(0,0,0,.7);
    margin: 100px auto;
    width: 160px;
    text-align: center;
    
    -webkit-transition: all .1s ease;
    -moz-transition: all .1s ease;
    -ms-transition: all .1s ease;
    -o-transition: all .1s ease;
    transition: all .1s ease;
}

input[type="submit"]:active {
    -webkit-box-shadow: 0px 3px 0px rgba(219,31,5,1), 0px 3px 6px rgba(0,0,0,.9);
    -moz-box-shadow: 0px 3px 0px rgba(219,31,5,1), 0px 3px 6px rgba(0,0,0,.9);
    box-shadow: 0px 3px 0px rgba(219,31,5,1), 0px 3px 6px rgba(0,0,0,.9);
    position: relative;
    top: 6px;
}
</style>
    
    <link rel="stylesheet" href="/public/assets/css/main.css">
    <script src="/public/assets/js/libs/jquery-1.7.2.min.js"></script>
    <script src="/public/assets/js/libs/jquery-ui.min.js"></script>
</head>
<body>

    <h2>WordBlocks</h2>
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
