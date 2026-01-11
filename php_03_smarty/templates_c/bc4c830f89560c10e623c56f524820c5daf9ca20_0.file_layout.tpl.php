<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:34:57
  from 'file:layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637cd1336bc8_78904602',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bc4c830f89560c10e623c56f524820c5daf9ca20' => 
    array (
      0 => 'layout.tpl',
      1 => 1768124791,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637cd1336bc8_78904602 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo (($tmp = $_smarty_tpl->getValue('page_title') ?? null)===null||$tmp==='' ? 'Aplikacja' ?? null : $tmp);?>
</title>
  <link rel="stylesheet" href="<?php echo $_smarty_tpl->getValue('app_url');?>
/css/ui.css">
</head>
<body>
  <div class="topbar">
    <div class="brand">Dzień dobry</div>
    <div class="topbar-right">
      <?php if ($_smarty_tpl->getValue('is_logged')) {?>
        <a class="toplink" href="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/security/logout.php">Wyloguj</a>
      <?php }?>
    </div>
  </div>

  <div class="page">
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_70249997369637cd1335741_97791047', 'content');
?>

  </div>
</body>
</html>
<?php }
/* {block 'app_top'} */
class Block_171551618169637cd1335bb2_69610527 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
}
}
/* {/block 'app_top'} */
/* {block 'app_content'} */
class Block_9205299469637cd13361a7_15022108 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
}
}
/* {/block 'app_content'} */
/* {block 'content'} */
class Block_70249997369637cd1335741_97791047 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

            <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_171551618169637cd1335bb2_69610527', 'app_top', $this->tplIndex);
?>

      <div id="app_content">
        <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_9205299469637cd13361a7_15022108', 'app_content', $this->tplIndex);
?>

      </div>
    <?php
}
}
/* {/block 'content'} */
}
