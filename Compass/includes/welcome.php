<?php
if(is_user_logged_in()):
?>
<div class=" bg-profile">
<div class="inner outer">
<div class="col col1_3">
<?php the_post_thumbnail('medium');?>
</div>
<div class="col col2_3">
<h2>Welcome to Compass</h2>
<p class="lead">Measure your company's employee's competancy levels. Measure, compare and see results in your businesses ROI increase as you use our service.</p>
</div>
</div>
 </div>
</div>

    
<?php
else:

endif;
?>