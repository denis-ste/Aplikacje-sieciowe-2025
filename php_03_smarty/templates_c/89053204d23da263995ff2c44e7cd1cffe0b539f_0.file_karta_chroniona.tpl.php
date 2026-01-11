<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:36:20
  from 'file:karta_chroniona.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637d240b3382_11085894',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89053204d23da263995ff2c44e7cd1cffe0b539f' => 
    array (
      0 => 'karta_chroniona.tpl',
      1 => 1768065210,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637d240b3382_11085894 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_180192430869637d240a3a75_29167923', 'content');
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layout.tpl", $_smarty_current_dir);
}
/* {block 'content'} */
class Block_180192430869637d240a3a75_29167923 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

  <div class="card wide">
    <h1>Karta chroniona</h1>

    <?php if (!$_smarty_tpl->getValue('is_admin')) {?>
      <div class="msg msg-warn">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('messages'), 'm');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('m')->value) {
$foreach0DoElse = false;
?>
          <?php echo htmlspecialchars((string)$_smarty_tpl->getValue('m'), ENT_QUOTES, 'UTF-8', true);?>

        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
      </div>
    <?php } else { ?>
      <div class="msg msg-ok">Masz dostęp (admin).</div>
    <?php }?>

    <div class="backline">
      <a href="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/home.php">Powrót</a>
    </div>
  </div>
<?php
}
}
/* {/block 'content'} */
}
