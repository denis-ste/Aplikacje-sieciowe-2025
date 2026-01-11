<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:36:14
  from 'file:calc.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637d1e862c15_05744015',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b5b590a4f195febb185b800e8f021108e096c95f' => 
    array (
      0 => 'calc.tpl',
      1 => 1768124708,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637d1e862c15_05744015 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_149333361469637d1e850472_19053627', 'content');
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layout.tpl", $_smarty_current_dir);
}
/* {block 'content'} */
class Block_149333361469637d1e850472_19053627 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

  <div class="card wide">
    <h1>Zwykły kalkulator</h1>

    <?php if ($_smarty_tpl->getValue('restriction_info') != '') {?>
      <div class="msg msg-warn"><?php echo htmlspecialchars((string)$_smarty_tpl->getValue('restriction_info'), ENT_QUOTES, 'UTF-8', true);?>
</div>
    <?php }?>

    <form method="post" action="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/calc.php">
      <div class="form-row">
        <label>Liczba 1:</label>
        <input type="text" name="liczba1" value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('liczba1'), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <label>Operacja:</label>
        <select name="operacja">
          <option value="plus" <?php if ($_smarty_tpl->getValue('operacja') == 'plus') {?>selected<?php }?>>+</option>
          <option value="minus" <?php if ($_smarty_tpl->getValue('operacja') == 'minus') {?>selected<?php }?>>-</option>
          <option value="times" <?php if ($_smarty_tpl->getValue('operacja') == 'times') {?>selected<?php }?>>*</option>
          <option value="div" <?php if ($_smarty_tpl->getValue('operacja') == 'div') {?>selected<?php }?>>\</option>
        </select>
      </div>

      <div class="form-row">
        <label>Liczba 2:</label>
        <input type="text" name="liczba2" value="<?php echo htmlspecialchars((string)$_smarty_tpl->getValue('liczba2'), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary">Oblicz</button>
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

    <?php if ($_smarty_tpl->getValue('wynik') !== null && $_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('messages')) == 0) {?>
      <div class="result">Wynik: <?php echo $_smarty_tpl->getValue('wynik');?>
</div>
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
