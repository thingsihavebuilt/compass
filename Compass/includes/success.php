
<header>
<h1>Succession plan - suitable roles</h1>

</header>
<div class="items">
<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Staff Member'):
include('success-staff.php');
else:
include('success-manager.php');
endif;

?>
</div>