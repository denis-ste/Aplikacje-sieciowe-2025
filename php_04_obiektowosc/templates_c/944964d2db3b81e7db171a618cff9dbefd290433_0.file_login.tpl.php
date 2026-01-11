<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:34:57
  from 'file:login.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637cd1223721_81234664',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '944964d2db3b81e7db171a618cff9dbefd290433' => 
    array (
      0 => 'login.tpl',
      1 => 1768065134,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637cd1223721_81234664 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_54646022569637cd1210807_39581008', 'content');
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layout.tpl", $_smarty_current_dir);
}
/* {block 'content'} */
class Block_54646022569637cd1210807_39581008 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

  <div class="card">
    <h1>Logowanie</h1>

    <form method="post" action="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/security/login.php<?php if ($_GET['return']) {?>?return=<?php echo rawurlencode((string)$_GET['return']);
}?>">
      <div class="form-row">
        <label>Login:</label>
        <input type="text" name="login" value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('login'), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <label>Hasło:</label>
        <input type="password" name="pass" value="">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
      </div>
    </form>

    <?php if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('messages')) > 0) {?>
      <div class="msg msg-err">
        <ul>
          <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('messages'), 'm');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('m')->value) {
$foreach0DoElse = false;
?>
            <li><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('m'), ENT_QUOTES, 'UTF-8', true);?>
</li>
          <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </ul>
      </div>
    <?php }?>
  </div>
<?php
}
}
/* {/block 'content'} */
}
