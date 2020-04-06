<a  href="<?php echo site_url('admin/category/index'); ?>" class="btn btn-info"><i class="arrow_left"></i> List</a>
<h5 class="font-20 mt-15 mb-1"><?php echo str_replace('_',' ','Category'); ?></h5>
<!--Data display of category with id--> 
<?php
	$c = $category;
?> 
<table class="table table-striped table-bordered">         
		<tr><td>Cat Name</td><td><?php echo $c['cat_name']; ?></td></tr>

<tr><td>Created At</td><td><?php echo $c['created_at']; ?></td></tr>

<tr><td>Updated At</td><td><?php echo $c['updated_at']; ?></td></tr>


</table>
<!--End of Data display of category with id//--> 