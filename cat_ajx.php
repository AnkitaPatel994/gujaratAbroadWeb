<?php
include "config.php";
if(!empty($_POST['cat_id'])) {
	$query ="SELECT * FROM tbl_subcategory WHERE subcat_cat = '$_POST[cat_id]'";
	$exe=mysqli_query($conn,$query);
	
	while($data=mysqli_fetch_array($exe)) {
?>

	<option value="<?php echo $data['subcat_id']; ?>"><?php echo $data['subcat_name']; ?></option>
<?php
	}
}
?> 