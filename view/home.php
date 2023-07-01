<?php

require_once('./inc/functions.php');
require_once('./controller/clotheController.php');

$functions = new Functions();
$clotheController = new clotheController();

//pega todas as peças de roupa disponíveis
// $clothes = json_decode($clotheController->getAll());
// shuffle($clothes);

$items = $clotheController->getAll();
$imgs = $clotheController->getImg();


//realiza a "compra" de uma peça de roupa
if (isset($_GET['clothe_id'])) {
	$id = $_GET['clothe_id'];

	$clotheController->buy($id);
}

//limpa a lista de comprados
if (isset($_GET['clean'])) {
	$clotheController->destroy();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="view/style.css">

</head>

<body>
	<img class="logo" src="./assets/images/hstern.png" alt="logo" />
	<img class="title" src="./assets/images/collection.png" alt="logo" />
	<h4>scraped</h4>


	<!-- <h1 class="title">DESTAQUES</h1> -->
	<a href="?clean" class="clean">Limpar lista de compra</a>
	<div id="image-container"></div>


	<div id="clothes" class="container">
		<?php
		foreach ($items as $item) {
			// Access object properties
			$title = $item['title'];
			$price = $item['price'];
			$img = $item['img'];
			// echo print_r($item);
			// Render the item
			echo '<div class="clothe-card">';
			echo '<div class="image-container">';
			echo '<img src="' . $img[0] . '" alt="' . $title . '" />';
			echo '<img src="' . $img[1] . '" alt="' . $title . '" class="hover-image" />';
			echo '</div>';
			echo '<h5>' . $title . '</h5>';
			echo '<h5>' . $price . '</h5>';
			if ($functions->alreadyPurchased($title)) {
				echo "<p class='purchased'>Comprado</p>";
			} else {
				echo "<a href='?p=final&clothe_id=" . $title . "'>Comprar</a>";
			}
			echo '</div>';
		}
		?>

	</div>

	<img class="banner" src="./assets/images/banner.jpg" alt="logo" />
	<footer>
	</footer>
</body>
<script>
	var images = <?php echo json_encode($imgs); ?>; // Assuming $imgs is an array of image URLs

	function getRandomPosition() {
		var screenWidth = window.innerWidth;
		var screenHeight = window.innerHeight * 1.5;
		var randomX = Math.floor(Math.random() * (screenWidth - 50));
		var randomY = Math.floor(Math.random() * (screenHeight - 50));
		return [randomX, randomY];
	}

	function checkCollision(img, positions) {
		var imgRect = img.getBoundingClientRect();

		for (var i = 0; i < positions.length; i++) {
			var pos = positions[i];
			var rect = {
				top: pos[1],
				right: pos[0] + 160,
				bottom: pos[1] + 160,
				left: pos[0]
			};

			if (
				imgRect.top < rect.bottom &&
				imgRect.right > rect.left &&
				imgRect.bottom > rect.top &&
				imgRect.left < rect.right
			) {
				return true; // Collision detected
			}
		}

		return false; // No collision
	}

	window.addEventListener('load', function () {
		var imageContainer = document.getElementById('image-container');
		var positions = [];

		images.forEach(function (imgUrl) {
			var img = document.createElement('img');
			img.src = imgUrl;
			img.alt = 'img';
			img.className = 'random-image';

			var isCollision = true;
			var randomX, randomY;

			// Keep generating new positions until no collision occurs
			while (isCollision) {
				[randomX, randomY] = getRandomPosition();
				isCollision = checkCollision(img, positions);
			}

			positions.push([randomX, randomY]);
			img.style.top = randomY + 'px';
			img.style.left = randomX + 'px';

			imageContainer.appendChild(img);
		});
	});
</script>

</html>
<!-- <?php

// echo var_dump($items);
// echo print_r($items);
//echo var_dump($items);
//for ($i = 0; $i < count($items); $i++) {
?>

					<div class="clothe-card">
						<img src="<?= $items[$i]->img ?>" alt="<?= $items[$i]->title ?>" />
						<h5><?= $items[$i]->title ?></h5>

					<?php
					// if($functions->alreadyPurchased($items[$i]->id)){
					// 	echo "<p class='purchased'>Comprado</p>";
					// }else{
					// 	echo "<a href='?p=final&clothe_id=".$items[$i]->id."'>Comprar</a>";
					// }
					?>

								
						<div class="info">
							<p>Gênero - <?= $items[$i]->price ?></p>
							<!-- <p>Material - <?= $clothes[$i]->material ?></p>
							<p>Cor - <?= $clothes[$i]->color ?></p>
							<p>Origem - <?= $clothes[$i]->origin ?></p>
							<p>Tipo - <?= $clothes[$i]->type ?></p> -->