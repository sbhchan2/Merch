<?php
	require_once("../etc/session.php");
	require_once("class.user.php");
	$auth_user = new USER();
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$user_id = $_SESSION['user_session'];
	$stmt->execute(array(":user_id"=>$user_id));
	$active_detail=$stmt->fetch(PDO::FETCH_ASSOC);

	$quality  = array("new","used","old");
	$category = array(" ","Book","clothe","appliance","etc");
	$size_char_array = array("XS","S","M","L","XL","XXL");
	//print_r($_SESSION);
	$hash_arr = array();

	if(!isset($_SESSION['product_category']))
	{
		$_SESSION['product_category'] = $_SESSION['product_title'] = $_SESSION['product_price'] = $_SESSION['product_quality'] = $_SESSION['product_description'] = $_SESSION['product_hashtag'] = "";
		unset($_SESSION['product_id']);
	}
	$_SESSION['sellpage_error'] =NULL;


//______________________________________________________________________________________

	if(isset($_POST['btn_product_submit']))
	{
		try
		{
			$_SESSION['product_category'] = strip_tags($_POST['product_category']);
			$_SESSION['product_title'] = strip_tags($_POST['product_title']);
			$_SESSION['product_price'] = strip_tags($_POST['product_price']);
			$_SESSION['product_quality'] = strip_tags($_POST['product_quality']);
			$_SESSION['product_description'] = strip_tags($_REQUEST['product_description']);
			$_SESSION['product_hashtag'] = strip_tags($_REQUEST['product_hashtag']);

/*
	Declare Session Variables
			$_SESSION['book_edition'] = $_SESSION['book_author'] =$_SESSION['book_subject'] = NULL;
			$_SESSION['clothe_brand'] =  $_SESSION['clothe_size_num'] = $_SESSION['clothe_size_char'] = NULL;
			$_SESSION['appliance_brand'] =NULL;
*/
		$auth_user->addProduct();
		$hash_arr = $auth_user->convert_hashtag();
		$auth_user->addHashtag($hash_arr);

		}
		catch (PDOException $e)
		{
			$_SESSION['sellpage_error'] = $e;
		}
	}
// clear button
	if(isset($_POST['btn_clear']))
	{
		$_SESSION['product_title']= $_SESSION['product_category'] = $_SESSION['product_price'] =  $_SESSION['product_quality'] = $_SESSION['product_description'] = "";
	}
/*
	Submit button for book, clothe appliance
	if(isset($_POST['btn_book_submit']))
	{
		try{
			$_SESSION['book_edition'] = strip_tags($_POST['book_edition']);
			$_SESSION['book_author'] = strip_tags($_POST['book_author']);
			$_SESSION['book_subject'] = strip_tags($_POST['book_subject']);
			$auth_user->addBook();

		}
		catch(PDOException $e){
				$_SESSION['sellpage_error'] = $e;
				//print_r($e);
		}
	}

	else if(isset($_POST['btn_clothe_submit']))
	{
		try{

				$_SESSION['clothe_size_num'] = strip_tags($_POST['clothe_size_num']);
				$_SESSION['clothe_size_char'] = strip_tags($_POST['clothe_size_char']);

				$auth_user->addProduct();
				$auth_user->addClothe();

				}
		catch(PDOException $e	){
			$_SESSION['sellpage_error'] = $e;
			//print_r($e);
		}
	}

	else if(isset($_POST['btn_appliance_submit']))
	{
		try{
				$_SESSION['appliance_brand'] = strip_tags($_POST['appliance_brand']);

				$auth_user->addProduct();
				$auth_user->addAppliance();

				}
		catch(PDOException $e	){
			$_SESSION['sellpage_error'] = $e;
			//print_r($e);
		}
	}
*/

?>
<!DOCTYPE html>
<html>
<head>
	<title>Sell_main</title>
	<link rel="stylesheet" type="text/css" href="../css/Sellpage.css">
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Fredoka+One" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">	</script>
	<script type="text/javascript" src="../js/Sellpage.js"></script>

</head>
<body>
	<div class="searchHeader">
		<div class="Logo">
			<div id="merchText">
				<a href="Buypage_loggedin.php">Merch</a>
			</div>
		</div>

		<div class="tm-container">
			<form action="search.php" method="post">
        <span>
					<button class="searchButton"></button>
				</span>
				<span class="searchBar">
					<input id="searchbar" type="text" name="hashTag" placeholder="#COMP2123 #ComputerScience #Kit #..">
				</span>
			</form>
		</div>


		<nav class="tm-nav">
				<ul>
					<li><button onclick="document.getElementById('requestModal').style.display='block'">Request</button></li>
					<li><a href="Buypage_loggedin.php">Buy</a></li>
					<li><a href="mypage.php">My page</a></li>
					<li><a href="log_in.php">My shopping bag</a></li>
				</ul>
			</nav>


  </div><!--searchHeader-->

	<div id="requestModal">
		<div id="requestContentDiv">
			<form action="Buypage_loggedin.php" method="post" enctype="multipart/form-data">
					<button id= "closeBtn" type="button"></button>
					<div class = "category">
						<select id="categorySelectBar" name="product_category">
										<option value = 1 selected >  </option>
										<option value = 2  > Clothe  </option>
										<option value = 3  > Appliance  </option>
										<option value = 4  > Etc  </option>
							</select>
					</div>
					<div class = "description">
						<textarea id="textareaTextBox" name="request_description" placeholder="Add Description to your product! "><?php if(isset($_SESSION['request_description'])){echo $_SESSION['request_description'];} ?></textarea>
					</div>
				<div class = "price">
						<input id="priceTextBox" type="text" class="form-control" name="request_price" placeholder="price(HKD)" value =<?php if(isset($_SESSION['request_price'])){print_r($_SESSION['request_price']);} ?>>
				</div>
				<br>
				<input id="requestSubmit" class="button" type="submit" name= "btn_request_submit"  value="Add Request" action ="Buypage_loggedin.php" >
			</form>
		</div>
	</div><!--requestModal-->

	<div class="requestedPanel" id="requested">
			<!-- Button to close the overlay navigation -->
	  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

	  <!-- Overlay content -->
	  <div class="overlay-content">
	    fuckyou
	  </div>
	</div>


	<div class="input-container">
		<div class="detailPanel">
			<h1 id="detailLabel">Details<h1></br>
			<p id="contentForDetail">@Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<button id="showRequested" onclick="openNav()">See what's requested</button>
		</div>

		<div id="tips-panel">
			<header id="titleForTips">
				Tips for selling your items
			</header>
			<section id="contentForTips">
				<ul>
					<li>
						Upload Photo</br>
						<span id="detail">
							@Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
						</span>

					</li>
					<!--photo description-->

					<li>
						Add title and description</br>
						<span id="detail">
							@Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
						</span>
					</li>
					<!--title and description-->

					<li>
						Set price and quality of items</br>
						<span id="detail">
							@Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
						</span>
					</li>
					<!--price and quality-->

					<li>
						Hashtags</br>
						<span id="detail">
							@Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
						</span>
					</li>
					<!--hashtags-->

				</ul>
			</section>
		</div>

		<form action="Sellpage.php" method="post" enctype="multipart/form-data">
			<div class="upload-Panel">
				<div class = "category">
						<label id="categoryLabel">Category</label></br>
						<select id="categorySelectBar" name="product_category">

										<option value = 1 selected >   </option>
										<option value = 2  > Clothe  </option>
										<option value = 3  > Appliance  </option>
										<option value = 4  > Etc  </option>
							</select>
				</div>
				<div class="photo">
					<label id="photoLabel">Photo</label></br>
					<p id="recommend">Recommended - photograph always gives a much better response<p></br>

<!--Image IO / Preview ____________________________________________________________________________ -->
					<form id="photoUploadPanel" action="sellpage.php" method="post" enctype="multipart/form-data">
							<label for="product_image" class="custom-file-upload">
								Add a photo
							</label>
							<br>
							<div id="preview">
							</div>
					</form>
					<p id="addupto">You can add up to 3 images</p>
<!-- ____________________________________________________________________________ -->
				</div>
				<div class="description">
					<input type = "file" multiple name ="product_image" id = "files" >
					<label id="descriptionLabel">Description</label></br>
					<textarea id="textareaTextBox" name="product_description" placeholder="Add Description to your product! "><?php if(isset($_SESSION['product_description'])){echo $_SESSION['product_description'];} ?></textarea>
				</div>

				<div class="price">
					<label id="priceLabel">Price</label></br>
					<input id="priceTextBox" type="text" class="form-control" name="product_price" placeholder="price(HKD)" value =<?php if(isset($_SESSION['product_price'])){print_r($_SESSION['product_price']);} ?>>
				</div>

				<div class="quality">
						<label id="qualityLabel">Quality</label></br>
						<div id="radios">
<?php					for ($x = 0; $x < sizeof($quality); $x++) {										?>
								<label ="new"> <?php echo $quality[$x] ;?> </label>
								<input type="radio" name="product_quality" value = <?php echo $x; ?> <?php if((int)$_SESSION['product_quality'] == $x){ echo 'checked';}?>>
  						  <span class="checkmark"></span>
<?php 					}
																												 	?>
						</div>
				</div>

			<!--Receive hashtags -->
			<div class="hashtag">
				<label id="hashtagLabel">Hashtag</label></br>
				<textarea id="hashtagTextArea" name="product_hashtag" placeholder="Add Hashtags to your product!"><?php if(isset($_SESSION['product_hashtag'])){echo $_SESSION['product_hashtag'];} ?></textarea>
			</div>

				<input class="button" type="submit" name= "btn_clear"  value="Clear Detail" action ="sellpage.php" >
				<br>
				<input class="button" type="submit" name= "btn_product_submit"  value="Add Product" action ="sellpage.php" >

			</br>
		</form>
<!--Upload Image file -->

	</div><!--upload panel-->


</div><!-- input container -->



</body>
</html>
