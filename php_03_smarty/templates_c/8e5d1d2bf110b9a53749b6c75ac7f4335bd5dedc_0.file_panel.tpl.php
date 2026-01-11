<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:36:10
  from 'file:panel.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637d1aa6d346_48321460',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e5d1d2bf110b9a53749b6c75ac7f4335bd5dedc' => 
    array (
      0 => 'panel.tpl',
      1 => 1768065160,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637d1aa6d346_48321460 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_196067219669637d1aa64750_84461838', 'content');
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layout.tpl", $_smarty_current_dir);
}
/* {block 'content'} */
class Block_196067219669637d1aa64750_84461838 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

  <div class="card wide">
    <h1><?php echo (($tmp = $_smarty_tpl->getValue('page_title') ?? null)===null||$tmp==='' ? 'Witaj w systemie kalkulatorów!' ?? null : $tmp);?>
</h1>
    <h2><?php echo (($tmp = $_smarty_tpl->getValue('page_subtitle') ?? null)===null||$tmp==='' ? 'Wybierz rodzaj kalkulatora:' ?? null : $tmp);?>
</h2>

    <div class="btn-col">
      <a class="btn btn-secondary btn-block" href="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/calc.php">Zwykły kalkulator</a>
      <a class="btn btn-primary btn-block" href="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/kredyt.php">Kalkulator kredytowy</a>
      <a class="btn btn-danger btn-block" href="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/karta_chroniona.php">Karta chroniona</a>
    </div>
  </div>
<?php
}
}
/* {/block 'content'} */
}
