<?php
/* Smarty version 5.4.2, created on 2026-01-11 11:36:12
  from 'file:kredyt.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.2',
  'unifunc' => 'content_69637d1c2e8876_36273666',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '664fb5fd0c2dc89b9e5f93ae03627a2a830136d7' => 
    array (
      0 => 'kredyt.tpl',
      1 => 1768124776,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69637d1c2e8876_36273666 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_155050599969637d1c2d2661_03045923', 'content');
?>

<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layout.tpl", $_smarty_current_dir);
}
/* {block 'content'} */
class Block_155050599969637d1c2d2661_03045923 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\KATALOG_ZADANIA\\php_03_smarty\\templates_ui';
?>

  <div class="card wide">
    <h1>Kalkulator kredytowy</h1>

    <form method="post" action="<?php echo $_smarty_tpl->getValue('app_url');?>
/app/kredyt.php">
      <div class="form-row">
        <label>Kwota kredytu:</label>
        <input type="text" name="kwota" value="<?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('kwota') ?? null)===null||$tmp==='' ? '' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <label>Okres (w latach):</label>
        <input type="text" name="lata" value="<?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('lata') ?? null)===null||$tmp==='' ? '' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <label>Oprocentowanie roczne (%):</label>
        <input type="text" name="oprocentowanie" value="<?php echo htmlspecialchars((string)(($tmp = $_smarty_tpl->getValue('oprocentowanie') ?? null)===null||$tmp==='' ? '' ?? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary">Oblicz ratę</button>
      </div>
    </form>

    <?php if ((null !== ($_smarty_tpl->getValue('messages') ?? null)) && $_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('messages')) > 0) {?>
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

    <?php if ((null !== ($_smarty_tpl->getValue('rata') ?? null)) && $_smarty_tpl->getValue('rata') !== null && (!(null !== ($_smarty_tpl->getValue('messages') ?? null)) || $_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('messages')) == 0)) {?>
      <div class="result">Miesięczna rata: <?php echo sprintf("%.2f",$_smarty_tpl->getValue('rata'));?>
 zł</div>
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
