<?php 
include_once 'common/session_db.php';
if(!USER['id']){
	$db->sendBack($_SERVER);
}
if($_REQUEST['id']){
	$book = $db->select('books',"*",array('id'=>$_REQUEST['id']))->fetch_assoc();
	$quantity = $book['quantity'];
	if($quantity > 0){
		$borrow_book_data = array('created_at'=>date('Y-m-d H:i:s'),'user_id'=>USER['id'],'books_id'=>$_REQUEST['id']);
		$insert_borrow_book = $db->insertQuery($borrow_book_data,'borrow_book');
		if($insert_borrow_book[0] == 1){
			$quantity = $quantity - 1;
			$updateQuantity = $db->updateQuery(array('quantity'=>$quantity),'books',$_REQUEST['id']);
			if($updateQuantity[0] == 1){
				?>
				<script type="text/javascript">
					alert("Book added in your account successfully");
				</script>
				<?php
				$db->sendBack($_SERVER);
			}else{
				?>
				<script type="text/javascript">
					alert("Some problem accur please try again. Error - <?php echo $conn->error; ?>");
				</script>
				<?php
				$db->sendBack($_SERVER);
			}
		}
	}else{
		?>
		<script type="text/javascript">
			alert("This book is not avilble");
		</script>
		<?php
		$db->sendBack($_SERVER);
	}
	
}
?>